<?php
class membership extends Controller {
     public function __construct()
     {  
         parent::__construct();
         require_once "./mvc/Core/Pagination.php";
     }
 
     function default(){
         $this->view("main_layout", [
             "Page" => "membership",
             "Title" => "Trang chủ",
             "CSS" => "custom-cgv",
             "PageRole" => "client",
         ]);

     }
}
?>