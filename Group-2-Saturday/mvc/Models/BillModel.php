<?php
 require_once __DIR__ . '/../Core/DbConnection.php';
require_once __DIR__ . '/../Entity/BillEntity.php';

class BillModel extends DbConnection{
    private $conn;
    private $user;
    public function __construct() {
        $this->conn = new DbConnection();
    }
    
    public function create($id, $idcustommer,$idstaff, $idprom, $totalPrice,$status)
    {
        if($idprom== ''){
            $idprom=0;
        }
        $sql = "INSERT INTO `Bill`(`IDBill`, `IDCustomer`, `IDStaff`, `IDPromoton`, `TotalPrice`, `Status`) 
        VALUES ( '$id','$idcustommer','$idstaff','$idprom','$totalPrice','Đã xử lý')";
        $check = true;
        $result = mysqli_query($this->conn->getConnection(), $sql);
        if (!$result) {
            $check = false;
        }
        return $check;
    }
    public function getIDMax(){
        $sql = "SELECT MAX(IDBill) as MaxID FROM `Bill`";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        return mysqli_fetch_assoc($result);
    }
    public function findIdByEmail() {
        if (isset($_SESSION['email_client'])) {
            $email = $_SESSION['email_client'];
            $stmt = $this->conn->getConnection()->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['id'];
            } else {
                return null;
            }
        } else {
            throw new Exception("Email not set in session");
        }
    }
    public function getQuery($filter, $input, $args)
    {
        
        $query = "SELECT DISTINCT `b`.`IDBill`,`f`.`TenPhim`,`r`.`TenPhong`,`db`.`CustomSeats`,`lc`.`ThoiGianChieu`,`lc`.`GiaVe`,`b`.`TotalPrice`,`b`.`Status` FROM `Bill` as `b` INNER JOIN `DetailBill` as `db` ON `b`.`IDBill` = `db`.`IDBill`  INNER JOIN `film` as `f` ON `f`.`id` = `db`.`IDMovie` INNER JOIN `lichchieu` as `lc` ON `lc`.`id` = `db`.`IDShowTime` INNER JOIN `cinemaroom` as `r` ON `r`.`id` = `db`.`IDRoom` WHERE `b`.`IDCustomer` = '". $this->findIdByEmail() . "'";        
        if ($input) {
            $query = $query . " AND (TenPhim LIKE N'%${input}%')";
        }
        $query = $query . " ORDER BY `b`.`IDBill` ASC";
        return $query;
    }
    // Phương thức để lấy thông tin của tất cả các hóa đơn
    public function getBills() {
        $bills = array();
        $query = "SELECT * FROM bill";
    
        $result = $this->conn->getConnection()->query($query);
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Kiểm tra xem biến $row['IDPromotion'] có tồn tại không
                $IDPromotion = isset($row['IDPromotion']) ? $row['IDPromotion'] : "";
    
                // Lấy giá trị của cột Status từ kết quả truy vấn
                $status = isset($row['Status']) ? $row['Status'] : "";
    
                $bill = new BillEntity(
                    $row['IDBill'],
                    $row['IDCustomer'],
                    $row['IDStaff'],
                    $IDPromotion, // Sử dụng biến $IDPromotion đã kiểm tra
                    $row['TotalPrice'],
                    $status, // Sử dụng giá trị của cột Status
                );
                array_push($bills, $bill);
            }
        } else {
            echo "Không có dữ liệu hóa đơn";
        }
    
        return $bills;
    }
    

    // Phương thức để thêm một hóa đơn mới
