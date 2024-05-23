<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class OTP_ResetPassword extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "OTP_ResetPassword" ,
                "Title" => "Đăng Ký",
                "PageRole" => "otpreset",
            ]);
        }          
    }
   
?>