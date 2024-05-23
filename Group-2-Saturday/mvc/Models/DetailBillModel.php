<?php
// require_once __DIR__ . '/../Core/DbConnection.php';
require_once __DIR__ . '/../Entity/DetailBillEntity.php';

class DetailBillModel extends DbConnection {
    private $conn;

    public function __construct() {
        // Tạo kết nối cơ sở dữ liệu
        $this->conn = new DbConnection();
    }

    // Phương thức để lấy thông tin của tất cả các chi tiết hóa đơn
    public function getAllDetailBills() {
        $detailBills = array();
        $query = "SELECT * FROM detailbill";

        $result = $this->conn->getConnection()->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $detailBill = new DetailBillEntity(
                    $row['IDDetailBill'],
                    $row['IDBill'],
                    $row['IDMovie'],
                    $row['IDSeat'],
                    $row['IDRoom'],
                    $row['IDShowTime'],
                    $row['IDFood'],
                    $row['CustomSeats'],
                    $row['IdSize']
                );
                array_push($detailBills, $detailBill);
            }
        } else {
            echo "Không có dữ liệu chi tiết hóa đơn";
        }

        return $detailBills;
    }

    // Phương thức để thêm một chi tiết hóa đơn mới
    public function addDetailBill($detailBillData) {
        $query = "INSERT INTO detailbill (IDBill, IDMovie, IDSeat, IDRoom, IDShowTime) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("iiiii", $detailBillData['IDBill'], $detailBillData['IDMovie'], $detailBillData['IDSeat'], $detailBillData['IDRoom'], $detailBillData['IDShowTime']);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Phương thức để cập nhật thông tin của một chi tiết hóa đơn
    public function updateDetailBill($detailBillId, $detailBillData) {
        $query = "UPDATE detailbill SET IDBill=?, IDMovie=?, IDSeat=?, IDRoom=?, IDShowTime=? WHERE IDDetailBill=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("iiiiii", $detailBillData['IDBill'], $detailBillData['IDMovie'], $detailBillData['IDSeat'], $detailBillData['IDRoom'], $detailBillData['IDShowTime'], $detailBillId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Phương thức để xóa một chi tiết hóa đơn
    public function deleteDetailBill($detailBillId) {
        $query = "DELETE FROM detailbill WHERE IDDetailBill=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $detailBillId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    ////////////////////////
    public function findByID($id) {
        $query = "SELECT * FROM detailbill WHERE IDBill = ?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $detailBill = new DetailBillEntity(
                $row['IDDetailBill'],
                $row['IDBill'],
                $row['IDMovie'],
                $row['IDSeat'],
                $row['IDRoom'],
                $row['IDShowTime'],
                $row['IDFood'],
                $row['CustomSeats'],
                $row['IdSize']
            );
            return $detailBill;
        } else {
            // Trả về null nếu không tìm thấy chi tiết hóa đơn có IDBill tương ứng
            return null;
        }
    }
    public function checkIDFoodExists($IDFood) {
        $query = "SELECT IDFood FROM detailbill WHERE IDFood = ?";
       $stmt = $this->conn->getConnection()->prepare($query);
       $stmt->bind_param("i", $IDFood);
       
       if ($stmt->execute()) {
           $result = $stmt->get_result();
           
           if ($result->num_rows > 0) {
               // IDFood tồn tại trong bảng bill
               $row = $result->fetch_assoc();
               return $row['IDFood'];
           } else {
               // IDFood không tồn tại trong bảng bill
               return false;
           }
       } else {
           // Lỗi khi thực hiện truy vấn
           return false;
       }
       }
       public function create($id, $idbill,$idmovie, $idseat, $idroom,$idshowtime,$idfood,$customseats)
       {
           $sql = "INSERT INTO `DetailBill`(`IDDetailBill`, `IDBill`, `IDMovie`, `IDSeat`, `IDRoom`, `IDShowTime`, `IDFood`, `CustomSeats`) 
           VALUES ('$id', '$idbill', '$idmovie', '$idseat', '$idroom', '$idshowtime' ,'$idfood','$customseats')";
           $check = true;
           $result = mysqli_query($this->conn->getConnection(), $sql);
           if (!$result) {
               $check = false;
           }
           return $check;
       }
       public function getIDMax(){
           $sql = "SELECT MAX(IDDetailBill) as MaxID FROM `DetailBill`";
           $result = mysqli_query($this->conn->getConnection(), $sql);
           return mysqli_fetch_assoc($result);
       }
       function getAllByIDPhim($id){
        $query = "SELECT detailbill.*,lichchieu.ThoiGianChieu FROM detailbill JOIN lichchieu ON detailbill.IDShowtime = lichchieu.id WHERE IDMovie = '". $id."'";
        $detailBills = array();
        
        $result = $this->conn->getConnection()->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $detailBill = array(
                    'IDDetailBill' => $row['IDDetailBill'],
                    'IDBill' => $row['IDBill'],
                    'IDMovie' => $row['IDMovie'],
                    'IDSeat' => $row['IDSeat'],
                    'IDRoom' => $row['IDRoom'],
                    'IDShowTime' => $row['IDShowTime'],
                    'IDFood' => $row['IDFood'],
                    'CustomSeats' => $row['CustomSeats'],
                    'IdSize' => $row['IdSize'],
                    'ThoiGianChieu' => $row['ThoiGianChieu'] // Thêm trường này từ bảng lichchieu
                );
                array_push($detailBills, $detailBill);
            }
        }

        return $detailBills;


        
       }
       public function getRelatedBillIDs($foodId) {
        $query = "SELECT DISTINCT d.IDBill, b.Status 
                  FROM detailbill d 
                  INNER JOIN bill b ON d.IDBill = b.IDBill 
                  WHERE d.IDFood = ?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("s", $foodId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $relatedBills = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $relatedBills[] = $row;
            }
        }
        
        return $relatedBills;
    }
    
    
    
    
}
?>
