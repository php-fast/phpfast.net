<?php
//# Trang này rất quan trọng, giúp tạo nhiều ngôn ngữ cho website. Nó có liên kết đặc biệt với Controller Posts
namespace App\Controllers\Backend;
use System\Core\BaseController;
use App\Models\LanguagesModel;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Libraries\Assets;


class LanguagesController extends BaseController {
    protected $languagesModel;
    protected $assets;


    public function __construct()
    {
        load_helpers(['backend']);
        $this->languagesModel = new LanguagesModel();
        $this->assets = new Assets();

        $this->assets->add('css', 'css/style.css', 'head');
        $this->assets->add('js', 'js/jfast.1.1.2.js', 'footer');
        $this->assets->add('js', 'js/authorize.js', 'footer');

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
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->render('dashbroad', 'backend/languages/index');
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
                redirect(admin_url('languages'));
            }
        }
    }

    // Chỉnh sửa ngôn ngữ
    public function edit($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', 'Ngôn ngữ không tồn tại.');
            redirect(admin_url('languages'));
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
            redirect(admin_url('languages'));
        }

        $this->data('language', $language);
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->data('title', 'Chỉnh sửa ngôn ngữ');
        $this->render('dashbroad', 'backend/languages/edit');
    }

    // Xóa ngôn ngữ
    public function delete($id)
    {
        $language = $this->languagesModel->getLanguageById($id);

        if (!$language) {
            Session::flash('error', 'Ngôn ngữ không tồn tại.');
            redirect(admin_url('languages'));
        }

        // Không cho phép xóa ngôn ngữ mặc định
        if ($language['is_default'] == 1) {
            Session::flash('error', 'Không thể xóa ngôn ngữ mặc định.');
            redirect(admin_url('languages'));
        }

        $this->languagesModel->del($this->languagesModel->_table(), 'id = ?', [$id]);
        Session::flash('success', 'Xóa ngôn ngữ thành công.');
        redirect(admin_url('languages'));
    }

    // get language active
    public function getActiveLanguages()
    {
        $languages = $this->languagesModel->getActiveLanguages();
        return $languages;
    }
}