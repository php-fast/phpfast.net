<?php
// Kiểm tra nếu không có ROOT_PATH, ngăn chặn truy cập
if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * Hàm xss_clean
 * Lọc các đầu vào để tránh XSS (Cross-Site Scripting)
 * 
 * @param string $data Dữ liệu cần lọc
 * @return string Dữ liệu đã được làm sạch
 */
function xss_clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Hàm clean_input
 * Làm sạch dữ liệu đầu vào để tránh các lỗ hổng bảo mật như XSS
 * 
 * @param mixed $data Dữ liệu cần làm sạch (chuỗi hoặc mảng)
 * @return mixed Dữ liệu đã được làm sạch
 */
function clean_input($data) {
    if (is_array($data)) {
        // Nếu $data là một mảng, áp dụng clean_input cho từng phần tử
        foreach ($data as $key => $value) {
            $data[$key] = clean_input($value);
        }
        return $data;
    } else {
        // Nếu $data là một chuỗi, làm sạch như bình thường
        // Loại bỏ khoảng trắng ở đầu và cuối
        $data = trim($data);
        // Xóa các ký tự backslashes \
        $data = stripslashes($data);
        // Loại bỏ các ký tự không mong muốn như ', "
        $data = str_replace(["'", '"'], '', $data);
        // Chuyển đổi các ký tự đặc biệt thành các thực thể HTML
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        // Loại bỏ các ký tự không phải chữ cái, số, dấu cách và dấu câu cơ bản
        $data = preg_replace('/[^\w\s\p{P}]/u', '', $data);
        return $data;
    }
}

/**
 * Hàm lấy dữ liệu từ $_GET một cách an toàn
 * 
 * @param string $key Tên của tham số cần lấy
 * @param mixed $default Giá trị mặc định nếu tham số không tồn tại
 * @return mixed Dữ liệu đã được làm sạch hoặc giá trị mặc định
 */
function S_GET($key, $default = null) {
    if (isset($_GET[$key])) {
        return clean_input($_GET[$key]);
    }
    return $default;
}

/**
 * Hàm check tồn tại dữ liệu từ $_GET hay không
 * 
 * @param string $key Tên của tham số cần lấy
 * @return boolean True hoac False
 */
function HAS_GET($key) {
    if (isset($_GET[$key])) {
        return true;
    }
    return false;
}

/**
 * Hàm lấy dữ liệu từ $_POST một cách an toàn
 * 
 * @param string $key Tên của tham số cần lấy
 * @param mixed $default Giá trị mặc định nếu tham số không tồn tại
 * @return mixed Dữ liệu đã được làm sạch hoặc giá trị mặc định
 */
function S_POST($key, $default = null) {
    if (isset($_POST[$key])) {
        return clean_input($_POST[$key]);
    }
    return $default;
}

/**
 * Hàm check tồn tại dữ liệu từ $_POST hay không
 * 
 * @param string $key Tên của tham số cần lấy
 * @return boolean True hoac False
 */
function HAS_POST($key) {
    if (isset($_POST[$key])) {
        return true;
    }
    return false;
}

/**
 * Hàm lấy dữ liệu từ $_REQUEST một cách an toàn
 * 
 * @param string $key Tên của tham số cần lấy
 * @param mixed $default Giá trị mặc định nếu tham số không tồn tại
 * @return mixed Dữ liệu đã được làm sạch hoặc giá trị mặc định
 */
function S_REQUEST($key, $default = null) {
    if (isset($_REQUEST[$key])) {
        return clean_input($_REQUEST[$key]);
    }
    return $default;
}

/**
 * Hàm check tồn tại dữ liệu từ $_REQUEST hay không
 * 
 * @param string $key Tên của tham số cần lấy
 * @return boolean True hoac False
 */
function HAS_REQUEST($key) {
    if (isset($_REQUEST[$key])) {
        return true;
    }
    return false;
}

/**
 * Hàm uri_security
 * Làm sạch và bảo vệ URI tránh các cuộc tấn công XSS, SQL Injection
 * 
 * @param string $uri Dữ liệu URI cần làm sạch
 * @return string URI đã được làm sạch
 */
function uri_security($uri) {
    // Loại bỏ các ký tự không hợp lệ từ URI
    $uri = filter_var($uri, FILTER_SANITIZE_URL);

    // Áp dụng thêm các bước làm sạch XSS
    return xss_clean($uri);
}

