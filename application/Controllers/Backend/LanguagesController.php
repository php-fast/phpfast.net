<?php
//# Trang này rất quan trọng, giúp tạo nhiều ngôn ngữ cho website. Nó có liên kết đặc biệt với Controller Posts
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\LanguagesModel;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Libraries\Assets;
use App\Libraries\Fastlang as Flang;
use System\Libraries\Validate;




class LanguagesController extends BaseController {
    protected $languagesModel;
    protected $assets;


    public function __construct()
    {
        load_helpers(['backend']);
        $this->languagesModel = new LanguagesModel();

        $this->assets = new Assets();
        $this->assets->add('css', 'css/style.css', 'head');
        $this->assets->add('js', 'js/jfast.1.1.3.js', 'footer');
        $this->assets->add('js', 'js/script.js', 'footer');
        $this->assets->add('js', 'js/campaign.js', 'footer');
        $this->assets->add('js', 'js/language.js', 'footer');

        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('header', $header);
        $this->data('footer', $footer);

        Flang::load('Languages', LANG);


    }

    // Liệt kê danh sách ngôn ngữ
    public function index()
    {
        $languages = $this->languagesModel->getLanguages();
        $this->data('languages', $languages);
        $this->data('title', Flang::_e('tile_languages'));
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));

        $this->data('csrf_token', Session::csrf_token()); //token security

        $this->render('dashbroad', 'backend/languages/index');
    }
    // Thêm ngôn ngữ mới
    public function add()
    {   
        // Validate form add new language
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = S_POST('csrf_token') ?? '';
            $name = S_POST('name') ?? '';
            $code = S_POST('code') ?? '';
            $status = S_POST('status') ?? 'inactive';
            $default = S_POST('is_default') ?? 0;

            // check CSRF token
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed') );
                redirect(admin_url('languages'));
            }

            if ($default) {
                $this->languagesModel->unsetDefaultLanguage();
                if($status == 'inactive') {
                    $status = 'active';
                }
            }

            $data = [
                'name' => $name,
                'code' => $code,
                'status' => $status,
                'is_default' => $default,
            ];
            
            $rules = [
               'name' =>  [
                    'rules' => [Validate::length(3, 80)],
                    'messages' => [Flang::_e('length_error', 3, 80)]
               ],
               'code' => [
                    'rules' => [Validate::alpha(), Validate::lowercase() ,Validate::length(2, 2)],
                    'messages' => [Flang::_e('notalpha'), Flang::_e('lowercase_error'), Flang::_e('length_error', 2, 2)]
               ],
                'is_default' => [
                    'rules' => [Validate::in([0, 1])],
                    'messages' => [Flang::_e('in_error')]
                ],
                'status' => [
                    'rules' => [Validate::in(['active', 'inactive'])],
                    'messages' => [Flang::_e('in_error')]
                ]
            ];
            $validator = new Validate();
            if(!$validator->check($data, $rules)){
                $errors = $validator->getErrors();
                foreach ($errors as $field => $messagesArray) {
                    foreach ($messagesArray as $message) {
                        $messages[] = ucfirst($field) . ": " . $message;
                    }
                }
                $errorMessage = implode("<br>", $messages); 
                Session::flash('error', $errorMessage);
            } else {
                if ($default == 1) {
                    $this->languagesModel->unsetDefaultLanguage();
                }
                $status = $this->languagesModel->addLanguage($data);
                if (!$status['success']) {
                    echo $status['message'];
                    Session::flash('error', Flang::_e($status['message']) );
                } else {
                    Session::flash('success', Flang::_e('add_success') );
                }
            }
        }

        redirect(admin_url('languages'));

    }

    // Chỉnh sửa ngôn ngữ
    public function edit($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', Flang::_e('not_exits'));
            redirect(admin_url('languages'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = S_POST('csrf_token') ?? '';
            $name = S_POST('name') ?? '';
            $code = S_POST('code') ?? '';
            $status = S_POST('status') ?? 'inactive';
            $default = S_POST('is_default') ?? 0;

            if ($default) {
                $this->languagesModel->unsetDefaultLanguage();
                if($status == 'inactive') {
                    $status = 'active';
                }
            }

            $data = [
                'name' => $name,
                'code' => $code,
                'status' => $status,
                'is_default' => $default,
            ];

            // check CSRF token
            if (!Session::csrf_verify($csrf_token)){
                Session::flash('error', Flang::_e('csrf_failed') );
                redirect(admin_url('languages'));
            }

            $rules = [
               'name' =>  [
                    'rules' => [Validate::length(3, 80)],
                    'messages' => [Flang::_e('length_error', 3, 80)]
               ],
               'code' => [
                    'rules' => [Validate::alpha(), Validate::lowercase() ,Validate::length(2, 2)],
                    'messages' => [Flang::_e('notalpha'), Flang::_e('lowercase_error'), Flang::_e('length_error', 2, 2)]
               ],
                'is_default' => [
                    'rules' => [Validate::in([0, 1])],
                    'messages' => [Flang::_e('in_error')]
                ],
                'status' => [
                    'rules' => [Validate::in(['active', 'inactive'])],
                    'messages' => [Flang::_e('in_error')]
                ]
            ];
            $validator = new Validate();
            if(!$validator->check($data, $rules)){
                $errors = $validator->getErrors();
                foreach ($errors as $field => $messagesArray) {
                    foreach ($messagesArray as $message) {
                        $messages[] = ucfirst($field) . ": " . $message;
                    }
                }
                $errorMessage = implode("<br>", $messages); 
                Session::flash('error', $errorMessage);
            } else {
                $status = $this->languagesModel->setLanguage($id, $data);
                if (!$status['success']) {
                    Session::flash('error', Flang::_e($status['message']) );
                } else {
                    Session::flash('success', Flang::_e('update_success') );
                }
            }
            redirect(admin_url('languages/edit/' . $id));
        }

            
        $this->data('csrf_token', Session::csrf_token()); //token security
        $this->data('language', $language);
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->data('title', Flang::_e('edit_language') . ' ' . $language['name']);
        $this->render('dashbroad', 'backend/languages/edit');
    }

    // Xóa ngôn ngữ
    public function delete($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', Flang::_e('exits'));
            redirect(admin_url('languages'));
        }

        // Không cho phép xóa ngôn ngữ mặc định
        if ($language['is_default'] == 1) {
            Session::flash('error', Flang::_e('not_del_defaute'));
            redirect(admin_url('languages'));
        }

        $this->languagesModel->del($this->languagesModel->_table(), 'id = ?', [$id]);
        Session::flash('success', Flang::_e('delete_success'));
        redirect(admin_url('languages'));
    }

    public function setdefault($id) {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', Flang::_e('exits'));
            redirect(admin_url('languages'));
        }

        $this->languagesModel->unsetDefaultLanguage();
        
        $data = [
            'is_default' => 1,
            'status' => 'active'
        ];
        $status = $this->languagesModel->setLanguage($id, $data);

        if (!$status['success']) {
            Session::flash('error', Flang::_e($status['message']) );
        } else {
            Session::flash('success', Flang::_e('update_success') );
        }

        redirect(admin_url('languages'));

    }

    public function changestatus($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', Flang::_e('exits'));
            redirect(admin_url('languages'));
        }
        if($language['is_default'] == 1) {
            Session::flash('error', Flang::_e('not_change_default'));
            redirect(admin_url('languages'));
        }
        $status = $language['status'] == 'active' ? 'inactive' : 'active';
        $data = [
            'status' => $status
        ];
        $status = $this->languagesModel->setLanguage($id, $data);

        if (!$status['success']) {
            Session::flash('error', Flang::_e($status['message']) );
        } else {
            Session::flash('success', Flang::_e('update_success') );
        }
        redirect(admin_url('languages'));
    }
}