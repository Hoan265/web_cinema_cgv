<?php
class Details_movie extends Controller {
     public $FilmModel;
     public function __construct()
     {  
        $this->FilmModel= $this->model("FilmModel"); 
         parent::__construct();
         require_once "./mvc/Core/Pagination.php";
     }
 
     function default(){
         $this->view("main_layout", [
             "Page" => "details_movie",
             "Title" => "Trang chủ",
             "Script" => "details_movie",
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
            // $result = $this->FilmModel->getById($_POST['idDetail']);
            $result = $this->FilmModel->getFilmById($_POST['idDetail']);

            // $result = $this->FilmModel->getById($_SESSION['data_id']);
            echo json_encode($result);
        }
    }
     public function getQuery($filter, $input, $args) {
        $query = $this->FilmModel->getQuery($filter, $input, $args);
        return $query;
    }
    public function getId(){
        if(isset($_SESSION['data_id'])) {
            echo $_SESSION['data_id'];
            // echo $this->FilmModel->getById($_SESSION['data_id']);
        } else {
            echo "";
        }
    }
    public function getById(){
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->FilmModel->getById($_POST['idDetail']);
            echo $result;
        }
    }
}
?>