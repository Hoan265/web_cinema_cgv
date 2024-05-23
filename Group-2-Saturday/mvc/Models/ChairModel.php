<?php
// Include database connection
// require_once __DIR__ . '/../Core/DbConnection.php';
require_once __DIR__ . '/../Entity/ChairEntity.php';

class ChairModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }

    public function getAllChairs() {
        $query = "SELECT * FROM chair";
        $result = $this->conn->getConnection()->query($query);

        $chairs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $chair = new ChairEntity(
                    $row['id'],
                    $row['TenGhe'],
                    $row['HinhAnh'],
                    $row['GiaGhe']
                );
                array_push($chairs, $chair);
            }
        }
        return $chairs;
    }

    public function addChair($TenGhe, $HinhAnh, $GiaGhe) {
        $query = "INSERT INTO chair (TenGhe, HinhAnh, GiaGhe) VALUES (?, ?, ?)";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("sss", $TenGhe, $HinhAnh, $GiaGhe);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateChair($id, $TenGhe, $HinhAnh, $GiaGhe) {
        $query = "UPDATE chair SET TenGhe=?, HinhAnh=?, GiaGhe=? WHERE id=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("sssi", $TenGhe, $HinhAnh, $GiaGhe, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteChair($id) {
        $query = "DELETE FROM chair WHERE id=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    /////////////////////////////////////////

    public function getChairByID($id) {
        $query = "SELECT * FROM chair WHERE id = ?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $chair = new ChairEntity(
                $row['id'],
                $row['TenGhe'],
                $row['HinhAnh'],
                $row['GiaGhe']
            );
            return $chair;
        } else {
            return null;
        }
    }
    
}


?>