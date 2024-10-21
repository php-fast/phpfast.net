<?php
namespace System\Libraries;
use System\Core\AppException;
use Exception;

// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Render {
    /**
     * Tên của theme
     * @var string
     */
    private static $themeName;

    /**
     * Đường dẫn thư mục theme
     * @var string
     */
    private static $themePath;

     /**
     * Khởi tạo và load cấu hình theme một lần duy nhất
     */
    private static function init() {
        if (self::$themeName === null || self::$themePath === null) {
            // Lấy cấu hình theme từ file config
            $themeConfig = config('theme');
            // Lưu tên theme và đường dẫn theme
            self::$themeName = $themeConfig['theme_name'] ?? 'default';
            $themeRelativePath = $themeConfig['theme_path'] ?? 'application/Views';
            self::$themePath = ROOT_PATH . '/' . $themeRelativePath . '/' . self::$themeName . '/';
            unset($themeConfig);
            unset($themeRelativePath);
        }
    }

    /**
     * Lấy tên của theme
     * 
     * @return string Tên theme
     */
    private static function _name() {
        self::init();
        return self::$themeName;
    }

    /**
     * Lấy đường dẫn của theme
     * 
     * @return string Đường dẫn thư mục theme
     */
    private static function _path_theme() {
        self::init();
        return self::$themePath;
    }

    /**
     * Lấy path của một component trong themes
     * 
     * @param string $component Tên component cần lấy path
     * @return string Đường dẫn đến component
     */
    public static function _path_component($component) {
        return self::_path_theme() . 'component/';
    }

    /**
     * Lấy path của thư mục theme theo controller
     * Ví dụ: controller Home thì thư mục theme là home/
     * 
     * @param string $controller Tên của controller
     * @return string Đường dẫn đến thư mục theme của controller
     */
    public static function _path_controller($controller) {
        return self::_path_theme() . strtolower($controller) . '/';
    }

    /**
     * Render toàn bộ layout và view với dữ liệu
     * 
     * @param string $layout Tên layout cần load (ví dụ: 'layout' hoặc 'layout2')
     * @param string $view Tên view cần load (ví dụ: 'home/home')
     * @param array $data Dữ liệu truyền vào view
     * @throws \Exception
     */
    public static function render($layout, $view, $data = []) {
        self::init(); // Đảm bảo cấu hình đã được load

        $layoutPath = self::_path_theme() . $layout . '.php';
        $viewPath = self::_path_theme() . $view . '.php';

        if (!file_exists($layoutPath)) {
            throw new AppException("Layout '{$layout}' not found at Path: '{$layoutPath}'.");
        }
        if (!file_exists($viewPath)) {
            throw new AppException("View '{$view}' not found at Path: '{$viewPath}'.");
        }

        // Thêm path của view vào data để truyền vào layout
        $data['view'] = $viewPath;

        // Truyền dữ liệu vào view
        extract($data);
        // Bắt đầu buffer để lưu output vào chuỗi
        ob_start();
        // Gọi layout chính và hiển thị nội dung
        require_once $layoutPath;
        return ob_get_clean();  // Trả về chuỗi
    }

    /**
     * Render một component cụ thể và trả về dưới dạng chuỗi
     * 
     * @param string $component Tên component cần render (ví dụ: 'header', 'footer')
     * @param array $data Dữ liệu truyền vào component
     * @return string Kết quả render component
     * @throws \Exception
     */
    public static function component($component, $data = []) {
        self::init(); // Đảm bảo cấu hình đã được load

        $componentPath = self::_path_theme() . $component . '.php';

        if (!file_exists($componentPath)) {
            throw new \Exception("Component '{$component}' không tồn tại tại đường dẫn '{$componentPath}'.");
        }

        // Truyền dữ liệu vào component
        extract($data);

        // Bắt đầu buffer để lưu output
        ob_start();
        require $componentPath;
        return ob_get_clean();
    }

    /**
     * Phương thức pagination: tạo phân trang và render qua Views
     * 
     * @param string $base_url URL cơ bản cho phân trang
     * @param int $current_page Số trang hiện tại
     * @param int $total_items Tổng số lượng item
     * @param int $items_per_page Số item mỗi trang
     * @param int $num_links Số liên kết trang hiển thị
     * @param array $query_params Các tham số query khác để giữ trên URL
     * @param array $custom_names Tên các biến tùy chỉnh trong query string (page, limit, sortby, sortsc)
     * 
     * @return string HTML phân trang
     */
    public static function pagination($base_url, $current_page, $total_items, $items_per_page = 10, $num_links = 5, $query_params = [], $custom_names = []) {
        self::init();
        $total_pages = ceil($total_items / $items_per_page);
        
        if ($total_pages <= 1) {
            return ''; // Không cần phân trang nếu chỉ có 1 trang
        }

        // Tên biến mặc định cho phân trang
        $default_names = [
            'page'    => 'page',
            'limit'   => 'limit',
            'sortby'  => 'sortby',
            'sortsc'  => 'sortsc'
        ];

        // Kết hợp các biến tùy chỉnh với các tên biến mặc định
        $custom_names = array_merge($default_names, $custom_names);

        // Tạo URL query string cho các tham số còn lại (sortby, sortsc, limit, ...)
        $query_params[$custom_names['limit']] = $items_per_page;

        // Chuẩn bị dữ liệu cho view phân trang
        $start = max(1, $current_page - floor($num_links / 2));
        $end = min($total_pages, $current_page + floor($num_links / 2));

        $data = [
            'base_url'       => $base_url,
            'current_page'   => $current_page,
            'total_pages'    => $total_pages,
            'start'          => $start,
            'end'            => $end,
            'query_params'   => $query_params,
            'custom_names'   => $custom_names
        ];
        // Sử dụng view pagination.php để render HTML phân trang
        return self::component('common/pagination/pagination', $data);
    }

    /**
     * Phương thức pagination2: tạo phân trang dạng Previous/Next
     * 
     * @param string $base_url URL cơ bản cho phân trang
     * @param int $current_page Số trang hiện tại
     * @param bool $is_next Có trang tiếp theo không
     * @param array $query_params Các tham số query khác để giữ trên URL
     * @param array $custom_names Tên các biến tùy chỉnh trong query string (page, ...)
     * 
     * @return string HTML phân trang dạng Previous/Next
     */
    public static function pagination2($base_url, $current_page, $is_next, $query_params = ['limit' =>  10], $custom_names = []) {
        self::init();
    
        // Tên biến mặc định cho phân trang
        $default_names = [
            'page' => 'page',
        ];
    
        // Kết hợp các biến tùy chỉnh với các tên biến mặc định
        $custom_names = array_merge($default_names, $custom_names);
    
        // Tạo query string cho các tham số khác (ngoài page)
        $query_string = http_build_query($query_params);
    
        // Loại bỏ ?page=1 nếu đang ở trang 1
        if ($current_page == 1) {
            $page_query_string = $query_string ? '?' . $query_string : ''; // Không có ? nếu không có query string khác
        } else {
            $page_query_string = '?' . $custom_names['page'] . '=' . $current_page;
            if ($query_string) {
                $page_query_string .= '&' . $query_string;
            }
        }
    
        // URL cho trang trước và trang sau
        $prev_page_url = $current_page > 2 ? $base_url . '?' . $custom_names['page'] . '=' . ($current_page - 1) . '&' . $query_string : ($query_string ? $base_url . '?' . $query_string : $base_url);
        $next_page_url = $base_url . '?' . $custom_names['page'] . '=' . ($current_page + 1) . '&' . $query_string;
    
        // Loại bỏ dấu & thừa
        $prev_page_url = rtrim($prev_page_url, '&');
        $next_page_url = rtrim($next_page_url, '&');
    
        $data = [
            'base_url'       => $base_url,
            'current_page'   => $current_page,
            'is_next'        => $is_next,
            'prev_page_url'  => $prev_page_url,
            'next_page_url'  => $next_page_url,
            'custom_names'   => $custom_names,
            'query_params'   => $query_string
        ];    

        // Sử dụng view pagination2.php để render HTML phân trang
        return self::component('common/pagination/pagination2', $data);
    }
}
