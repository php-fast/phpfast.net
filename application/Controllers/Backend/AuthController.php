<?php
namespace App\Controllers\Backend;

use System\Core\BaseController;
use App\Models\UsersModel;
use System\Libraries\Security;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Libraries\Assets;
use App\Libraries\Fastmail;
use App\Libraries\Fastlang as Flang;
use System\Libraries\Validate;


class AuthController extends BaseController
{
    protected $usersModel;
    protected $assets;
    protected $mailer;
    protected $t;

    public function __construct()
    {
        load_helpers(['backend']);
        $this->usersModel = new UsersModel();
        $this->assets = new Assets();
        Flang::load('auth', LANG);

        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('header', $header);
        $this->data('footer', $footer);
    }

    // Kiểm tra trạng thái đăng nhập
    public function index()
    {
        if (Session::has('user_id')) {
            // Nếu đã đăng nhập, chuyển hướng đến dashboard
            redirect(admin_url('dashboard'));
        } else {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            redirect(admin_url('auth/login'));
        }
    }

    // Hiển thị form đăng nhập
    public function login()
    {
        //Buoc validate neu co request login.
        if (isset($_POST['username'])){
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed') );
                redirect(admin_url('auth/login'));
            }
            $input = [
                'username'  =>  S_POST('username') ?? '',
                'password'  =>  S_POST('password') ?? ''
            ];
            $rules = [
                'username' => [
                    'rules' => [Validate::alnum(), Validate::length(5, 30)],
                    'messages' => [Flang::_e('username_invalid'), Flang::_e('username_length', 5, 30)]
                ],
                'password' => [
                    'rules' => [Validate::length(5, null)],
                    'messages' => [Flang::_e('password_length', 5)]
                ]
            ];
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                // Lấy các lỗi và hiển thị
                $errors = $validator->getErrors();
                $this->data('errors', $errors);
            }else{
                return $this->_login($input);
            }
        }

        // Hiển thị trang đăng nhập: Nếu ko có request login, or validate that bai
        $this->data('title', Flang::_e('login_welcome'));
        $this->data('csrf_token', Session::csrf_token(600)); //token security login chi ton tai 10 phut.


        // Thêm các tệp CS dạng file và inline vao head
        $this->assets->add('css', 'movies.css', 'head');
        $this->assets->inline('css', '.highlight { color: red; }', 'head');
        // Thêm các tệp JS dạng file và inline vào head
        $this->assets->add('js', 'jfast.1.1.0.js', 'footer');
        $this->assets->inline('js', <<<'EOD'
            console.log("Inline JS at Auth/Login");
            //alert('Login'); 
        EOD, 'footer');
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));

        $this->data('footer', 'Trang nay khong can footer');
        // Gọi layout chính và truyền dữ liệu cùng với các phần render
        $this->render('backend', 'backend/auth/login');
    }

    // Xử lý đăng nhập
    public function _login($input)
    {
        //$2y$10$jJzcVXtMuqC3LKSjtX2I0ulknNZCZmJuP8lIlKBq0vaTWAJYFZamu la admin
        $user = $this->usersModel->getUserByUsername($input['username']);
        //echo Security::hashPassword($input['password']);die;
        if ($user && Security::verifyPassword($input['password'], $user['password'])) {
            if ($user['status'] !== 'active') {
                Session::flash('error', Flang::_e('users_noactive', $input['username']));
                redirect(admin_url('auth/login'));
                exit();
            }

            // Set thông tin đăng nhập vào session
            Session::set('user_id', $user['id']);
            Session::set('role', $user['role']);
            Session::set('permissions', json_decode($user['permissions'], true));
            // Tái tạo session ID để tránh session fixation
            Session::regenerate();

            redirect(admin_url('dashboard'));
        } else {
            Session::flash('error', Flang::_e('login_failed', $input['username']) );
            redirect(admin_url('auth/login'));
        }
    }

    // Đăng xuất
    public function logout()
    {
        Session::del('user_id');
        Session::del('role');
        Session::del('permissions');
        redirect(admin_url('auth/login'));
        exit();
    }

    // Đăng ký tài khoản mới
    public function register()
    {
        if (isset($_POST['username'])) {
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed') );
            }else{
                unset($csrf_token);
            }
        }

        if (isset($csrf_token) || !isset($_POST['username'])) {
            $this->data('title', Flang::_e('register_welcome'));
            $this->data('csrf_token', Session::csrf_token(900)); //token security login chi ton tai 15 phut.

            $this->render('backend', 'backend/auth/register');
        } else {
            print_r($_POST);
        }
    }

    // Xử lý đăng ký tài khoản
    private function _register()
    {
        $csrfToken = S_POST('csrf_token') ?? '';

        // Kiểm tra CSRF token
        if (!Session::csrf_verify($csrfToken)) {
            Session::flash('error', 'Token không hợp lệ.');
            redirect(admin_url('auth/register'));
        }

        $username = S_POST('username') ?? '';
        $email = S_POST('email') ?? '';
        $password = S_POST('password') ?? '';

        if ($this->usersModel->getUserByUsername($email)) {
            Session::flash('error', 'Email đã được sử dụng.');
            redirect(admin_url('auth/register'));
        }

        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => Security::hashPassword($password),
            'role' => 'member',
            'status' => 'inactive',
        ];

        $userId = $this->usersModel->addUser($userData);

        if ($userId) {
            // Gửi email kích hoạt
            $activationLink = admin_url('auth/activation/' . $userId);
            $emailContent = Render::component('common/email/activation', ['username' => $username, 'activation_link' => $activationLink]);
            $this->mailer->send($email, 'Kích hoạt tài khoản', $emailContent);

            Session::flash('success', 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.');
            redirect(admin_url('auth/login'));
        } else {
            Session::flash('error', 'Đăng ký không thành công. Vui lòng thử lại.');
            redirect(admin_url('auth/register'));
        }
    }

    //Forgot Password
    public function forgot(){
        echo 1;
    }

    // Kiểm tra quyền truy cập (middleware)
    public function checkPermission($controller, $action)
    {
        $permissions = Session::get('permissions');
        if (!$permissions) {
            return false;
        }

        if (isset($permissions[$controller]) && in_array($action, $permissions[$controller])) {
            return true;
        }

        return false;
    }
}
