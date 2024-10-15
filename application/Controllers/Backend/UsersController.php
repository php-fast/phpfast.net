<?php
//# Trang này giúp quản lý người dùng và phân quyền người dùng có quyền access những trang nào thôi nè. Cũng không có gì ghê gớm lắm.
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\UsersModel;
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
use System\Libraries\Assets;


class UsersController extends BaseController {

    protected $usersModel;
    protected $assets;

    public function __construct()
    {
        load_helpers(['backend']);
        $this->usersModel = new UsersModel();
        $this->assets = new Assets();

        $this->assets->add('css', 'css/style.css', 'head');
        $this->assets->add('js', 'js/jfast.1.1.3.js', 'footer');
        $this->assets->add('js', 'js/authorize.js', 'footer');
        //$header = Render::component('backend/component/header');
        //$footer = Render::component('backend/component/footer');
        // $sidebar = Render::component('backend/component/auth_sidebar');
        // $this->data('sidebar', $sidebar);
        $this->data('header', '');
        $this->data('footer', '');
    }
    public function index() {
        if (Session::get('role') === 'admin') {
            $users = $this->usersModel->getUsers();
            
            $this->data('users', $users);
            $this->data('title', 'Welcome Roles Pages');
            $this->data('assets_header', $this->assets->header('backend'));
            $this->data('assets_footer', $this->assets->footer('backend'));

            $this->render('backend', 'backend/users/list');
        } else {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            redirect(admin_url('dashboard'));
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
        $this->data('csrf_token', Session::csrf_token(600)); 
        $this->data('title', 'Add member');
        $this->render('backend', 'backend/users/add');
    }

    public function create() {

        if (HAS_POST('username')){
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed'));
                redirect(auth_url('login'));
            }
            // check permission luôn là mảng
            $permissions = $_POST['permission'] ?? [];
            if (!is_array($permissions)) {
                $permissions = [];
            }
            $permissions_json = json_encode($permissions);

            $input = [
                'username' => S_POST('role') ?? '',
                'fullname' => S_POST('fullname') ?? '',
                'email' => S_POST('email') ?? '',
                'phone' => S_POST('phone') ?? '',
                'password' => S_POST('password') ?? '',
                'password_repeat' => S_POST('password_repeat'),
                'status' => S_POST('status') ?? '',
                'permission' => $permissions
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
                'status' => [
                    'rules' => [
                        Validate::phone(),
                        Validate::length(6, 30)
                    ],
                    'messages' => [
                        Flang::_e('phone_invalid'),
                        Flang::_e('phone_length', 6, 30)
                    ]
                ],
                'permission' => [
                    'rules' => [
                        Validate::inArray(),
                    ],
                    'messages' => [
                        Flang::_e('phone_invalid'),
                    ]
                ],

            ];

            $role = S_POST('role');
        }

    }
}