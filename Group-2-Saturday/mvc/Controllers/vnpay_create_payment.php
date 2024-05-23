<?php
class vnpay_create_payment extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
        require_once "./mvc/Core/Pagination.php";

    }
    function default()
    {
        $this->view("main_layout", [
            "Page" => "vnpay_create_payment",
            "Title" => "Thanh Toán",
            "Script" => "",
            "Plugin" => [],
            "PageRole" => "client",
        ]);
    }
    }
?>