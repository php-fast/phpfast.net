<?php
//# Trang này rất quan trọng, quan trọng nhất trong CMS. Giúp tạo và quản lý nhiều loại bài viết.
//Viết controller này đầu tiên (Step 1)
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\PostTypeModel;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Libraries\Validate;

class PosttypeController extends BaseController
{
    protected $posttypeModel;

    public function __construct()
    {
        load_helpers(['backend']);
        $this->posttypeModel = new PostTypeModel();
        $this->data('header', Render::component('backend/component/header'));
        $this->data('footer', Render::component('backend/component/footer'));
    }

    // Trang danh sách Post Types
    public function index()
    {
        $postTypes = $this->posttypeModel->getAllPostTypes();
        $this->data('postTypes', $postTypes);
        $this->render('backend', 'backend/posttype/index');
    }

    // Trang tạo Post Type mới
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            $this->data('csrf_token', Session::csrf_token(600));
            $this->render('backend', 'backend/posttype/add');
        }
    }

    // Lưu Post Type mới vào database
    public function store()
    {
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'fields' => $_POST['fields'] ?? ''
        ];
        $rules = [
            'name' => [
                'rules' => [Validate::notEmpty(), Validate::length(3, 150)],
                'messages' => ['Tên Post Type không được để trống.', 'Tên Post Type phải có độ dài từ 3 đến 150 ký tự.']
            ],
            'slug' => [
                'rules' => [Validate::notEmpty(), Validate::length(3, 150)],
                'messages' => ['Slug không được để trống.', 'Slug phải có độ dài từ 3 đến 150 ký tự.']
            ]
        ];

        $validator = new Validate();
        if ($validator->check($data, $rules)) {
            if ($this->posttypeModel->createPostType($data)) {
                Session::flash('success', 'Tạo Post Type thành công.');
                redirect(admin_url('post-types'));
            } else {
                Session::flash('error', 'Có lỗi xảy ra khi tạo Post Type.');
                $this->add();
            }
        } else {
            $errors = $validator->getErrors();
            $this->data('errors', $errors);
            $this->add();
        }
    }

    // Các hàm khác: edit, update, delete ...
}