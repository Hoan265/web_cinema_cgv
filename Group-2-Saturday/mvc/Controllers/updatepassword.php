<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class updatepassword extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "updatepassword" ,
                "Title" => "Đăng Nhập",
                "PageRole" => "client",
            ]);
        }          
    }
   
?>