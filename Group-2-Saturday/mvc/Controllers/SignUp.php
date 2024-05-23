<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class SignUp extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "SignUp" ,
                "Title" => "Đăng Ký",
                "PageRole" => "signup",
            ]);
        }          
    }
   
?>