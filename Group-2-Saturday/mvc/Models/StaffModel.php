<?php 
// require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/StaffEntity.php';

class StaffModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }

    public function getAllStaff() {
        $query = "SELECT * FROM Staff";
        $result = $this->conn->getConnection()->query($query);

        $staffs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                  $staff = new StaffEntity(
                    $row['IdStaff'],
                    $row['IDRole'],
                    $row['IdFunction'],
                    $row['Status']
                  );
                  array_push($staffs, $staff);
               
            }
        }
        return $staffs;
}
public function findbyId($id){
        $query = "SELECT * FROM Staff WHERE IdStaff = '$id'";
        $result = $this->conn->getConnection()->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $staff = new StaffEntity(
                $row['IdStaff'],
                    $row['IDRole'],
                    $row['IdFunction'],
                    $row['Status']
            );
            return $staff;
        } else {
            // Trả về null nếu không tìm thấy nhân viên có id tương ứng
            return null;
        }
    }
    public function addStaff($id, $idRole, $idFunction, $status){
        $query = "INSERT INTO Staff (IdStaff, IDRole, IdFunction, Status) 
                  VALUES ('$id', '$idRole', '$idFunction', '$status')";
    
        // Thực hiện truy vấn
        $result = $this->conn->getConnection()->query($query);
    
        // Kiểm tra và trả về kết quả
        if ($result) {
            // Thêm thành công
            return true;
        } else {
            // Thêm thất bại
            return false;
        }
    }
    public function getNextStaffID() {
        // Lấy ID cuối cùng từ cơ sở dữ liệu
        $query = "SELECT MAX(RIGHT(IdStaff, 4)) AS lastID FROM Staff WHERE LEFT(IdStaff, 2) = 'NV'";
        $result = $this->conn->getConnection()->query($query);
    
        // Kiểm tra và xử lý ID tiếp theo
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = (int)$row['lastID']; // Chuyển đổi thành số nguyên
            $nextID = $lastID + 1;
        } else {
            // Nếu không có dữ liệu, bắt đầu từ 1
            $nextID = 1;
        }
    
        // Định dạng lại ID theo chuỗi NVxxxx
        $formattedID = 'NV' . str_pad($nextID, 4, '0', STR_PAD_LEFT);
    
        return $formattedID;
    }
    public function updateStaff($id, $idRole, $status){
        // Tạo câu truy vấn cập nhật thông tin
        $query = "UPDATE Staff SET IDRole = '$idRole', Status = '$status' WHERE IdStaff = '$id'";
        
        // Thực hiện truy vấn
        $result = $this->conn->getConnection()->query($query);
        
        // Kiểm tra và trả về kết quả
        if ($result) {
            // Cập nhật thành công
            return true;
        } else {
            // Cập nhật thất bại
            return false;
        }
    }
    
    public function deleteStaffById($id) {
        $query = "DELETE FROM Staff WHERE IdStaff = '$id'";
        $result = $this->conn->getConnection()->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
public function findRoleByIdStaff($idStaff) {
    // Tạo câu truy vấn SQL để lấy IDRole từ bảng Staff dựa trên IDStaff được cung cấp
    $query = "SELECT IDRole FROM Staff WHERE IdStaff = ?";
    
    // Chuẩn bị câu truy vấn
    $stmt = $this->conn->getConnection()->prepare($query);
    
    if ($stmt) {
        // Bind tham số và thực hiện truy vấn
        $stmt->bind_param("s", $idStaff);
        $stmt->execute();
        
        // Lấy kết quả
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Nếu có kết quả, trả về IDRole
            $row = $result->fetch_assoc();
            return $row['IDRole'];
        } else {
            // Nếu không có kết quả, trả về null
            return null;
        }
    } else {
        // Nếu có lỗi trong quá trình chuẩn bị truy vấn, in ra thông báo lỗi và kết thúc chương trình
        echo "Error: " . $this->conn->getConnection()->error;
        die();
    }
}
public function getPaginatedStaff($limit, $offset) {
    $query = "SELECT * FROM Staff LIMIT ? OFFSET ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $staffList = array();
    while ($row = $result->fetch_assoc()) {
        $staff = new StaffEntity(
            $row['IdStaff'],
            $row['IDRole'],
            $row['IdFunction'],
            $row['Status']
        );
        array_push($staffList, $staff);
    }
    return $staffList;
}

public function countAllStaff() {
    $query = "SELECT COUNT(*) AS total FROM Staff";
    $result = $this->conn->getConnection()->query($query);
    $total = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    return $total;
}
public function searchStaff($keyword) {
    $searchTerm = $this->conn->getConnection()->real_escape_string($keyword);
    $query = "SELECT * FROM Staff WHERE IdStaff LIKE '%$searchTerm%' OR IDRole LIKE '%$searchTerm%'";
    $result = $this->conn->getConnection()->query($query);
    $searchResults = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $staff = new StaffEntity(
                $row['IdStaff'],
                $row['IDRole'],
                $row['IdFunction'],
                $row['Status']
            );
            array_push($searchResults, $staff);
        }
    }
    return $searchResults;
}

       
}

?>
