<?php
class myticket extends Controller {
     public $BillModel;
     public function __construct()
     {  
         $this->BillModel= $this->model("BillModel"); 
         parent::__construct();
         require_once "./mvc/Core/Pagination.php";
     }
 
     function default(){
         $this->view("main_layout", [
             "Page" => "myticket" ,
             "Title" => "Vé của tôi",
             "Script" => "myticket",
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
    //  public function getData()
    //  {
    //      $data = $this->BillModel->getAll();
    //      echo json_encode($data);
    //     //  echo $data;
    //  }
     public function getQuery($filter, $input, $args) {
        $query = $this->BillModel->getQuery($filter, $input, $args);
        return $query;
    }
}
?>