<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class CreateNewPassword extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "CreateNewPassword" ,
                "Title" => "Tạo mật khẩu mới",
                "PageRole" => "createnewpassword",
            ]);
        }          
    }
   
?>