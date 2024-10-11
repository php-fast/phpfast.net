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
<<<<<<< HEAD
            redirect(base_url('admin/auth/login'));
=======
            redirect(base_url('account/login'));
>>>>>>> d56d56bc250df9011e4c0789f16dacc6aedb2327
        }
        // Gọi middleware tiếp theo
        return $next($request);
    }
}