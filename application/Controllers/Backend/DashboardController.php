<?php
namespace App\Controllers\Backend;
use System\Core\BaseController;
use System\Libraries\Session;

class DashboardController extends BaseController {

    public function index() {
        echo 'Hello from DashboardController!';
        Session::start();
        print_r($_SESSION);
    }
}
