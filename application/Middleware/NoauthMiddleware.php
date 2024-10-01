<?php
namespace App\Middleware;

class NoauthMiddleware {

    /**
     * Xử lý middleware
     * 
     * @param mixed $request Thông tin request
     * @param callable $next Middleware tiếp theo
     * @return mixed
     */
    public function handle($request, $next) {
        // Giả sử sử dụng session để kiểm tra người dùng đã đăng nhập
        if (\System\Libraries\Session::has('user_id')) {
            // Nếu đã đăng nhập, chuyển hướng đến trang admin
            redirect(base_url('admin/dashboard'));
        }
        // Gọi middleware tiếp theo
        return $next($request);
    }
}