<?php 
require_once __DIR__ . '/../Models/BillModel.php';
class Success extends Controller{
    private $billModel;

    public function __construct() {
        $this->billModel = new BillModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "success" ,
                "Title" => "Thành công",
                "PageRole" => "client",
            ]);
    }
    }
   
?>