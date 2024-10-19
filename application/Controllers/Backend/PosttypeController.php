<?php
//# Trang này rất quan trọng, quan trọng nhất trong CMS. Giúp tạo và quản lý nhiều loại bài viết.
//Viết controller này đầu tiên (Step 1)
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\PostTypeModel;
use App\Models\LanguagesModel;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Libraries\Assets;
use System\Libraries\Validate;
use App\Libraries\Fastlang as Flang;

class PosttypeController extends BaseController
{
    protected $posttypeModel;
    protected $languageModel;
    protected $assets;


    public function __construct()
    {
        load_helpers(['backend']);
        $this->posttypeModel = new PostTypeModel();
        $this->languageModel = new LanguagesModel();

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
    }

    public function index()
    {   
        $structTable = $this->posttypeModel->showStructTable('fast_posts_post_cn');
        foreach( $structTable as $column) {
           print_r($column);
           echo '<br>';
           echo '<br>';
           echo '<br>';
        }
        die;
        $postTypes = $this->posttypeModel->getAllPostTypes();
        $this->data('postTypes', $postTypes);
        $this->data('header', Render::component('backend/component/header'));
        $this->data('footer', Render::component('backend/component/footer'));
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        
        $this ->data('title', 'Danh sách Post Type');
        $this->render('dashbroad', 'backend/posttype/index');
    }

    public function add()
    {   
        // check menthod post
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = json_decode(file_get_contents('php://input'), true);
             // check csrf 
             $csrf_token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
             if (!Session::csrf_verify($csrf_token)){
                 echo json_encode(['error' => 'CSRF token không hợp lệ.']);
                 die;
             }

            if (json_last_error() === JSON_ERROR_NONE && isset($inputData)) {
                
                $isAddPostType = $this->_add($inputData);
                if($isAddPostType['status'] === 'success') {
                    Session::flash('success', $isAddPostType['message']);
                } 
                echo json_encode($isAddPostType);
                die;
            } else {
                echo json_encode(['error' => 'Dữ liệu không hợp lệ.']);
                die;
            }
                   
        }

