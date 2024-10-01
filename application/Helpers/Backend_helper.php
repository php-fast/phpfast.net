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