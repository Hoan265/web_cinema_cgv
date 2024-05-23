<?php
class Home extends Controller {
     public $FilmModel;
     public function __construct()
     {  
        $this->FilmModel= $this->model("FilmModel"); 
         parent::__construct();
         require_once "./mvc/Core/Pagination.php";
     }
 
     function default(){
         $this->view("main_layout", [
             "Page" => "home" ,
             "Title" => "Trang chủ",
             "Script" => "home",
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
         $data = $this->FilmModel->getAll();
         echo json_encode($data);
     }
     public function getDetail()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->FilmModel->getById($_POST['id']);
            echo json_encode($result);
        }
    }
     public function getQuery($filter, $input, $args) {
        $query = $this->FilmModel->getQuery($filter, $input, $args);
        return $query;
    }
    public function saveId(){
        if(isset($_POST['id'])) {
            $_SESSION['data_id'] = $_POST['id'];
            echo $_SESSION['data_id'];
        }
    }
}
?>