        // render layout
        $languages = $this->languageModel->getActiveLanguages();
        $this->data('languages', $languages);
        $this->data('title', 'Tạo Post Type');
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->data('csrf_token', Session::csrf_token(600));
        $this->render('dashbroad', 'backend/posttype/add');
    }

    private function _add($data)
    {      
        $defaultLanguage = $this->languageModel->getDefaultLanguage();
        if (!isset($data['languages']) || empty($data['languages'])) {
            $data['languages'] = [$defaultLanguage['code']];
        }
        $data['languages'] = json_encode($data['languages']);
        $data['status'] = $data['status'] ?? 'active';
        $rules = [
            'name' => [
                'rules' => [Validate::notEmpty(), Validate::length(3, 150)],
                'messages' => ['Tên Post Type không được để trống.', 'Tên Post Type phải có độ dài từ 3 đến 150 ký tự.']
            ],
            'slug' => [
                'rules' => [Validate::notEmpty(), Validate::length(3, 150), Validate::lowercase() ],
                'messages' => ['Slug không được để trống.', 'Slug phải có độ dài từ 3 đến 150 ký tự.', 'Slug phải viết thường.']
            ],
            'languages' => [
                'rules' => [Validate::json()],
                'messages' => ['Ngôn ngữ phải là chuỗi JSON hợp lệ.']
            ],
            'status' => [
                'rules' => [Validate::notEmpty(), Validate::in(['active', 'inactive'])],
                'messages' => ['Trạng thái không được để trống.', 'Trạng thái phải là active hoặc inactive.']
            ]
        ];
        $rules_field = [
            'type' => [
                'rules' => [Validate::notEmpty()],
                'messages' => ['Type không được để trống.']
            ],
            'label' => [
                'rules' => [Validate::notEmpty()],
                'messages' => ['Label không được để trống.']
            ],
            'field_name' => [
                'rules' => [Validate::notEmpty(), Validate::lowercase()],
                'messages' => ['Field Name không được để trống.', 'Field Name phải viết thường.']
            ],
            'description' => [
                'rules' => [Validate::notEmpty()],
                'messages' => ['Description không được để trống.']
            ],
            'required' => [
                'rules' => [Validate::notEmpty(), Validate::in([true, false])],
                'messages' => ['Required không được để trống.', 'Required phải là true hoặc false.']
            ],
            'visibility' => [
                'rules' => [Validate::notEmpty(), Validate::in([true, false])],
                'messages' => ['Visibility không được để trống.', 'Visibility phải là true hoặc false.']
            ],
            'collapsed' => [
                'rules' => [Validate::in([true, false])],
                'messages' => ['Collapsed phải là true hoặc false.']
            ],
        ];

        $validator = new Validate();
        if ($validator->check($data, $rules)) {
            $messages = [];
            foreach($data['fields'] as $i => $field) {
                if (!$validator->check($field, $rules_field)) {
                    $errors = $validator->getErrors();
                    foreach ($errors as $field => $messagesArray) {
                        foreach ($messagesArray as $message) {
                            $messages[] = ucfirst($field) . "fied thu ".($i + 1) .": " . $message;
                        }
                    }
                }
            }
            if (!empty($messages)) {
                $messages = implode('<br>', $messages);
                return [
                    'status' => 'error',
                    'message' => $messages
                ];
            }
            $data['fields'] = json_encode($data['fields']);
            if ($this->posttypeModel->createPostType($data)) {
                $data['languages'] = convers_array($data['languages']);
                foreach($data['languages'] as $lang) {
                    $tableName = table_posttype($data['slug'], $lang);
                    $data['fields'] = convers_array($data['fields']);
                    
                    $sqldatatest = $this->posttypeModel->createPostTypeTable($tableName, $data['fields']);
                }
                return [
                    'status' => 'success',
                    'message' => 'Tạo Post Type thành công.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Tạo Post Type thất bại.'
                ];
            }
        } else {
            $errors = $validator->getErrors();
            foreach ($errors as $field => $messagesArray) {
                foreach ($messagesArray as $message) {
                    $messages[] = ucfirst($field) . ": " . $message;
                }
            }
            $errorMessage = implode("<br>", $messages); 
            return [
                'status' => 'error',
                'message' => $errorMessage
            ];
        }
    }

    public function edit($id) {
        $postType = $this->posttypeModel->getPostTypeByID($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = json_decode(file_get_contents('php://input'), true);
            if(json_last_error() === JSON_ERROR_NONE && isset($inputData)) { 
                $rules = 
              $result = $this->_edit($postType, $inputData);
              echo json_encode( $result);
              die;
            }
            die;
        }

        $languages = $this->languageModel->getActiveLanguages();
        $this->data('postType', $postType);
        $this->data('languages', $languages);
        $this->data('title', 'Edit '. $postType['name']);
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->data('csrf_token', Session::csrf_token(600));
        $this->render('dashbroad', 'backend/posttype/edit');
    }
    
    private function _edit($old_data, $new_data) {
        $change_data = array();
        if (isset($old_data['languages']) && isset($new_data['languages'])) {
            $old_data['languages'] = json_decode($old_data['languages']);
            sort($old_data['languages']);
            sort($new_data['languages']);
            if ($old_data['languages'] !== $new_data['languages']) {
                $added_languages = array_diff($new_data['languages'], $old_data['languages']);
                $added_languages = array_values($added_languages);
                $removed_languages = array_diff($old_data['languages'], $new_data['languages']);
                $removed_languages = array_values($removed_languages);
                if(!empty($removed_languages) && is_array($removed_languages)) {
                    foreach($removed_languages as $lang) {
                        $tableName = table_posttype($old_data['slug'], $lang);
                        $this->posttypeModel->dropPostTypeTable($tableName);
                    }
                }
                if(!empty($added_languages && is_array($added_languages))) {
                    foreach($added_languages as $lang) {
                        $tableName = table_posttype($old_data['slug'], $lang);
                        $orginal_table = table_posttype($old_data['slug'], $old_data['languages'][0]);
                        $this->posttypeModel->duplicateTable($tableName, $orginal_table);
                    }
                }
                $change_data['languages'] = json_encode($new_data['languages']);
            }
        }

        if(isset($old_data['slug']) && isset($new_data['slug'])) {
            if ($old_data['slug'] !== $new_data['slug']) {
                if(isset($new_data['languages']) && is_array($new_data['languages'])) {
                    foreach($new_data['languages'] as $lang) {
                        $newTableName = table_posttype($new_data['slug'], $lang);
                        $oldTableName = table_posttype($old_data['slug'], $lang);
                        $this->posttypeModel->changeTableName($oldTableName, $newTableName);
                    }
                }
                $change_data['slug'] = $new_data['slug'];
            }
        }
        if(isset($old_data['status']) && isset($new_data['status'])) {
            if ($old_data['status'] !== $new_data['status']) {
                $change_data['status'] = $new_data['status'];
            }
        }
        if(isset($old_data['fields']) && isset($new_data['fields'])) {
            $tableName = table_posttype($old_data['slug'], $old_data['languages'][0]);
            $structTable = $this->posttypeModel->showStructTable($tableName);
// dang lam
            foreach( $structTable as $column) {
                $fields[] = $column['Field'];
            }
        }
        
        if (empty($change_data)) {
            return ['status' => 'error', 'message' => 'Không có dữ liệu thay đổi.'];
        }
        $this->posttypeModel->updatePostType($old_data['id'], $change_data);
        return ['status' => 'success', 'message' => 'Cập nhật thành công.'];
    }

    public function delete($id)
    {   
        $postType = $this->posttypeModel->getPostTypeByID($id);
        $languages = convers_array($postType['languages']);
        if ($this->posttypeModel->deletePostType($id)) {
            foreach($languages as $lang) {
                $tableName = table_posttype($postType['slug'], $lang);
                $this->posttypeModel->dropPostTypeTable($tableName);
            }
            Session::flash('success', 'Xóa Post Type thành công.');
        }
        redirect(admin_url('posttype'));
    }
}