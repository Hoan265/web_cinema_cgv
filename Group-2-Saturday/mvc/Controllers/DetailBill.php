<?php
// Include detail bill model
require_once __DIR__ . '/../Models/DetailBillModel.php';

class DetailBill extends Controller
{
    private $detailBillModel;

    public function __construct()
    {
        // Tạo một instance mới của DetailBillModel
        $this->detailBillModel = new DetailBillModel();
        parent::__construct();
    }

    // Phương thức để lấy tất cả các chi tiết hóa đơn
    public function getAllDetailBills()
    {
        return $this->detailBillModel->getAllDetailBills();
    }
    public function getDetailBill($IDRoom, $IDShowtime)
    {
    }
    // Phương thức để thêm một chi tiết hóa đơn mới
    public function addDetailBill($detailBillData)
    {
        return $this->detailBillModel->addDetailBill($detailBillData);
    }

    // Phương thức để cập nhật thông tin của một chi tiết hóa đơn
    public function updateDetailBill($detailBillId, $detailBillData)
    {
        return $this->detailBillModel->updateDetailBill($detailBillId, $detailBillData);
    }

    // Phương thức để xóa một chi tiết hóa đơn
    public function deleteDetailBill($detailBillId)
    {
        return $this->detailBillModel->deleteDetailBill($detailBillId);
    }
    ///////////////
    public function findByID($id)
    {
        return $this->detailBillModel->findByID($id);
    }
    public function checkIDFoodExists($IDFood)
    {
        return $this->detailBillModel->checkIDFoodExists($IDFood);
    }
    public function add()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {            
            $idbill = $_POST['tenkhuyenmai'];
            $idmovie = basename($_POST['file_anh']);
            $idseat = $_POST['ngaybd'];
            $idroom = $_POST['ngaykt'];
            $idshowtime = $_POST['giatri'];
            $idfood = $_POST['status'];
            $customseats = $_POST['status'];
            $result = $this->detailBillModel->create($this->getIDMax(),$idbill,$idmovie,$idseat,$idroom,$idshowtime,$idfood,$customseats);
            echo $result;
        }
    }
    public function getIDMax()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->detailBillModel->getIDMax();
            if ($result) {
                // Lấy giá trị của ID lớn nhất
                $maxID = $result['MaxID'];
                echo $maxID+1;
            } else {
                // Xử lý nếu không có kết quả trả về
                echo "Không có dữ liệu";
            }
        }
    }
    
    public function getRelatedBillIDs($foodId) {
        return $this->detailBillModel->getRelatedBillIDs($foodId);
}
    
    public function getAllByIDPhim($id) {
        return $this->detailBillModel->getAllByIDPhim($id);
    }
}
