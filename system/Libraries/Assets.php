<?php
namespace System\Libraries;

use MatthiasMullie\Minify;

class Assets {
    protected $path_views;
    protected $path_public;

    protected $css = ['head' => [], 'footer' => []];
    protected $js = ['head' => [], 'footer' => []];
    protected $inlineCss = ['head' => [], 'footer' => []];
    protected $inlineJs = ['head' => [], 'footer' => []];

    public function __construct() {
        $themeName = config('theme');
        $themeName = $themeName['theme_name'] ?? 'default';
        $this->path_views = ROOT_PATH . '/application/Views/'.$themeName.'/';
        $this->path_public = ROOT_PATH . '/public/assets/';
    }

    /**
     * Thêm CSS, JS vào mảng head hoặc footer để chuẩn bị render ra folder public assets
     */
    public function add($type = 'css', $file = '', $location = 'head') {
        if ($location !== 'head' && $location !== 'footer') {
            $location = 'head';
        }
        if ($type == 'css') {
            $this->css[$location][] = $file;
        } else {
            $this->js[$location][] = $file;
        }
    }

    /**
     * Thêm inline CSS hoặc JS vào head hoặc footer
     */
    public function inline($type = 'css', $content = '', $location = 'head') {
        if ($location !== 'head' && $location !== 'footer') {
            $location = 'head';
        }
        if ($type == 'css') {
            $this->inlineCss[$location][] = $content;
        } else {
            $this->inlineJs[$location][] = $content;
        }
    }

    /**
     * Render assets ra thư mục public và trả về HTML cho head
     */
    public function header($type = 'frontend') {
        $output = '';

        // Combine và render CSS cho head
        if (!empty($this->css['head'])) {
            $url = $this->render($type, $this->css['head'], 'css');
            $output .= '<link rel="stylesheet" href="' . $url . '">' . PHP_EOL;
        }

        // Combine và render JS cho head
        if (!empty($this->js['head'])) {
            $url = $this->render($type, $this->js['head'], 'js');
            $output .= '<script src="' . $url . '"></script>' . PHP_EOL;
        }

        // Inline CSS cho head
        if (!empty($this->inlineCss['head'])) {
            $output .= '<style>' . implode("\n", $this->inlineCss['head']) . '</style>' . PHP_EOL;
        }

        // Inline JS cho head
        if (!empty($this->inlineJs['head'])) {
            $output .= '<script>' . implode("\n", $this->inlineJs['head']) . '</script>' . PHP_EOL;
        }

        return $output;
    }

    /**
     * Render assets ra thư mục public và trả về HTML cho footer
     */
    public function footer($type = 'frontend') {
        $output = '';

        // Combine và render CSS cho footer
        if (!empty($this->css['footer'])) {
            $url = $this->render($type, $this->css['footer'], 'css');
            $output .= '<link rel="stylesheet" href="' . $url . '">' . PHP_EOL;
        }

        // Combine và render JS cho footer
        if (!empty($this->js['footer'])) {
            $url = $this->render($type, $this->js['footer'], 'js');
            $output .= '<script src="' . $url . '"></script>' . PHP_EOL;
        }

        // Inline CSS cho footer
        if (!empty($this->inlineCss['footer'])) {
            $output .= '<style>' . implode("\n", $this->inlineCss['footer']) . '</style>' . PHP_EOL;
        }

        // Inline JS cho footer
        if (!empty($this->inlineJs['footer'])) {
            $output .= '<script>' . implode("\n", $this->inlineJs['footer']) . '</script>' . PHP_EOL;
        }

        return $output;
    }

    /**
     * Render assets ra thư mục public
     */
    public function render($type, $files = [], $assetType = 'css') {
        $hashedName = md5(implode('-', $files)) . ".$assetType";
        $publicFilePath = "{$this->path_public}{$type}/{$assetType}/{$hashedName}";

        // Nếu file đã tồn tại, trả về đường dẫn
        if (file_exists($publicFilePath)) {
            return "/assets/{$type}/{$assetType}/{$hashedName}";
        }

        // Gộp và minify nội dung
        $combinedContent = $this->combineFiles($type, $files, $assetType);
        $this->saveFile($publicFilePath, $combinedContent);

        return "/assets/{$type}/{$assetType}/{$hashedName}";
    }

    /**
     * Gộp các file thành 1 file duy nhất, mỗi file xuống dòng mới
     */
    protected function combineFiles($type, $files, $assetType) {
        $combinedContent = '';
        foreach ($files as $file) {
            $filePath = "{$this->path_views}{$type}/assets/{$file}";
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $combinedContent .= '//'.$file."\n". $content . "\n";
            }
        }
        return $this->minify($combinedContent, $assetType);
    }

    /**
     * Minify nội dung sử dụng thư viện `matthiasmullie/minify`
     */
    protected function minify($content, $assetType) {
        if ($assetType === 'css') {
            $minifier = new Minify\CSS();
            $minifier->add($content);
            return $minifier->minify();
        } elseif ($assetType === 'js') {
            $minifier = new Minify\JS();
            $minifier->add($content);
            return $minifier->minify();
        }
        return $content;
    }

    /**
     * Lưu file đã được minify vào thư mục public
     */
    protected function saveFile($filePath, $content) {
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // Kiểm tra đường dẫn trước khi lưu để tránh ghi đè lên file hệ thống
        if (strpos(realpath($dir), realpath($this->path_public)) !== 0) {
            throw new \System\Core\AppException('Invalid path for asset file saving.');
        }
        file_put_contents($filePath, $content);
    }
}
