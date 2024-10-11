<?php
//# Trang này rất quan trọng, giúp tạo nhiều ngôn ngữ cho website. Nó có liên kết đặc biệt với Controller Posts
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\LanguagesModel;
use System\Libraries\Session;
use System\Libraries\Render;

class LanguagesController extends BaseController {
    protected $languagesModel;

    public function __construct()
    {
        load_helpers(['backend']);
        $this->languagesModel = new LanguagesModel();
        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('header', $header);
        $this->data('footer', $footer);
    }

    // Liệt kê danh sách ngôn ngữ
    public function index()
    {
        $languages = $this->languagesModel->getLanguages();
        $this->data('languages', $languages);
        $this->data('title', 'Quản lý ngôn ngữ');
        $this->render('backend', 'backend/languages/index');
    }

    // Thêm ngôn ngữ mới
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = S_POST('name') ?? '';
            $code = S_POST('code') ?? '';
            $status = S_POST('status') ?? 'inactive';
            $flat = S_POST('flat') ?? '';
            $isDefault = isset($_POST['is_default']) ? 1 : 0;


            if ($name && $code) {
                if ($isDefault) {
                    $this->languagesModel->unsetDefaultLanguage(); // Bỏ mặc định cho tất cả ngôn ngữ khác
                }
                
                $this->languagesModel->add($this->languagesModel->_table(), [
                    'name' => $name,
                    'code' => $code,
                    'status' => $status,
                    'is_default' => $isDefault,
                    'flat' => $flat
                ]);

                Session::flash('success', 'Ngôn ngữ mới đã được thêm thành công.');
                redirect(admin_url('languages/index'));
            } else {
                Session::flash('error', 'Vui lòng nhập đầy đủ thông tin.');
                redirect(admin_url('languages/add'));
            }
        }
    }

    // Chỉnh sửa ngôn ngữ
    public function edit($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', 'Ngôn ngữ không tồn tại.');
            redirect(admin_url('languages/index'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = S_POST('name') ?? '';
            $code = S_POST('code') ?? '';
            $status = S_POST('status') ?? 'inactive';
            $is_default = isset($_POST['is_default']) ? 1 : 0;

            if ($is_default) {
                $this->languagesModel->unsetDefaultLanguage(); // Bỏ mặc định tất cả trước khi đặt ngôn ngữ mới
            }

            $this->languagesModel->set($this->languagesModel->_table(), [
                'name' => $name,
                'code' => $code,
                'status' => $status,
                'is_default' => $is_default
            ], 'id = ?', [$id]);

            //Them buoc thay doi ten cac bang Posts lien quan den ngon ngu nua

            Session::flash('success', 'Chỉnh sửa ngôn ngữ thành công.');
            redirect(admin_url('languages/index'));
        }

        $this->data('language', $language);
        $this->render('backend', 'backend/languages/edit');
    }

    // Xóa ngôn ngữ
    public function delete($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', 'Ngôn ngữ không tồn tại.');
            redirect(admin_url('languages/index'));
        }

        // Không cho phép xóa ngôn ngữ mặc định
        if ($language['is_default'] == 1) {
            Session::flash('error', 'Không thể xóa ngôn ngữ mặc định.');
            redirect(admin_url('languages/index'));
        }

        $this->languagesModel->del($this->languagesModel->_table(), 'id = ?', [$id]);
        Session::flash('success', 'Xóa ngôn ngữ thành công.');
        redirect(admin_url('languages/index'));
    }

    // get language active
    public function getActiveLanguages()
    {
        $languages = $this->languagesModel->getActiveLanguages();
        return $languages;
    }
}