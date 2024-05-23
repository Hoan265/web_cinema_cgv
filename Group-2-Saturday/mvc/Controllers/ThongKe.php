<?php
class ThongKe extends Controller {
     public function __construct() {

          parent::__construct();
      }
      public function default()
      {
              $this->view("main_layout",[
                  "Page" => "ThongKe",
                  "Title" => "Thống kê",
                  "PageRole" => "admin",                            
              ]);
      }
}

?>