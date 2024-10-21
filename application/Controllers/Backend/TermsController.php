<?php
namespace App\Controllers;
namespace App\Controllers\Backend;
use App\Models\TermsModel;
use App\Models\LanguagesModel;
use System\Libraries\Session;
use App\Libraries\Fastlang as Flang;
use System\Libraries\Render;
use System\Libraries\Assets;
use System\Core\BaseController;
use App\Helpers\Backend_helper;
use System\Libraries\Validate;

class TermsController extends BaseController {
    protected $termsModel;
    protected $assets;
    protected $LanguagesModel;
    public function __construct()
    {
        load_helpers(['backend']);
        $this->termsModel = new TermsModel();
        $this->LanguagesModel = new LanguagesModel();
        $this->assets = new Assets();
        Flang::load('terms', LANG);

        $this->assets->add('css', 'css/style.css', 'head');
        $this->assets->add('js', 'js/jfast.1.1.3.js', 'footer');
        $this->assets->add('js', 'js/terms.js', 'footer');
        $sidebar = Render::component('backend/component/main_sidebar');
        $header = Render::component('backend/component/header');
        $footer = Render::component('backend/component/footer');
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));
        $this->data('sidebar', $sidebar);
        $this->data('header', $header);
        $this->data('footer', $footer);
    }

    // Hiển thị danh sách tất cả các term
    public function index() {
        
        $langActive = $this->LanguagesModel->getActiveLanguages();
        $this->data('csrf_token', Session::csrf_token(600));
        if(HAS_GET('type') && HAS_GET('posttype'))
        {
            $type = S_GET('type') ?? '';
            $posttype = S_GET('posttype') ?? '';
            $allTerm = $this->termsModel->getTaxonomiesByTypeAndPostType($type, $posttype);
            $tree = $this->treeTerm($allTerm);
            
            $this->data('title', Flang::_e('title_index') . ' - ' . $posttype . ($type ? ' - ' . $type : ''));
            $this->data('type', $type); 
            $this->data('posttype', $posttype);
            $this->data('allTerm', $allTerm);
            $this->data('langActive', $langActive);
            $this->data('tree', $tree);
            $this->render('backend', 'backend/terms/index');
        } else {
            $allTerm = $this->termsModel->getTaxonomies();
            $tree = $this->treeTerm($allTerm);
            // $this->data('csrf_token', Session::csrf_token(600));
            $this->data('allTerm', $allTerm);
            $this->data('title', 'Term Management');
            $this->data('tree', $tree);
            $this->data('langActive', $langActive);
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
            // et name lang
            if (!empty($node['lang'])) {
                $lang = $this->LanguagesModel->getLanguageById($node['lang']);
                if ($lang) {
                    $node['lang_name'] = $lang['name'];
                } else {
                    $node['lang_name'] = '';
                }
            } else {
                $node['lang_name'] = '';
            }

            if (!empty($node['parent'])) {
                $result[$node['parent']]['children'][] = &$node;
                $node['parent_name'] = $result[$node['parent']]['name'];
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
    public function add() {
        $name = S_POST('name');
        $type = S_POST('type');
        $posttype = S_POST('posttype');
        $parent = S_POST('parent');
        $lang = S_POST('lang');
        $counter = 2;

        if(empty($parent) || $parent == 0) {
            $parent = null;
        }
        if(empty(HAS_POST('slug'))) {
            $slug = url_slug($name);
        } else {
            $slug = S_POST('slug');
        }
        $allTerm = $this->termsModel->getTaxonomies();
        $description = S_POST('description');
        if($this->termsModel->getTermBySlug($slug)) {
            $newslug = $slug . '-' . $counter;
            while ($this->termsModel->getTermBySlug($newslug)) {
                $newslug = $slug . '-' . $counter;
                $counter++;
            }
            $slug = $newslug;
        }
        $input = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'type' => $type,
            'posttype' => $posttype,
            'parent' => $parent,
            'lang' => $lang
        ];
        $rules = [
            'name' => [
                'rules' => [
                    Validate::notEmpty(''),
                    Validate::length(6, 30)
                ],
                'messages' => [
                    Flang::_e('name_empty'),
                    Flang::_e('name_length', 6, 30)
                ]
            ],
            'slug' => [
                'rules' => [
                    Validate::notEmpty(),
                ],
                'messages' => [
                    Flang::_e('slug_empty'),
                ]   
            ],
            'description' => [
                'rules' => [
                    Validate::notEmpty(),
                    Validate::length(6, 150)
                ],
                'messages' => [
                    Flang::_e('description_empty'),
                    Flang::_e('description_length', 6, 150)
                ]
            ],
            'type' => [
                'rules' => [
                    Validate::notEmpty()
                ],
                'messages' => [
                    Flang::_e('type_empty', 6, 30)
                ]
            ],
            'posttype' => [
                'rules' => [
                    Validate::notEmpty(),
                ],
                'messages' => [
                    Flang::_e('posttype_empty', 6, 60),
                ]
            ],
            'parent' => [],
            'lang' => [
                'rules' => [
                    Validate::notEmpty(),
                ],
                'messages' => [
                    Flang::_e('lang_empty'),
                ]
            ],

        ];
        $validator = new Validate();
        if (!$validator->check($input, $rules)) {
            // Lấy các lỗi và hiển thị
            $errors = $validator->getErrors();
            $this->data('errors', $errors);     
        }else{
            $this->termsModel->addTerm($input);
        }

        $redirectUrl = admin_url('terms/?posttype=' . $posttype . '&type=' . $type);
        $redirectUrl = rtrim($redirectUrl, '/');

        redirect($redirectUrl);
    }

    // Chỉnh sửa một term
    public function edit($posttype, $type, $termId) {

        if(HAS_POST('name')) {
            // print_r($termId);die;
            $input = [
                'name' => S_POST('name'),
                'slug' => S_POST('slug'),
                'type' => S_POST('type'),
                'posttype' => S_POST('posttype'),
                'parent' => S_POST('parent'),
                'lang' => S_POST('lang'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $rules = [
                'name' => [
                    'rules' => [
                        Validate::notEmpty(''),
                        Validate::length(6, 30)
                    ],
                    'messages' => [
                        Flang::_e('name_empty'),
                        Flang::_e('name_length', 6, 30)
                    ]
                ],
                'slug' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('slug_empty'),
                    ]   
                ],
                'type' => [
                    'rules' => [
                        Validate::notEmpty()
                    ],
                    'messages' => [
                        Flang::_e('type_empty', 6, 30)
                    ]
                ],
                'posttype' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('posttype_empty', 6, 60),
                    ]
                ],
                'parent' => [],
                'lang' => [
                    'rules' => [
                        Validate::notEmpty(),
                    ],
                    'messages' => [
                        Flang::_e('lang_empty'),
                    ]
                ],
    
            ];

            $validator = new Validate();
            if (!$validator->check($input, $rules)) {
                // Lấy các lỗi và hiển thị
                $errors = $validator->getErrors();
                $this->data('errors', $errors);     
            }else{
                $this->termsModel->setTerm($termId, $input);
                Session::flash('success', Flang::_e('edit_terms_success'));
                $redirectUrl = admin_url('terms/?posttype=' . $posttype . '&type=' . $type);
                $redirectUrl = rtrim($redirectUrl, '/');
                redirect($redirectUrl);
            }

        }
        $data = $this->termsModel->getTermById($termId);
        $lang = $this->LanguagesModel->getLanguages();
        $tree = $this->treeTerm($this->termsModel->getTaxonomiesByTypeAndPostType($type, $posttype));
        $this->data('csrf_token', Session::csrf_token(600));
        $this->data('title', 'Edit term');
        $this->data('lang', $lang);
        $this->data('data', $data);
        $this->data('tree', $tree);
        
        $this->render('backend', 'backend/terms/edit');
    }

    // Xóa term
    public function delete($posttype, $type, $termId) {
        $children = $this->termsModel->getTermByParent($termId);
        if (!empty($children)) {
            foreach ($children as $child) {
                $newdata = [
                    'parent' => null,
                ];
                $this->termsModel->setTerm($child['id'], $newdata);
            }
        }
        Session::flash('success', Flang::_e('delete_terms_success'));
        $this->termsModel->delTerm($termId);
        $redirectUrl = admin_url('terms/?posttype=' . $posttype . '&type=' . $type);
        $redirectUrl = rtrim($redirectUrl, '/');

        redirect($redirectUrl);

    }
}