public function addBill($billData) {
    $query = "INSERT INTO bill (IDCustomer, IDStaff, IDPromotion,TotalPrice, Status) VALUES (?, ?, ?, ?,?)";
    
    // Thêm biến Status vào danh sách các trường cần chèn
    $stmt = $this->conn->getConnection()->prepare($query);
    $stmt->bind_param("iiiiis", $billData['IDCustomer'], $billData['IDStaff'], $billData['IDPromotion'], $billData['TotalPrice'], $billData['Status']);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


    // Phương thức để cập nhật thông tin của một hóa đơn
    public function updateBill($IDBill, $billData) {
        $query = "UPDATE bill SET IDCustomer=?, IDStaff=?, IDPromotion=?, TotalPrice=? WHERE IDBill=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("iiiiii", $billData['IDCustomer'], $billData['IDStaff'], $billData['IDPromotion'],$billData['TotalPrice'], $IDBill);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Phương thức để xóa một hóa đơn
    public function deleteBill($IDBill) {
        $query = "DELETE FROM bill WHERE IDBill=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $IDBill);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    //////////////////////////////////
    public function getBillsTicket() {
        $bills = array();
        $query = "SELECT * FROM bill";
    
        $result = $this->conn->getConnection()->query($query);
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $status = isset($row['Status']) ? $row['Status'] : "";
                $bill = new BillEntity(
                    $row['IDBill'],
                    $row['IDCustomer'],
                    $row['IDStaff'],
                    $row['IDPromoton'],
                    $row['TotalPrice'],
                    $status // Sử dụng giá trị của cột Status
                );
                array_push($bills, $bill);
            }
        } else {
            echo "Không có dữ liệu hóa đơn";
        }
    
        return $bills;
    }
    public function checkIDFoodExists($IDFood) {
        $query = "SELECT IDFood FROM bill WHERE IDFood = ?";
       $stmt = $this->conn->getConnection()->prepare($query);
       $stmt->bind_param("i", $IDFood);
       
       if ($stmt->execute()) {
           $result = $stmt->get_result();
           
           if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               return $row['IDFood'];
           } else {
               return false;
           }
       } else {
           // Lỗi khi thực hiện truy vấn
           return false;
       }
       }
       public function checkAllStatusCompleted($IDFood) {
        $query = "SELECT Status FROM bill WHERE IDFood = ?";
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $IDFood);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $status = $row['Status'];
                if ($status != 'Đã xác nhận') {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    public function getBillsByStatus($status) {
        $bills = array();
        $query = "SELECT * FROM bill WHERE Status = ?";
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $bill = new BillEntity(
                $row['IDBill'],
                $row['IDCustomer'],
                $row['IDStaff'],
                $row['IDPromoton'],
                $row['TotalPrice'],
                $row['Status']
            );
            array_push($bills, $bill);
        }
        return $bills;
    }
public function updateStatus($IDBill, $newStatus) {
    $query = "UPDATE bill SET Status=? WHERE IDBill=?";
    $stmt = $this->conn->getConnection()->prepare($query);
    $stmt->bind_param("si", $newStatus, $IDBill);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
public function getPaginatedBills($limit, $offset) {
    $query = "SELECT * FROM Bill LIMIT $limit OFFSET $offset";
    $result = $this->conn->getConnection()->query($query);
    $bills = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bill = new BillEntity(
                $row['IDBill'],
                $row['IDCustomer'],
                $row['IDStaff'],
                $row['IDPromoton'],
                $row['TotalPrice'],
                $row['Status']
            );
            array_push($bills, $bill);
        }
    }
    return $bills;
}

public function countAllBills() {
    $query = "SELECT COUNT(*) AS total FROM Bill";
    $result = $this->conn->getConnection()->query($query);
    $total = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    return $total;
}
public function getPaginatedBillsByStatus($status, $limit, $offset) {
    $query = "SELECT * FROM Bill WHERE Status = ? LIMIT ? OFFSET ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    $stmt->bind_param("sii", $status, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $bills = array();
    while ($row = $result->fetch_assoc()) {
        $bill = new BillEntity(
            $row['IDBill'],
            $row['IDCustomer'],
            $row['IDStaff'],
            $row['IDPromoton'],
            $row['TotalPrice'],
            $row['Status']
        );
        array_push($bills, $bill);
    }
    return $bills;
}

public function countBillsByStatus($status) {
    $query = "SELECT COUNT(*) AS total FROM Bill WHERE Status = ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    return $total;
}
public function searchBills($keyword) {
    $searchTerm = $this->conn->getConnection()->real_escape_string($keyword);
    $query = "SELECT * FROM bill WHERE IDBill LIKE '%$searchTerm%' OR IDCustomer LIKE '%$searchTerm%' OR IDStaff LIKE '%$searchTerm%' OR IDPromoton LIKE '%$searchTerm%' OR TotalPrice LIKE '%$searchTerm%' OR Status LIKE '%$searchTerm%'";
    $result = $this->conn->getConnection()->query($query);
    $searchResults = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bill = new BillEntity(
                $row['IDBill'],
                $row['IDCustomer'],
                $row['IDStaff'],
                $row['IDPromoton'],
                $row['TotalPrice'],
                $row['Status']
            );
            array_push($searchResults, $bill);
        }
    }
    return $searchResults;
}
}
?>
