<?php
define('LANG_LIST', ['en','vi','id','jp','kr']);
if (!empty($_SESSION['fast_lang']) && in_array($_SESSION['fast_lang'], LANG_LIST)){
    define('LANG', $_SESSION['fast_lang']);
}elseif (!empty($_COOKIE['fast_lang']) && in_array($_COOKIE['fast_lang'], LANG_LIST)){
    define('LANG', $_COOKIE['fast_lang']);
}elseif (!empty($_REQUEST['lang']) && in_array($_REQUEST['lang'], LANG_LIST)){
    define('LANG', $_REQUEST['lang']);
}
if (!defined('LANG')){
    define('LANG', 'en');
}
define('DEBUG_TIME', true); //bat la true neu muon DEBUG
if (DEBUG_TIME){
    // Đo lường bắt đầu ngay từ khi framework bắt đầu khởi chạy
    define('START_TIME', microtime(true));
    define('START_MEMORY', memory_get_usage());
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
// Đường dẫn đến thư mục root của ứng dụng
define('ROOT_PATH', realpath(__DIR__ . '/../'));
define('WRITE_PATH', realpath(__DIR__ . '/../writeable/'));

// Load autoload từ Composer
require_once ROOT_PATH . '/vendor/autoload.php';
// Load file Bootstrap để khởi động hệ thống
require_once ROOT_PATH . '/system/Core/Bootstrap.php';

// Khởi động Bootstrap của framework
$application = new \System\Core\Bootstrap();
$application->run();

if (DEBUG_TIME){
    $performance = \System\Libraries\Monitor::endFramework();
    echo "Time Run Total: " . $performance['execution_time']*1000 . " ms!";
    echo "Total Mem: " . \System\Libraries\Monitor::formatMemorySize($performance['memory_used']);
}