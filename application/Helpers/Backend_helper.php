<?php
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}
if (!function_exists('base_url')){
    function base_url($path = '') {
        global $base_url;
        if (empty($base_url)){
            $app_url = config('app');
            $base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($base_url, '/') . '/' . trim($path, '/').'/';
    }
}
if (!function_exists('auth_url')){
    function auth_url($path = '') {
        global $base_url;
        if (empty($base_url)){
            $app_url = config('app');
            $base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($base_url, '/') . '/account/' . trim($path, '/').'/';
    }
}
if (!function_exists('admin_url')){
    function admin_url($path = '') {
        global $base_url;
        if (empty($base_url)){
            $app_url = config('app');
            $base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($base_url, '/') . '/admin/' . trim($path, '/').'/';
    }
}
if (!function_exists('admin_url_lang')){
    function admin_url_lang($path = '') {
        global $base_url;
        if (empty($base_url)){
            $app_url = config('app');
            $base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($base_url, '/') . '/admin/languages/' . trim($path, '/').'/';
    }
}
//admin_url_lang('index') . admin_url_lang('add') => /admin/languages/add
if (!function_exists('admin_url_posttype')){
    function admin_url_posttype($path = '') {
        global $base_url;
        if (empty($base_url)){
            $app_url = config('app');
            $base_url = !empty($app_url['app_url']) ? $app_url['app_url'] : '/';
            unset($app_url);
        }
        return rtrim($base_url, '/') . '/admin/posttype/' . trim($path, '/').'/';
    }
}



// helper function name to url utf8 bỏ dấu nếu tiếng việt
if (!function_exists('to_url')) {
    function to_url($str) {
        // Chuyển đổi sang chữ thường
        $str = mb_strtolower($str, 'UTF-8');
        
        // Chuyển đổi ký tự có dấu sang không dấu
        $unwantedArray = [
            'a' => ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ'],
            'd' => ['đ'],
            'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ'],
            'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị'],
            'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ'],
            'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ừ', 'ử', 'ữ', 'ự'],
            'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ']
        ];

        foreach ($unwantedArray as $nonAccent => $accents) {
            $str = str_replace($accents, $nonAccent, $str);
        }

        // Thay thế các ký tự không hợp lệ sang dấu gạch ngang
        $str = preg_replace('/[^a-z0-9-]+/', '-', $str);
        
        // Xóa các dấu gạch ngang liên tiếp
        $str = preg_replace('/-+/', '-', $str);

        // Xóa dấu gạch ngang ở đầu và cuối chuỗi
        $str = trim($str, '-');

        return $str;
    }
}