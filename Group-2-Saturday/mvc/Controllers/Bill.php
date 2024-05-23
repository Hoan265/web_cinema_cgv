<?php
require_once __DIR__ . '/../Models/BillModel.php';

class Bill extends Controller
{
    private $billModel;
    private $detailBillModel;
    public function __construct()
    {
        $this->detailBillModel = $this->model("DetailBillModel");
        $this->billModel = new BillModel();
        parent::__construct();
        require_once "./mvc/Core/Pagination.php";

    }
    function default()
    {
        $this->view("main_layout", [
            "Page" => "bill",
            "Title" => "Hoá đơn",
            "Script" => "",
            "Plugin" => [],
            "PageRole" => "client",
        ]);
    }
    // Hiển thị tất cả các hóa đơn
    public function getAllBills()
    {
        return $this->billModel->getBills();
    }

    // Thêm một hóa đơn mới
    public function addBill($billData)
    {
        return $this->billModel->addBill($billData);
    }

    // Cập nhật thông tin của một hóa đơn
    public function updateBill($IDBill, $billData)
    {
        return $this->billModel->updateBill($IDBill, $billData);
    }

    // Xóa một hóa đơn
    public function deleteBill($IDBill)
    {
        return $this->billModel->deleteBill($IDBill);
    }
    ///////////////////////
    public function getBillsTicket()
    {
        return $this->billModel->getBillsTicket();
    }
    public function checkIDFoodExists($IDFood)
    {
        return $this->billModel->checkIDFoodExists($IDFood);
    }
    public function checkAllStatusCompleted($IDFood)
    {
        return $this->billModel->checkAllStatusCompleted($IDFood);
    }
    public function getBillsByStatus($status)
    {
        return $this->billModel->getBillsByStatus($status);
    }
    public function updateStatus($IDBill, $newStatus)
    {
        return $this->billModel->updateStatus($IDBill, $newStatus);
    }
    public function add()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idcustommer = $_POST['idcustommer'];
            $idstaff = 1;
            $idprom = $_POST['idprom'];
            $totalPrice = $_POST['totalPrice'];
            $trangthai = 1;
            //detail bill
            $idmovie = $_POST['idmovie'];
            $idseat = $_POST['idseat'];
            $idroom = $_POST['idroom'];
            $idshowtime = $_POST['idshowtime'];
            $listidfood = $_POST['listidfood'];
            $customseats = $_POST['customseats'];
            $idBill = $this->getIDMax();
            $idDetailBill = $this->getIDMaxDetailBill();
            if ($listidfood == null) {
                $result1 = $this->detailBillModel->create($idDetailBill, $idBill, $idmovie, $idseat, $idroom, $idshowtime, '', $customseats);
                $result = $this->billModel->create($idBill, $idcustommer, $idstaff, $idprom, $totalPrice, $trangthai);
            } else {
                foreach ($listidfood as $idfood) {
                    $result1 = $this->detailBillModel->create($this->getIDMaxDetailBill(), $idBill, $idmovie, $idseat, $idroom, $idshowtime, $idfood, $customseats);
                }
                $result = $this->billModel->create($idBill, $idcustommer, $idstaff, $idprom, $totalPrice, $trangthai);
            }
            echo $result;
        }
    }
    public function getIDMax()
    {
        // if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $result = $this->billModel->getIDMax();
        if ($result) {
            // Lấy giá trị của ID lớn nhất
            $maxID = $result['MaxID'];
            return $maxID + 1;
        } else {
            // Xử lý nếu không có kết quả trả về
            return null;
        }
        // }
    }
    public function getIDMaxDetailBill()
    {
        // if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $result = $this->detailBillModel->getIDMax();
        if ($result) {
            // Lấy giá trị của ID lớn nhất
            $maxID = $result['MaxID'];
            return $maxID + 1;
        } else {
            // Xử lý nếu không có kết quả trả về
            return "Không có dữ liệu";
        }
        // }
    }
    public function getQuery($filter, $input, $args) {
        $query = $this->billModel->getQuery($filter, $input, $args);
        return $query;
    }
public function getPaginatedBills($limit, $offset) {
    return $this->billModel->getPaginatedBills($limit, $offset);

}
public function countAllBills() {
    return $this->billModel->countAllBills();
}
public function getPaginatedBillsByStatus($status, $limit, $offset) {
    return $this->billModel->getPaginatedBillsByStatus($status, $limit, $offset);
}
public function countBillsByStatus($status) {
    return $this->billModel->countBillsByStatus($status);
    }
public function searchBills($keyword) {
    return $this->billModel->searchBills($keyword);
        }
        }
