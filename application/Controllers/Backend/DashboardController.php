<?php
//# Trang tổng quan cho admin. Phần này ko quan trọng kiểu như trang chủ của admin thôi

namespace App\Controllers\Backend;
use System\Core\BaseController;
use System\Libraries\Session;

class DashboardController extends BaseController {

    public function index() {
        Session::start();
        print_r($_SESSION);
    }

    public function index2($item = '', $item2 = ''){
        echo $item.'.'.$item2;
    }
}
