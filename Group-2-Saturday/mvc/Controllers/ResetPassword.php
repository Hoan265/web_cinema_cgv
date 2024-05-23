<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class resetpassword extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "resetpassword" ,
                "Title" => "Đăng Nhập",
                "PageRole" => "resetpassword",
            ]);
        }          
    }
   
?>