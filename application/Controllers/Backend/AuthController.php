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

    public function __construct()
    {
        load_helpers(['backend']);
        $this->usersModel = new UsersModel();
        $this->assets = new Assets();
        Flang::load('auth', LANG);

        $this->assets->add('css', 'css/style.css', 'head');
        $this->assets->add('js', 'js/jfast.1.1.2.js', 'footer');
        $this->assets->add('js', 'js/authorize.js', 'footer');
        //$header = Render::component('backend/component/header');
        //$footer = Render::component('backend/component/footer');
        $this->data('header', '');
        $this->data('footer', '');
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
                'username'  =>  $_POST['username'] ?? '',
                'password'  =>  $_POST['password'] ?? ''
            ];
            $rules = [
                'username' => [
                    'rules' => [Validate::alnum("@."), Validate::length(5, 150)],
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
     
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        // $this->data('footer', 'Trang nay khong can footer');
        // Gọi layout chính và truyền dữ liệu cùng với các phần render
        $this->render('backend', 'backend/auth/login');
    }
    
    // Xử lý đăng nhập
    public function _login($input)
    {
        //$2y$10$jJzcVXtMuqC3LKSjtX2I0ulknNZCZmJuP8lIlKBq0vaTWAJYFZamu la admin
        if (!filter_var($input['username'], FILTER_VALIDATE_EMAIL)) {
            $user = $this->usersModel->getUserByUsername($input['username']);
        }else{
            $user = $this->usersModel->getUserByEmail($input['username']);
        }
        
        // echo Security::hashPassword($input['password']);die;
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
        //Buoc validate neu co request register.
        if (isset($_POST['username'])){
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed') );
                redirect(admin_url('auth/register'));
            }
            $input = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'password_verify' => $_POST['password_verify'],
                'phone' => $_POST['phone'],
                'telegram' => $_POST['telegram'] ?? '',
                'skype' => $_POST['skype'] ?? '',
                'whatsapp' => $_POST['whatsapp'] ?? '',
            ];
            $rules = [
                'username' => [
                    'rules' => [
                        Validate::alnum('@.-+_'), 
                        Validate::length(6, 30)
                    ],
                    'messages' => [
                        Flang::_e('username_invalid'),
                        Flang::_e('username_length', 6, 30)
                    ]
                ],
                'email' => [
                    'rules' => [
                        Validate::email(),
                        Validate::length(6, 150)
                    ],
                    'messages' => [
                        Flang::_e('email_invalid'),
                        Flang::_e('email_length', 6, 150)
                    ]
                ],
                'phone' => [
                    'rules' => [
                        Validate::phone(),
                        Validate::length(10)
                    ],
                    'messages' => [
                        Flang::_e('phone_invalid'),
                        Flang::_e('phone_length', 10)
                    ]
                ],
                'password' => [
                    'rules' => [
                        Validate::length(6, 60),
                    ],
                    'messages' => [
                        Flang::_e('password_length', 6, 60),
                    ]
                ],
                'password_verify' => [
                    'rules' => [
                        Validate::equals($input['password'])
                    ],
                    'messages' => [
                        Flang::_e('password_verify_invalid', $input['password_verify'])
                    ]
                ],
                'telegram' => [
                    'rules' => [
                        Validate::alnum('@.-+_'),
                        Validate::length(6, 100)
                    ],
                    'messages' => [
                        Flang::_e('telegram_length', 6, 100)
                    ]
                ],
                'skype' => [
                    'rules' => [
                        Validate::alnum('@.-+_'),
                        Validate::length(6, 100)
                    ],
                    'messages' => [
                        Flang::_e('skype_length', 6, 100)
                    ]
                ],
                'whatsapp' => [
                    'rules' => [
                        Validate::phone(),
                        Validate::length(10)
                    ],
                    'messages' => [
                        Flang::_e('whatsapp_length', 10)
                    ]
                ]
            ];
            
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                // Lấy các lỗi và hiển thị
                $errors = $validator->getErrors();
                
                $this->data('errors', $errors);
            }else{
                $errors = [];
                if ($this->usersModel->isUsernameExists($input['username'])) {
                    $errors['username'] = array(
                        Flang::_e('username_double', $input['username'])
                    );
                    $isExists = true;
                }
                if ($this->usersModel->isEmailExists($input['email'])) {
                    $errors['email'] = array(
                        Flang::_e('email_double', $input['email'])
                    );
                    $isExists = true;
                }
                if (!isset($isExists) && empty($errors)){
                    $input['password'] = Security::hashPassword($input['password']);
                    $input['avatar'] = '';
                    $input['role'] = 'admin';
                    $input['permissions'] = config('admin', 'Roles');
                    $input['permissions'] = json_encode($input['permissions']);
                    $input['status'] = 'inactive';
                    $input['created_at'] = DateTime();
                    $input['updated_at'] = DateTime();
                    return $this->_register($input);
                }else{
                    $this->data('errors', $errors);
                }
            }
        }
        
        // Hiển thị trang đăng nhập: Nếu ko có request login, or validate that bai
        $this->data('title', Flang::_e('register_welcome'));
        $this->data('csrf_token', Session::csrf_token(600)); //token security login chi ton tai 10 phut.
        
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        
        $this->render('backend', 'backend/auth/register');
    }
    
    // Xử lý đăng ký tài khoản
    private function _register($input)
    {
        // Tạo mã kích hoạt 6 ký tự cho người dùng nhập vào
        $activationNo = strtoupper(random_string(6)); // Tạo mã gồm 6 ký tự
        // Tạo mã kích hoạt riêng cho URL
        $activationCode = strtolower(random_string(20)); // Tạo mã gồm 20 ký tự
        $optionalData = [
            'activation_no' => $activationNo,
            'activation_code' => $activationCode,
            'activation_expires' => time()+86400,
        ];
        $input['optional'] = json_encode($optionalData);
        //Them Data Nguoi Dung Vao Du Lieu
        $user_id = $this->usersModel->addUser($input);

        if ($user_id) {
            // Gửi email kích hoạt
            $activationLink = admin_url('auth/activation/' . $user_id . '/' . $activationCode.'/');
            //$emailContent = Render::component('common/email/activation', ['username' => $input['username'], 'activation_link' => $activationLink]);
            //echo $emailContent;die;
            $this->mailer = new Fastmail();
            $this->mailer->send($input['email'], 'Kích hoạt tài khoản', 'activation', ['username' => $input['username'], 'activation_link' => $activationLink, 'activation_no' => $activationNo]);

            Session::flash('success', 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.');
            redirect(admin_url("auth/activation/{$user_id}/"));
        } else {
            Session::flash('error', 'Đăng ký không thành công. Vui lòng thử lại.');
            redirect(admin_url('auth/register'));
        }
    }

    public function activation($user_id = '', $activationCode = null)
    {
        // Lấy thông tin người dùng từ ID
        $user = $this->usersModel->getUserById($user_id);
        if (!$user) {
            Session::flash('error', 'Tài khoản không tồn tại.');
            redirect(admin_url('auth/login'));
            return;
        }
        if ($user['status'] != 'inactive'){
            Session::flash('success', 'Tài khoản đã được kích hoạt.');
            redirect(admin_url('auth/login'));
            return;
        }

        $user_optional = @json_decode($user['optional'], true);

        $user_active_expires = $user_optional['activation_expires'] ?? 0;

        // Nếu người dùng yêu cầu gửi lại mã
        if (isset($_POST['activation_resend'])) {
            return $this->_activation_resend($user_id, $user_optional, $user);
        }

        if ($user_active_expires < time()){
            $this->data('error', 'Mã kích hoạt đã bị hết hạn.');
            return $this->_activation_form($user_id);
        }

        // Trường hợp người dùng truy cập qua URL
        if ($activationCode) {
            $user_active_code = $user_optional['activation_code'] ?? '';
            if (!empty($user_active_code) && strtolower($user_active_code) === strtolower($activationCode)) {
                // Kích hoạt tài khoản
                return $this->_activation($user_id);
            } else {
                $this->data('error', 'Mã kích hoạt không hợp lệ.');
                return $this->_activation_form($user_id);
            }
        }

        // Trường hợp người dùng nhập mã vào form
        if (isset($_POST['activation_no'])) {
            $activationNo = S_POST('activation_no');
            $user_active_no = $user_optional['activation_no'] ?? '';
            if (!empty($user_active_no) && strtoupper($user_active_no) === strtoupper($activationNo)) {
                // Kích hoạt tài khoản
                $this->_activation($user_id);
            } else {
                $this->data('error', 'Mã kích hoạt không hợp lệ.');
                $this->_activation_form($user_id);
            }
        } else {
            // Hiển thị form nhập mã kích hoạt
            $this->_activation_form($user_id);
        }
    }
        //Forgot Password
    public function forgot_password(){

        if(isset($_POST['email'])) {
            $input = [ 
                'email' => $_POST['email']
            ];
            $rules = [
                'email' => [
                    'rules' => [
                        Validate::email(),
                        Validate::length(6, 150)
                    ],
                    'messages' => [
                        Flang::_e('email_invalid'),
                        Flang::_e('email_length', 6, 150)
                    ]
                ],
            ];
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                $errors = $validator->getErrors();
                $this->data('errors', $errors);     
            }else{
                if (!$this->usersModel->isEmailExists($input['email'])) {
                    $errors['email'] = array(
                        Flang::_e('email_exist', $input['email'])
                    );
                    $this->data('errors', $errors);     
                }
                else {
                    $user = $this->usersModel->getUserByEmail($input['email']);
                    $user_optional = @json_decode($user['optional'], true);
                    $this->_forgot_send($user_optional, $user);
                }
            }
        }
        
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        
        $this->render('backend', 'backend/auth/forgot_password');
    }

    public function reset_password() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $this->check_token($token);
        }
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));

        $this->render('backend', 'backend/auth/reset_password');
    }

    public function update_pass() {
        if(isset($_POST['password'])) {
            $token = $_POST['token'];
            $user_id = $this->check_token($token);
            $input = [
                'password' => $_POST['password'],
            ];
            $rules = [
            'password' => [
                    'rules' => [
                        Validate::length(6, 60),
                    ],
                    'messages' => [
                        Flang::_e('password_length', 6, 60),
                    ]
                ]
            ];
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                $errors = $validator->getErrors();
                
                $this->data('errors', $errors);
            }
            else {
                $input['password'] = Security::hashPassword($input['password']);
                $this->usersModel->updateUser($user_id, $input);
                
                $success = 'Reset password success';
                $this->data('success', $success);
                $this->data('csrf_token', Session::csrf_token(600));
                $this->data('assets_header', $this->assets->header('backend'));
                $this->data('assets_footer', $this->assets->footer('backend'));
            
            $this->render('backend', 'backend/auth/login');
          }

        }
    }

    private function check_token($token) {

            $parts = explode('abec', $token);
            $user_id = $parts[0];
            $token = $parts[1];
            $user = $this->usersModel->getUserById($user_id);
            if($user) {
                $optional = isset($user['optional']) ? $user['optional'] : null;
                $optional_data = json_decode($optional, true);

                $token_db = $optional_data['token_reset_password'];
                $token_expires = $optional_data['token_reset_password_expires'];
              
                if($token !== $token_db) {
                    $error = 'Đường dẫn sai, vui lòng nhập lại email dế reset password';
                    $this->data('error', $error);
                    $this->data('assets_footer', $this->assets->footer('backend'));
                    $this->data('assets_header', $this->assets->header('backend'));
                    $this->render('backend', 'backend/auth/forgot_password');
                    die();
                }
                if($token_expires <= date('U')){
                    $error = 'Đường dẫn hết hạn, vui lòng nhập lại email dế reset password';
                    $this->data('error', $error);
                    $this->data('assets_footer', $this->assets->footer('backend'));
                    $this->data('assets_header', $this->assets->header('backend'));
                    $this->render('backend', 'backend/auth/forgot_password');
                    die();
                }
         }
         return $user_id;

    }   



    private function _activation_resend($user_id, $user_optional, $user)
    {
        // Tạo mã kích hoạt 6 ký tự cho người dùng nhập vào
        $activationNo = strtoupper(random_string(6)); // Tạo mã gồm 6 ký tự
        // Tạo mã kích hoạt riêng cho URL
        $activationCode = strtolower(random_string(20)); // Tạo mã gồm 20 ký tự
        if (empty($user_optional)){
            $user_optional = [];
        }/*  */
        $user_optional['activation_no'] = $activationNo;
        $user_optional['activation_code'] = $activationCode;
        $user_optional['activation_expires'] = time()+86400;
        $this->usersModel->updateUser($user_id, ['optional'=>json_encode($user_optional)]);

        // Gửi email mã kích hoạt mới
        $activationLink = admin_url('auth/activation/' . $user_id . '/' . $activationCode.'/');
        $this->mailer = new Fastmail();
        $this->mailer->send($user['email'], 'Mã lích hoạt lại tài khoản', 'activation', ['username' => $user['username'], 'activation_link' => $activationLink, 'activation_no' => $activationNo]);

        Session::flash('success', 'Mã kích hoạt mới đã được gửi tới email của bạn.');
        redirect(admin_url('auth/activation/' . $user_id));
    }   
    // send email forgot password
    private function _forgot_send($user_optional, $user)
    {
        $user_id = $user['id'];
        // tạo token forgot password
        $token = bin2hex(random_bytes(32));
        // Tạo mã kích hoạt 6 ký tự cho người dùng nhập vào

        $user_optional['token_reset_password'] = $token;
        $user_optional['token_reset_password_expires'] = time()+86400;
        $this->usersModel->updateUser($user_id, ['optional'=>json_encode($user_optional)]);

        // Construct reset link 
        $reset_link = 'https://domainweb.com/admin/auth/reset_password/?token=' . $user_id . 'abec' . $token;
        
        // Gửi email link reset password
        $this->mailer = new Fastmail();
        $this->mailer->send($user['email'], 'Link reset password for user', 'reset_password', ['username' => $user['username'], 'reset_link' => $reset_link]);

        Session::flash('success', 'Link reset password đã được gửi đến email của bạn.');
        // redirect(admin_url('auth/activation/' . $user_id));
    }   

    /**
     * Hiển thị form nhập mã kích hoạt
     */
    private function _activation_form($user_id)
    {
        $this->data('title', Flang::_e('active_welcome'));
        $this->data('user_id', $user_id);
        $this->render('backend', 'backend/auth/activation');
    }
    private function _activation($user_id)
    {
        $this->usersModel->updateUser($user_id, [
            'status' => 'active',
            'optional' => null
        ]);
    
        Session::flash('success', 'Tài khoản của bạn đã được kích hoạt thành công.');
        redirect(admin_url('auth/login'));
    }

    // Kiểm tra quyền truy cập (middleware)
    // public function _checkPermission($controller, $action)
    // {
    //     $permissions = Session::get('permissions');
    //     if (!$permissions) {
    //         return false;
    //     }

    //     if (isset($permissions[$controller]) && in_array($action, $permissions[$controller])) {
    //         return true;
    //     }

    //     return false;
    // }
}
