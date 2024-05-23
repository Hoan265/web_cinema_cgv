<?php
class voucher extends Controller {
     public $PromotionModel;
     public function __construct()
     {  
         $this->PromotionModel= $this->model("PromotionModel"); 
         parent::__construct();
         require_once "./mvc/Core/Pagination.php";
     }
 
     function default(){
         $this->view("main_layout", [
             "Page" => "voucher" ,
             "Title" => "Khuyến mãi",
             "Script" => "voucher",
             "Plugin" => [
                "sweetalert2" => 1,
                "datepicker" => 1,
                "flatpickr" => 1,
                "notify" => 1,
                "jquery-validate" => 1,
                "select" => 1,
                "pagination" => [],
            ], 
             "PageRole" => "client",
         ]);

        }
     public function getData()
     {
         $data = $this->PromotionModel->getAll();
         echo json_encode($data);
        //  echo $data;
     }
     public function getQuery($filter, $input, $args) {
        $query = $this->PromotionModel->getQuery($filter, $input, $args);
        return $query;
    }
}
?>