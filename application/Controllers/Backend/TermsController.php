<?php
namespace App\Controllers;
namespace App\Controllers\Backend;
use App\Models\TermsModel;
use App\Models\LanguagesModel;
use System\Libraries\Session;
use System\Libraries\Render;
use System\Core\BaseController;
use App\Helpers\Backend_helper;

class TermsController extends BaseController {
    protected  $termModel;
    protected  $LanguagesModel;
    public function __construct()
    {
        load_helpers(['backend']);
        $this->termModel = new TermsModel();
        $this->LanguagesModel = new LanguagesModel();
        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('header', $header);
        $this->data('footer', $footer);
    }
   
    // Hiển thị danh sách tất cả các term
    public function index() {
        
        if(HAS_GET('type') && HAS_GET('posttype'))
        {
            $type = S_GET('type') ?? '';
            $posttype = S_GET('posttype') ?? '';
            $allTerm = $this->termModel->getTaxonomiesByTypeAndPostType($type, $posttype);
            $tree = $this->treeTerm($allTerm);
            $langActive = $this->LanguagesModel->getActiveLanguages();
            $this->data('allTerm', $allTerm);
            $this->data('type', $type); 
            $this->data('posttype', $posttype);
            $this->data('title', 'Term Management'. ' - ' . $posttype . ($type ? ' - ' . $type : ''));
            $this->data('tree', $tree);
            $this->render('backend', 'backend/terms/index');
        }
        else {
            $allTerm = $this->termModel->getTaxonomies();
            $tree = $this->treeTerm($allTerm);
            $this->data('allTerm', $allTerm);
            $this->data('title', 'Term Management');
            $this->data('tree', $tree);
            $this->render('backend', 'backend/terms/index');
        }    
        
    }
    private function treeTerm($term) {
        $result = [];
        $tree = [];
        // Sắp xếp dữ liệu theo id và parent
        foreach ($term as $item) {
            $result[$item['id']] = $item;
            $result[$item['id']]['children'] = [];
        }
    
        // Xây dựng cây phân cấp từ dữ liệu
        foreach ($result as $id => &$node) {
            if (!empty($node['parent'])) {
                $result[$node['parent']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }
    
        // Hàm đệ quy để in ra cây phân cấp
        function printTree($items, $level = 0) {
            foreach ($items as $item) {
                echo str_repeat(' - ', $level) . $item['name'] . "\n";
                if (!empty($item['children'])) {
                    printTree($item['children'], $level + 1);
                }
            }
        }
    
        // In cây phân cấp
      return $tree;
    }
    // Tạo mới một term
    public function create() {
        $name = S_POST('name');
        $type = S_POST('type');
        $posttype = S_POST('posttype');
        $parent = S_POST('parent');
        $lang = S_POST('lang');
    
        $counter = 2;
        if(empty($parent) || $parent == 0) {
            $parent = null;
        }
        if(!empty(HAS_POST('slug'))) {
            $slug = url_slug($name);
        } else {
            $slug = S_POST('slug');
        }
        $allTerm = $this->termModel->getTaxonomies();
        $description = $_POST['description'];
        if($this->termModel->getTermBySlug($slug)) {
            $newslug = $slug . '-' . $counter;
            while ($this->termModel->getTermBySlug($newslug)) {
                $newslug = $slug . '-' . $counter;
                $counter++;
            }
            $slug = $newslug;
        }
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'type' => $type,
            'posttype' => $posttype,
            'parent' => $parent,
            'lang' => $lang
        ];
        $this->termModel->addTerm($data);
        $redirectUrl = admin_url('terms/?posttype=' . $posttype . '&type=' . $type);
        $redirectUrl = rtrim($redirectUrl, '/');

        redirect($redirectUrl);
    }

    // Chỉnh sửa một term
    public function edit($posttype, $type, $termId) {
        $data = $this->termModel->getTermById($termId);
        $tree = $this->treeTerm($this->termModel->getTaxonomiesByTypeAndPostType($type, $posttype));
        $this->data('data', $data);
        $this->data('tree', $tree);
        $this->data('title', 'Chỉnh sửa term');
        $this->render('backend', 'backend/terms/edit');
    }

    // Cập nhật term
    public function update($posttype, $type, $termId) {
        $newdata = [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->termModel->setTerm($termId, $newdata);
       // reload page
        $redirectUrl = admin_url('terms/?posttype=' . $posttype . '&type=' . $type);
        $redirectUrl = rtrim($redirectUrl, '/');
        redirect($redirectUrl);

    }

    // Xóa term
    public function delete($posttype, $type, $termId) {

        $this->termModel->delTerm($termId);
        redirect(admin_url('terms/?posttype=' . $posttype . '&type=' . $type));

    }
}
