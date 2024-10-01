<?php
namespace App\Controllers\Backend;

use System\Core\BaseController;
use System\Libraries\Assets;

class MoviesController extends BaseController {
    protected $assets;

    public function __construct() {
        parent::__construct();
        $this->assets = new Assets();
    }

    /**
     * Hiển thị danh sách các bộ phim
     */
    public function index() {
        // Thêm các tệp CSS và JS vào head và footer
        $this->assets->add('css', 'movies.css', 'head');
        $this->assets->add('js', 'movies.js', 'footer');

        // Thêm inline CSS và JS
        $this->assets->inline('css', '.highlight { color: red; }', 'head');
        $this->assets->inline('js', 'console.log("Inline JS Loaded");alert(5);', 'footer');
        
        $this->data('assets_header', $this->assets->header('backend'));
        $this->data('assets_footer', $this->assets->footer('backend'));

        // Truyền dữ liệu cho view
        $this->data('title', 'Danh sách phim');
        $this->data('movies', [
            ['id' => 1, 'title' => 'Movie 1', 'genre' => 'Action'],
            ['id' => 2, 'title' => 'Movie 2', 'genre' => 'Comedy']
        ]);


        // Gọi hàm render và truyền vào layout và view
        echo $this->render('backend', 'backend/movies/list');
    }
}
