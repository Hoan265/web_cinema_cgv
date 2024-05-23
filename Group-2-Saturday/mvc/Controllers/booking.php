<?php
class Booking extends Controller {
     public $FilmModel;
     public $FoodsModel;
    private $LichChieuModel;

     public function __construct()
     {  
         $this->FilmModel= $this->model("FilmModel"); 
         $this->FoodsModel= $this->model("FoodsModel");
        $this->LichChieuModel = $this->model("LichChieuModel");
         parent::__construct();
         require_once "./mvc/Core/Pagination.php";
     }
 
     function default(){
         $this->view("main_layout", [
             "Page" => "booking" ,
             "Title" => "Trang chủ",
             "Script" => "booking",
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
         $data = $this->FoodsModel->getAll();
         echo json_encode($data);
        //  echo $data;
     }
     public function getQuery($filter, $input, $args) {
        $query = $this->FoodsModel->getQuery($filter, $input, $args);
        return $query;
    }
    public function getGiaVe(){
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $idPhim = $_POST['idPhim'];
            $ThoiGian = $_POST['ThoiGian'];
            $Room =$_POST['Room'];
            $result = $this->LichChieuModel->getGiaVe($idPhim, $ThoiGian, $Room);
            if ($result) {
                // Lấy giá trị của ID lớn nhất
                $GiaVe = $result['GiaVe'];
                echo $GiaVe;
            } else {
                // Xử lý nếu không có kết quả trả về
                echo "Không có dữ liệu";
            }
            // echo json_encode($result);
        }
    }

}
?>