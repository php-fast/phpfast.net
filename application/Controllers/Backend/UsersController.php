<?php
//# Trang này giúp quản lý người dùng và phân quyền người dùng có quyền access những trang nào thôi nè. Cũng không có gì ghê gớm lắm.
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\UsersModel;
use System\Libraries\Session;
use System\Libraries\Render;
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
        $sidebar = Render::component('backend/component/main_sidebar');
        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('sidebar', $sidebar);
        $this->data('header', $header);
        $this->data('footer', $footer);
    }

    public function index() {
        if (Session::has('user_id')) {
            $users = $this->usersModel->getUsers();
            $this->data('users', $users);
            $this->data('title', 'Welcome Roles Pages');
            $this->assets->add('js', 'js/users.js', 'footer');
            $this->data('assets_header', $this->assets->header('backend'));
            $this->data('assets_footer', $this->assets->footer('backend'));
            $this->render('backend', 'backend/users/list');
        }
        else {
            redirect(auth_url('login'));
        }
    }
    //index, add, edit, delete, upda
    public function add() {
        if (HAS_POST('username')){
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)){
                $this->data('error', Flang::_e('csrf_failed'));
            }

            $permissions = S_POST('permissions') ?? [];
            if (!is_array($permissions)) {
                $permissions = [];
            }

            $input = [
                'username' => S_POST('username') ?? '',
                'fullname' => S_POST('fullname') ?? '',
                'email' => S_POST('email') ?? '',
                'phone' => S_POST('phone') ?? '',
                'password' => S_POST('password') ?? '',
                'password_repeat' => S_POST('password_repeat'),
                'role' => S_POST('role') ?? '',
                'permissions' => json_encode($permissions),
                'status' => S_POST('status') ?? '',
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
                'status' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('status_option'),
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
                if (empty($isExists) && empty($errors)){
                    $input['password'] = Security::hashPassword($input['password']);
                    //xu ly them 1 so field social
                    
                    $input['created_at'] = DateTime();
                    $input['updated_at'] = DateTime();
                    return $this->_add($input);
                } else {
                    $this->data('errors', $errors);
                }
            }
        }
        //render khi ko co request POST, or bi errors show ra.
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

        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->render('backend', 'backend/users/add');
    }


    private function _add($input)
    {
        if ($input['status'] !== 'active') {
            $activationNo = strtoupper(random_string(6)); // Tạo mã gồm 6 ký tự
            $activationCode = strtolower(random_string(20)); // Tạo mã gồm 20 ký tự
            $optionalData = [
                'activation_no' => $activationNo,
                'activation_code' => $activationCode,
                'activation_expires' => time() + 86400,
            ];
            $input['optional'] = json_encode($optionalData);
        } else {
            $input['optional'] = null;
        }
        $user_id = $this->usersModel->addUser($input);
        if ($user_id) {
            // Nếu status không phải là 'active' thì gửi email kích hoạt
            if ($input['status'] !== 'active') {
                $activationLink = auth_url('activation/' . $user_id . '/' . $activationCode . '/');
                $this->mailer = new Fastmail();
                $this->mailer->send($input['email'], Flang::_e('active_account'), 'activation', ['username' => $input['username'], 'activation_link' => $activationLink]);
            }
            Session::flash('success', Flang::_e('create_member_success'));
            redirect(admin_url('users/add'));
        } else {
            Session::flash('error', Flang::_e('register_error'));
            redirect(auth_url('dashboard'));
        }
    }

    public function edit($user_id) {
        // Check if the user exists
        $user = $this->usersModel->getUserById($user_id);
        if (!$user) {
            // User not found, redirect or show an error message
            Session::flash('error', Flang::_e('user_not_found'));
            redirect(admin_url('users/index'));
        }
    
        if (HAS_POST('username')) {
            $csrf_token = S_POST('csrf_token') ?? '';
            if (!Session::csrf_verify($csrf_token)) {
                $this->data('error', Flang::_e('csrf_failed'));
            }
    
            $permissions = S_POST('permissions') ?? [];
            if (!is_array($permissions)) {
                $permissions = [];
            }
    
            $input = [
                'username' => S_POST('username') ?? '',
                'fullname' => S_POST('fullname') ?? '',
                'email' => S_POST('email') ?? '',
                'phone' => S_POST('phone') ?? '',
                'role' => S_POST('role') ?? '',
                'permissions' => json_encode($permissions),
                'status' => S_POST('status') ?? '',
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
                        Validate::length(6, 50)
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
                'status' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('status_option'),
                    ]
                ]
            ];
    
            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                // Get errors and display
                $errors = $validator->getErrors();
                $this->data('errors', $errors);
            } else {
                $errors = [];
                $existingUser = $this->usersModel->getUserByUsername($input['username']);
                if ($existingUser && $existingUser['id'] != $user_id) {
                    $errors['username'] = array(
                        Flang::_e('username_double', $input['username'])
                    );
                }
                $existingEmailUser = $this->usersModel->getUserByEmail($input['email']);
                if ($existingEmailUser && $existingEmailUser['id'] != $user_id) {
                    $errors['email'] = array(
                        Flang::_e('email_double', $input['email'])
                    );
                }
                if (empty($errors)) {
                    // Since we're not updating the password, we don't process it
                    $input['updated_at'] = DateTime();
                    $this->_edit($user_id, $input);
    
                    // After updating, retrieve the updated user data
                    $user = $this->usersModel->getUserById($user_id);
    
                    // Set success message
                    $this->data('success', Flang::_e('update_member_success'));
                } else {
                    $this->data('errors', $errors);
                }
            }
        }

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
        $this->data('user', $user); // Pass current user data to the view
        $this->data('title', Flang::_e('title_edit_member'));
        $this->data('csrf_token', Session::csrf_token(600));
    
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->render('backend', 'backend/users/edit');
    }
    
    private function _edit($user_id, $input) {
        $dataToUpdate = [
            'username' => $input['username'],
            'fullname' => $input['fullname'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'role' => $input['role'],
            'permissions' => $input['permissions'],
            'status' => $input['status'],
            'updated_at' => $input['updated_at'],
        ];
    
        // Handle optional data
        if ($input['status'] !== 'active') {
            $activationNo = strtoupper(random_string(6)); // Generate a 6-character code
            $activationCode = strtolower(random_string(20)); // Generate a 20-character code
            $optionalData = [
                'activation_no' => $activationNo,
                'activation_code' => $activationCode,
                'activation_expires' => time() + 86400,
            ];
            $dataToUpdate['optional'] = json_encode($optionalData);
        } else {
            $dataToUpdate['optional'] = null;
        }
    
        $result = $this->usersModel->updateUser($user_id, $dataToUpdate);
    
        if ($result) {
            // If status is not 'active', send activation email
            if ($input['status'] !== 'active') {
                $activationLink = auth_url('activation/' . $user_id . '/' . $activationCode . '/');
                $this->mailer = new Fastmail();
                $this->mailer->send($input['email'], Flang::_e('active_account'), 'activation', ['username' => $input['username'], 'activation_link' => $activationLink]);
            }
            Session::flash('success', Flang::_e('create_member_success'));
            redirect(admin_url('users/index'));
        } else {
            // Set an error message
            $this->data('error', Flang::_e('update_error'));
        }
    }

    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ POST request
            
            $user_id = S_POST('user_id') ?? null;
            $status = S_POST('status') ?? null;

            $input = ['status' => $status];
    
            if ($user_id && $status) {
                $user = $this->usersModel->updateUser($user_id, $input);
                if ($user) {
                    $users = $this->usersModel->getUsers();
                    echo json_encode($users);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database update failed']);
                    return;
                }
            }
    
            // Nếu có lỗi, trả về kết quả thất bại
            echo json_encode(['success' => false]);
        } else {
            // Phương thức không được hỗ trợ
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        }
    }
    
    
}