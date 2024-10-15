<?php
namespace App\Middleware;

class AuthMiddleware {

    /** 
     * Xử lý middleware
     * 
     * @param mixed $request Thông tin request
     * @param callable $next Middleware tiếp theo
     * @return mixed
     */
    public function handle($request, $next) {
        
        // Giả sử sử dụng session để kiểm tra người dùng đã đăng nhập
        if (!\System\Libraries\Session::has('user_id')) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            redirect(base_url('account/login'));
        }
        // Gọi middleware tiếp theo
        return $next($request);
    }
}