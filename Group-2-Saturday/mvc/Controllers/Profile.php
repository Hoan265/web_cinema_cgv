<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class Profile extends Controller{
    private $loginModel;

    public function __construct() {
        $this->loginModel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "Profile" ,
                "Title" => "Thông tin cá nhân",
                "PageRole" => "admin",
            ]);
        }          
    }
   
?>