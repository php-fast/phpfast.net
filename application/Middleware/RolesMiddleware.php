<?php
namespace App\Middleware;

use System\Libraries\Session;

class RolesMiddleware {

    /**
     * Xử lý middleware
     * 
     * @param mixed $request Thông tin request
     * @param callable $next Middleware tiếp theo
     * @return mixed
     */
    public function handle($request, $next) {
        // Lấy tên controller và action từ request (giả sử chúng được lưu trong request)
        $controller = $request['controller'] ?? '';
        $action = $request['action'] ?? '';
        // Lấy quyền từ session
        $permissions = Session::get('permissions');

        if ($this->checkPermission($permissions, $controller, $action)) {
            // Cho phép tiếp tục nếu có quyền
            return $next($request);
        }
        // Nếu không có quyền, hiển thị thông báo lỗi
        throw new \System\Core\AppException('You not have permission access this page!', 403, null, 403);
    }

    /**
     * Kiểm tra xem người dùng có quyền truy cập vào controller và action không
     * 
     * @param array $permissions Mảng quyền của người dùng
     * @param string $controller Tên controller
     * @param string $action Tên action
     * @return bool
     */
    protected function checkPermission($permissions, $controller, $action) {
        // Kiểm tra nếu quyền tồn tại cho controller và action
        foreach ($permissions as $account_controller => $account_actions){
            $account_controller = '\\'.$account_controller.'Controller';
            if (strpos($controller, $account_controller) !== FALSE){
                if (in_array($action, $account_actions)){
                    return true;
                }
            }
        }
        return false;
    }
}
