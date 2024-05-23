<?php
// require_once __DIR__ . '/../Core/DbConnection.php';
require_once __DIR__ . '/../Entity/CinemaRoomEntity.php';

class CinemaRoomModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }

    public function getAllCinemaRooms() {
        $query = "SELECT * FROM cinemaroom";
        $result = $this->conn->getConnection()->query($query);

        $cinemaRooms = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cinemaRoom = new CinemaRoomEntity(
                    $row['id'],
                    $row['TenPhong'],
                    $row['LoaiPhong'],
                    $row['TinhTrang'],
                    $row['DSGhe']
                );
                array_push($cinemaRooms, $cinemaRoom);
            }
        }
        return $cinemaRooms;
    }

    public function addCinemaRoom($roomsData) {
        $query = "INSERT INTO cinemaroom (id, TenPhong, LoaiPhong, TinhTrang, DSGhe) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("issss", 
        $roomsData['id'], 
        $roomsData['TenPhong'], 
        $roomsData['LoaiPhong'], 
        $roomsData['TinhTrang'], 
        $roomsData['DSGhe']);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateCinemaRoom($id, $roomsData) {
        $query = "UPDATE cinemaroom SET TenPhong=?, LoaiPhong=?, TinhTrang=?, DSGhe=? WHERE id=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("ssssi", 
        $roomsData['TenPhong'], 
            $roomsData['LoaiPhong'], 
            $roomsData['TinhTrang'], 
            $roomsData['DSGhe'], 
            $id
    );
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCinemaRoom($id) {
        $query = "DELETE FROM cinemaroom WHERE id=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    /////
    public function getCinemaRoomByID($id) {
        $query = "SELECT * FROM cinemaroom WHERE id = ?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cinemaRoom = new CinemaRoomEntity(
                $row['id'],
                $row['TenPhong'],
                $row['LoaiPhong'],
                $row['TinhTrang'],
                $row['DSGhe']
            );
            return $cinemaRoom;
        } else {
            return null;
        }
    }
    
}

?>