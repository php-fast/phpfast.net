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
        return rtrim($base_url, '/') . '/auth/' . trim($path, '/').'/';
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