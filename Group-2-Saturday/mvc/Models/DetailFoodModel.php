<?php 
// require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/DetailFoodEntity.php';

class DetailFoodModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }
    public function getAllDetail() {
        $query = "SELECT * FROM DetailFood";
        $result = $this->conn->getConnection()->query($query);
        $details = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                  $detail = new DetailFoodEntity(
                    $row['Id'],
                    $row['Price'],
                    $row['Status'],
                    $row['IdFoods'],
                    $row['IdSize']
                  );
                  array_push( $details, $detail);
            }
        }
        return $details;
}
public function getDetailFoods($selectedFoodId) {
    $foods = array();
    $query = "SELECT * FROM DetailFood WHERE IdFoods = '$selectedFoodId'";
    $result = $this->conn->getConnection()->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
              $food = new DetailFoodEntity(
                $row['Id'],
                $row['Price'],
                $row['Status'],
                $row['IdFoods'],
                $row['IdSize']
              );
              array_push( $foods, $food);
        }
    }
    return $foods;
}
public function getDetailSizeFoods($id) {
    $sql = "SELECT * FROM DetailFood WHERE Id = '$id'";
    $result = $this->conn->getConnection()->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $detail = new DetailFoodEntity(
            $row['Id'],
            $row['Price'],
            $row['Status'],
            $row['IdFoods'],
            $row['IdSize']
        );
        return $detail;
    } else {
        return null;
    }
}


public function createIDetailFoods() {
    $sql = "SELECT MAX(CAST(SUBSTRING(Id, 3) AS UNSIGNED)) AS maxId FROM DetailFood";
    $result = $this->conn->getConnection()->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row["maxId"];
    } else {
        $lastId = 0;
    }
    
    // Tạo ID mới
    $newId = 'DT' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    return $newId;
}
public function updateSizeFoods($id, $price, $status) {
    $sql = "UPDATE DetailFood 
            SET Price = '$price', 
            Status = '$status' 
            WHERE Id = '$id'";

    // Thực thi câu truy vấn và kiểm tra kết quả
    if ($this->conn->getConnection()->query($sql) === TRUE) {
        echo "Cập nhật dữ liệu thành công";
    } else {
        echo "Lỗi khi cập nhật dữ liệu: " . $this->conn->getConnection()->error;
    }
}
public function addSize($id, $price, $status, $idF, $idS) {
    $sql = "INSERT INTO DetailFood (Id, Price, Status, IdFoods, IdSize) 
            VALUES ('$id', '$price', '$status', '$idF', '$idS')";
    
    // Thực thi câu truy vấn và kiểm tra kết quả
    if ($this->conn->getConnection()->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}
public function deleteDetailByFoodsId($idFoods) {
    $sql = "DELETE FROM DetailFood WHERE IdFoods = '$idFoods'";
    // Execute the query and check the result
    if ($this->conn->getConnection()->query($sql) === TRUE) {
        return true; // Deletion successful
    } else {
        return false; // Error occurredx
    }
}
public function updateStatusByFoodsId($idFoods, $status) {
    $sql = "UPDATE DetailFood 
            SET Status = '$status' 
            WHERE IdFoods = '$idFoods'";
    if ($this->conn->getConnection()->query($sql) === TRUE) {
        echo "Cập nhật trạng thái thành công cho các DetailFood có IdFoods = '$idFoods'";
    } else {
        echo "Lỗi khi cập nhật trạng thái: " . $this->conn->getConnection()->error;
    }
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