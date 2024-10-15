<?php
namespace App\Libraries;

class Fastlang
{
    // Mảng lưu trữ tất cả các bản dịch đã nạp
    protected static $translations = [];

    // Mảng lưu trữ các file đã được nạp
    protected static $load_list = [];

    /**
     * Load file ngôn ngữ nếu chưa được nạp
     */
    public static function load($file, $lang = 'en')
    {
        $file_key = "{$file}_{$lang}";

        // Kiểm tra nếu file ngôn ngữ đã được nạp
        if (!isset(self::$load_list[$file_key])) {
            $file_lang = ROOT_PATH . "/application/Languages/{$lang}/" . ucfirst($file) . ".php";
            
            // Nếu file tồn tại, nạp vào $translations
            if (file_exists($file_lang)) {
                $translations = require $file_lang;

                // Gộp các bản dịch mới vào mảng translations chính
                self::$translations = array_merge(self::$translations, $translations);

                // Đánh dấu file này đã được nạp
                self::$load_list[$file_key] = true;
            }
        }
    }

    /**
     * Lấy chuỗi dịch, trả về chính key nếu không tìm thấy
     */
    public static function _e($key, ...$args)
    {
        $translation = self::$translations[$key] ?? ucfirst($key);
        // Chỉ gọi replacePlaceholders nếu có $args
        if (!empty($args)) {
            $translation = self::replacePlaceholders($translation, $args);
        }
        return $translation;
    }

    /**
     * In ra chuỗi dịch, in ra chính key nếu không tìm thấy
     */
    public static function _($key, ...$args)
    {
        $translation = self::$translations[$key] ?? ucfirst($key);
        // Chỉ gọi replacePlaceholders nếu có $args
        if (!empty($args)) {
            $translation = self::replacePlaceholders($translation, $args);
        }
        echo $translation;
        unset($translation);
    }

    /**
     * Thay thế các placeholder trong chuỗi bằng giá trị truyền vào
     */
    protected static function replacePlaceholders($string, $args)
    {
        foreach ($args as $index => $value) {
            $string = str_replace('%' . ($index + 1) . '%', $value, $string);
        }
        return $string;
    }

    /**
     * Reset lại danh sách bản dịch và danh sách load
     */
    public static function reset()
    {
        self::$translations = [];
        self::$load_list = [];
    }
}
