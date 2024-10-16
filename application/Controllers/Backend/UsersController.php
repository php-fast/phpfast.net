<?php
//# Trang này giúp quản lý người dùng và phân quyền người dùng có quyền access những trang nào thôi nè. Cũng không có gì ghê gớm lắm.
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\UsersModel;
use System\Libraries\Session;
use System\Libraries\Security;
use App\Libraries\Fastmail;
use App\Libraries\Fastlang as Flang;
use System\Libraries\Assets;
use System\Libraries\Validate;

class UsersController extends BaseController {

    protected $usersModel;
    protected $mailer;
    protected $assets;

    public function __construct()
    {
        load_helpers(['backend']);
        $this->usersModel = new UsersModel();
        $this->assets = new Assets();
        Flang::load('users', LANG);

        $this->assets->add('css', 'css/style.css', 'head');
        $this->assets->add('js', 'js/jfast.1.1.3.js', 'footer');
        $this->assets->add('js', 'js/authorize.js', 'footer');
        $this->data('header', '');
        $this->data('footer', '');
    }
    public function index() {
        if (Session::has('user_id')) {
            if (Session::get('role') === 'admin') {

                $users = $this->usersModel->getUsers();
                
                $this->data('users', $users);
                $this->data('title', 'Welcome Roles Pages');
                $this->data('assets_header', $this->assets->header('backend'));
                $this->data('assets_footer', $this->assets->footer('backend'));

                $this->render('backend', 'backend/users/list');
            } else {
                redirect(admin_url('dashboard'));
            }
        }
        else {
            redirect(auth_url('login'));
        }

    }

    public function add() {
        $admin = config('admin', 'Roles');
        $moderator = config('moderator', 'Roles');
        $author = config('author', 'Roles');
        $member = config('member', 'Roles');

        $roles = [
            'admin' => $admin,
            'moderator' => $moderator,
            'author' => $author,
            'member' => $member
        ];

        $status = ['active', 'inactive', 'banned'];
        $this->data('roles', $roles);
        $this->data('status', $status);
        $this->data('title', Flang::_e('title_add_member'));
        $this->data('csrf_token', Session::csrf_token(600)); 
        $this->render('backend', 'backend/users/add');
    }

    public function create() {
        if (HAS_POST('username')){

            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed'));
                redirect(auth_url('login'));
            }
            $permissions = $_POST['permissions'] ?? [];
            if (!is_array($permissions)) {
                $permissions = [];
            }
            $input = [
                'username' => S_POST('username') ?? '',
                'fullname' => S_POST('fullname') ?? '',
                'email' => S_POST('email') ?? '',
                'phone' => S_POST('phone') ?? '',
                'role' => S_POST('role') ?? '',
                'password' => S_POST('password') ?? '',
                'password_repeat' => S_POST('password_repeat'),
                'permissions' => json_encode($permissions)
            ];
            $rules = [
                'username' => [
                    'rules' => [
                        Validate::alnum('_'),
                        Validate::length(6, 30)
                    ],
                    'messages' => [
                        Flang::_e('username_invalid'),
                        Flang::_e('username_length', 6, 30)
                    ]
                ],
                'fullname' => [
                    'rules' => [
                        Validate::length(6, 30)
                    ],
                    'messages' => [
                        Flang::_e('fullname_length', 6, 50)
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
                        Validate::length(6, 30)
                    ],
                    'messages' => [
                        Flang::_e('phone_invalid'),
                        Flang::_e('phone_length', 6, 30)
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
                'password_repeat' => [
                    'rules' => [
                        Validate::equals($input['password'])
                    ],
                    'messages' => [
                        Flang::_e('password_repeat_invalid', $input['password_repeat'])
                    ]
                ],
                'role' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('role_option'),
                    ]
                ],
                'permissions' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('permission_array_json'),
                    ]
                ],

            ];
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                // Lấy các lỗi và hiển thị
                $errors = $validator->getErrors();
                $this->data('errors', $errors);     
            }else{
                
                $errors = [];
                if ($this->usersModel->getUserByUsername($input['username'])) {
                    $errors['username'] = array(
                        Flang::_e('username_double', $input['username'])
                    );
                    $isExists = true;
                }
                
                if ($this->usersModel->getUserByEmail($input['email'])) {
                    $errors['email'] = array(
                        Flang::_e('email_double', $input['email'])
                    );
                    $isExists = true;
                }
                if (!isset($isExists) && empty($errors)){
                    $input['password'] = Security::hashPassword($input['password']);
                    $input['status'] = 'inactive';
                    $input['created_at'] = DateTime();
                    $input['updated_at'] = DateTime();
                    return $this->_addmember($input);
                } else {
                    $this->data('errors', $errors);
                }
            }
        }
        else {
            $this->data('title', Flang::_e('title_add_member'));
            $this->data('csrf_token', Session::csrf_token(600)); //token security login chi ton tai 10 phut.
            
            $this->data('title', Flang::_e('title_add_member'));
            $this->data('csrf_token', Session::csrf_token(600)); 
            $this->render('backend', 'backend/users/add');
        }
    }

    private function _addmember($input)
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
            $activationLink = auth_url('activation/' . $user_id . '/' . $activationCode.'/');
            //$emailContent = Render::component('common/email/activation', ['username' => $input['username'], 'activation_link' => $activationLink]);
            $this->mailer = new Fastmail();
            $this->mailer->send($input['email'], 'Kích hoạt tài khoản', 'activation', ['username' => $input['username'], 'activation_link' => $activationLink, 'activation_no' => $activationNo]);
            
            $users = $this->usersModel->getUsers();
            
            $this->data('success', Flang::_e('create_member_success'));
            $this->data('csrf_token', Session::csrf_token(600));
            
            $this->data('assets_header', $this->assets->header('backend'));
            $this->data('assets_footer', $this->assets->footer('backend'));
                
            $this->data('users', $users);
            $this->data('title', 'Welcome Roles Pages');
            
            $this->render('backend', 'backend/users/list');
        } else {
            Session::flash('error', Flang::_e('register_error'));
            redirect(auth_url('dashboard'));
        }
    }
}