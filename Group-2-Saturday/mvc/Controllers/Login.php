<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class Login extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "Login" ,
                "Title" => "Đăng Nhập",
                "PageRole" => "login",
            ]);
        }          
    }
   
?>