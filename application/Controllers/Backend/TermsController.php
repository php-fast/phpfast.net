<?php
namespace App\Controllers;
namespace App\Controllers\Backend;
use App\Models\TermModel;
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
        $this->termModel = new TermModel();
        $this->LanguagesModel = new LanguagesModel();
        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('header', $header);
        $this->data('footer', $footer);
    }
   
    // Hiển thị danh sách tất cả các term
    public function index($posttype = null, $type = null) {
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
    private function treeTerm($term) {
        $result = [];
        $tree = [];
        // Sắp xếp dữ liệu theo id và parent_id
        foreach ($term as $item) {
            $result[$item['id']] = $item;
            $result[$item['id']]['children'] = [];
        }
    
        // Xây dựng cây phân cấp từ dữ liệu
        foreach ($result as $id => &$node) {
            if (!empty($node['parent_id'])) {
                $result[$node['parent_id']]['children'][] = &$node;
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
        $name = $_POST['name'];
        $type = $_POST['type'];
        $posttype = $_POST['posttype'];
        $parent_id = $_POST['parent_id'];
        $counter = 2;
        if(empty($parent_id) || $parent_id == 0) {
            $parent_id = null;
        }   
        if(empty($_POST['slug'])) {
            $slug = to_url($name);
        } else {
            $slug = $_POST['slug'];
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
            'parent_id' => $parent_id
        ];
        $query = $this->termModel->addTerm($data);
        redirect('/admin/term/'.$posttype.'/'.$type);
    }

    // Chỉnh sửa một term
    public function edit($posttype, $type, $edit, $termId) {
        $data = $this->termModel->getTermById($termId);
        $tree = $this->treeTerm($this->termModel->getTaxonomiesByTypeAndPostType($type, $posttype));
        $this->data('data', $data);
        $this->data('tree', $tree);
        $this->data('title', 'Chỉnh sửa term');
        $this->render('backend', 'backend/terms/edit');
    }



    // Cập nhật term
    public function update($posttype, $type, $update, $termId) {
        $newdata = [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'description' => $_POST['description'],
            'updated_at' => date('Y-m-d H:i:s')
            
        ];
       $this->termModel->setTerm($termId, $newdata);
       // reload page
       redirect('/admin/term/'.$posttype.'/'.$type);
    }

    // Xóa term
    public function delete($posttype, $type, $update, $termId) {
        $this->termModel->delTerm($termId);
        redirect('/admin/term/'.$posttype.'/'.$type);
    
    }
}
