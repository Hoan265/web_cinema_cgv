<?php
class Promotion extends Controller {
    public $PromotionModel;
    public $LichChieuModel;
    public function __construct()
    {
        $this->PromotionModel = $this->model("PromotionModel");
        $this->LichChieuModel = $this->model("LichChieuModel");

        parent::__construct();
        require_once "./mvc/Core/Pagination.php";
    }

    public function default()
    {
            $this->view("main_layout",[
                "Page" => "promotion",
                "Title" => "Quản lý khuyến mãi", 
                "Script" => "promotion",
                "Plugin" => [
                    "sweetalert2" => 1,
                    "datepicker" => 1,
                    "flatpickr" => 1,
                    "notify" => 1,
                    "jquery-validate" => 1,
                    "select" => 1,
                    "pagination" => [],
                ],   
                "PageRole" => "admin",        
            ]);
    }

    public function add()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $ten = $_POST['tenkhuyenmai'];
            $hinhanh = basename($_POST['file_anh']);
            $ngaybd = $_POST['ngaybd'];
            $ngaykt = $_POST['ngaykt'];
            $giatri = $_POST['giatri'];
            $trangthai = $_POST['status'];
            $result = $this->PromotionModel->create($id,$ten,$hinhanh,$ngaybd,$ngaykt,$giatri,$trangthai);
            echo $result;
        }
    }

    public function getIDMax(){
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->PromotionModel->getIDMax();
            if ($result) {
                // Lấy giá trị của ID lớn nhất
                $maxID = $result['MaxID'];
                echo $maxID;
            } else {
                // Xử lý nếu không có kết quả trả về
                echo "Không có dữ liệu";
            }
        }
    }
    public function getData()
    {
        $data = $this->PromotionModel->getAll();
        return $data;
        // return json_encode($data);

    }
    public function getDetail()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->PromotionModel->getById($_POST['id']);
            echo json_encode($result);
        }
    }
    public function checkPromotion() 
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $result = $this->PromotionModel->checkPromotion($id);
            echo json_encode($result);
        }
    }
    public function deleteData(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $result = $this->PromotionModel->delete($id);
        }
    }

    public function update(){
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $ten = $_POST['tenkhuyenmai'];
            $hinhanh = basename($_POST['file_anh']);
            $ngaybd = $_POST['ngaybd'];
            $ngaykt = $_POST['ngaykt'];
            $giatri = $_POST['giatri'];
            $trangthai = $_POST['status'];
            $result = $this->PromotionModel->update($id,$ten,$hinhanh,$ngaybd,$ngaykt,$giatri,$trangthai);
            echo $result;
        }
    }

    public function getQuery($filter, $input, $args) {
        $query = $this->PromotionModel->getQuery($filter, $input, $args);
        return $query;
    }
//////////////////
    public function findDiscountPercentById($id) {
        return $this->PromotionModel->findDiscountPercentById($id);
        }

}
