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

// trans table name posttype $tableName = 'fast_posts_'.$data['slug'].'_'.$lang;
if(!function_exists('table_posttype')) {
    function table_posttype($slug, $lang) {
        $tableName = 'fast_posts_'.$slug.'_'.$lang;
        return  $tableName;
    }
}

if(!function_exists('convers_array')) {
    function convers_array($data) {
        if (is_string($data)) {
            // Nếu $data là chuỗi JSON, chuyển đổi thành mảng
            $data = json_decode($data, true);
        } elseif (is_object($data)) {
            // Nếu $data là đối tượng, chuyển thành mảng
            $data = (array)$data;
        } elseif (!is_array($data)) {
            // Nếu $data không phải là mảng, đối tượng hoặc chuỗi JSON, chuyển thành mảng rỗng
            $data = [];
        }
        return $data;
    }
}