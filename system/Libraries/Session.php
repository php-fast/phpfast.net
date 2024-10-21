<?php
namespace System\Libraries;
// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Session {

    /**
     * Khởi tạo session nếu chưa bắt đầu
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set một giá trị vào session
     * 
     * @param string $key Tên của session
     * @param mixed $value Giá trị cần lưu
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Lấy một giá trị từ session
     * 
     * @param string $key Tên của session
     * @return mixed|null Giá trị của session, hoặc null nếu không tồn tại
     */
    public static function get($key) {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    /**
     * Xóa một session cụ thể
     * 
     * @param string $key Tên của session cần xóa
     */
    public static function del($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Hủy toàn bộ session
     */
    public static function destroy() {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Kiểm tra sự tồn tại của một session
     * 
     * @param string $key Tên session cần kiểm tra
     * @return bool True nếu session tồn tại, False nếu không
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function has_flash($key) {
        self::start();
        return isset($_SESSION['flash']) && isset($_SESSION['flash'][$key]);
    }

    /**
     * Tạo một thông báo tạm thời (flash data). Neu khong truyen value thi no se la get flash data
     * Dữ liệu này sẽ chỉ tồn tại trong request tiếp theo và bị xóa sau đó
     * 
     * @param string $key Tên của flash message
     * @param mixed $value Giá trị của flash message
     */
    public static function flash($key, $value = '') {
        self::start();
        if (!empty($value)){
            $_SESSION['flash'][$key] = ['data'=>$value, 'expires'=>time()+60];
        }else{
            if (isset($_SESSION['flash'][$key])) {
                $value = $_SESSION['flash'][$key];
                if ($value['expires'] > time()){
                    unset($_SESSION['flash'][$key]);
                    if (empty($_SESSION['flash'])){
                        unset($_SESSION['flash']);
                    }
                    return $value['data'];
                }
                unset($_SESSION['flash'][$key]);
                if (empty($_SESSION['flash'])){
                    unset($_SESSION['flash']);
                }
            }
            return null;
        }
    }

    /**
     * Tái tạo session ID để tránh session fixation
     * Nên gọi sau khi người dùng đăng nhập hoặc thay đổi quyền truy cập
     */
    public static function regenerate() {
        self::start();
        session_regenerate_id(true);
    }

    /**
     * Kiểm tra và giới hạn thời gian tồn tại của session
     * Hủy session nếu đã hết thời gian
     * 
     * @param int $maxLifetime Thời gian tối đa tính bằng giây
     */
    public static function checkSessionTimeout($maxLifetime = 1800) {
        self::start();
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxLifetime)) {
            // Hủy session nếu quá thời gian cho phép
            self::destroy();
        }
        $_SESSION['last_activity'] = time(); // Cập nhật thời gian hoạt động cuối cùng
    }

    /**
     * Tạo và lấy CSRF token
     * @return string Chuỗi dạng `csrf_id::csrf_token`
     */
    public static function csrf_token($expired = 1800) {
        self::start();
        self::csrf_clean();
        // Tạo csrf_id dựa trên URL hiện tại
        $csrfId = md5($_SERVER['REQUEST_URI'] . json_encode($_GET));
        if (!empty($_SESSION['csrf_tokens'][$csrfId]) && !empty($_SESSION['csrf_tokens'][$csrfId]['token']) && $_SESSION['csrf_tokens'][$csrfId]['expires'] >= time()){
            $_SESSION['csrf_tokens'][$csrfId]['expires'] = time() + $expired;
            return $csrfId . '::' . $_SESSION['csrf_tokens'][$csrfId]['token'];
        }else{
            $csrfToken = random_string(32); // Tạo token ngẫu nhiên
            // Lưu token vào session với thời gian hết hạn (30 minutes default expired)
            $_SESSION['csrf_tokens'][$csrfId] = [
                'token' => $csrfToken,
                'expires' => time() + $expired // 30 minutes default expired
            ];
        }
        
        // Trả về chuỗi `csrf_id::csrf_token`
        return $csrfId . '::' . $csrfToken;
    }

    /**
     * Xác thực CSRF token từ session và dữ liệu form
     * @param string $token Chuỗi dạng `csrf_id::csrf_token` từ form
     * @return bool True nếu token hợp lệ, False nếu không
     */
    public static function csrf_verify($token) {
        self::start();
        self::csrf_clean();
        // Kiểm tra token có hợp lệ hay không (token không được rỗng và phải chứa '::')
        if (empty($token) || strpos($token, '::') === false) {
            return false;
        }
        // Bóc tách csrf_id và csrf_token từ chuỗi truyền vào
        list($csrfId, $csrfToken) = explode('::', $token);

        // Kiểm tra xem csrf_id có tồn tại trong session hay không
        if (!isset($_SESSION['csrf_tokens'][$csrfId])) {
            return false;
        }
        // Lấy thông tin csrf_token từ session
        $storedTokenData = $_SESSION['csrf_tokens'][$csrfId];
        // Kiểm tra token có khớp và chưa hết hạn không
        if ($storedTokenData['token'] === $csrfToken && $storedTokenData['expires'] >= time()) {
            // Xóa token sau khi xác thực thành công để tránh dùng lại
            unset($_SESSION['csrf_tokens'][$csrfId]);
            if (empty($_SESSION['csrf_tokens'])){
                unset($_SESSION['csrf_tokens']);
            }
            return true;
        }
        return false;
    }
    
    /**
     * Xóa các CSRF tokens đã hết hạn trong session
     */
    public static function csrf_clean() {
        self::start();

        if (!isset($_SESSION['csrf_tokens'])) {
            return;
        }
        // Xóa các token hết hạn
        foreach ($_SESSION['csrf_tokens'] as $csrfId => $tokenData) {
            if ($tokenData['expires'] < time()) {
                unset($_SESSION['csrf_tokens'][$csrfId]);
            }
        }
        if (empty($_SESSION['csrf_tokens'])){
            unset($_SESSION['csrf_tokens']);
        }
    }
}