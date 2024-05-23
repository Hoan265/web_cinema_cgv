<?php 
require_once __DIR__ . '/../Core/DbConnection.php';
require_once __DIR__ . '/../Entity/RoleStaffEntity.php';

class RoleStaffModel extends  Controller{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
        parent::__construct();
    }

    public function getAllRoleStaff() {
        $query = "SELECT * FROM RoleStaff";
        $result = $this->conn->getConnection()->query($query);

        $roles= array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $role = new RoleStaffEntity(
                    $row['Id'],
                    $row['Ten']
                  );
                  array_push($roles, $role);
            }
        }
        return  $roles;
}
   public function findIdRole($id){
    $query = "SELECT Ten FROM RoleStaff WHERE Id = '$id'";
        $result = $this->conn->getConnection()->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Ten'];
        } else {
            return null;
        }

   }
   public function getNextRoleID($roleName) {
    // Xây dựng câu truy vấn SQL để lấy ID dựa trên tên vai trò
    $query = "SELECT Id FROM RoleStaff WHERE Ten = '$roleName'";

    // Thực hiện truy vấn
    $result = $this->conn->getConnection()->query($query);

    // Kiểm tra kết quả truy vấn
    if ($result && $result->num_rows > 0) {
        // Lấy dòng dữ liệu đầu tiên từ kết quả truy vấn
        $row = $result->fetch_assoc();
        $roleCode = $row['Id'];

        // Xây dựng câu truy vấn để lấy ID lớn nhất cho loại vai trò
        $queryMaxID = "SELECT MAX(RIGHT(IdStaff, 4)) AS lastID FROM Staffs WHERE LEFT(IdStaff, 2) = '$roleCode'";
        
        // Thực hiện truy vấn
        $resultMaxID = $this->conn->getConnection()->query($queryMaxID);

        // Kiểm tra kết quả truy vấn
        if ($resultMaxID && $resultMaxID->num_rows > 0) {
            // Lấy dòng dữ liệu đầu tiên từ kết quả truy vấn
            $rowMaxID = $resultMaxID->fetch_assoc();

            // Lấy ID lớn nhất hiện có và tăng giá trị lên 1
            $lastID = $rowMaxID['lastID'];
            $nextID = ($lastID === null) ? 1 : $lastID + 1;

            return $roleCode . str_pad($nextID, 4, '0', STR_PAD_LEFT);
        }
    }

    // Nếu không có dữ liệu trả về, trả về null
    return null;
}
public function findIdByName($ten) {
    $query = "SELECT Id FROM RoleStaff WHERE Ten = '$ten'";
    $result = $this->conn->getConnection()->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Id'];
    } else {
        return null;
    }
}



}
?>