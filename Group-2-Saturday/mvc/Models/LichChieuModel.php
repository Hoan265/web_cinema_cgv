<?php
// require_once __DIR__ . '/../../mvc/Core/DbConnection.php';
require_once __DIR__ . '/../../mvc/Entity/LichChieuEntity.php';

class LichChieuModel extends DbConnection
{
    private $conn;

    public function __construct()
    {
        $this->conn = new DbConnection();
    }

    public function getAllScreenings()
    {
        $query = "SELECT * FROM lichchieu";
        $result = $this->conn->getConnection()->query($query);

        $screenings = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $screening = new LichChieuEntity(
                    $row['id'],
                    $row['Phim'],
                    $row['ThoiGianChieu'],
                    $row['PhongChieu'],
                    $row['GiaVe']
                );
                array_push($screenings, $screening);
            }
        }
        return $screenings;
    }
    public function addScreening($screeningData)
    {
        $query = "INSERT INTO lichchieu (id, Phim, ThoiGianChieu, PhongChieu, GiaVe) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param(
            "isssi",
            $screeningData['id'],
            $screeningData['Phim'],
            $screeningData['ThoiGianChieu'],
            $screeningData['PhongChieu'],
            $screeningData['GiaVe']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateScreening($scheduleId, $scheduleData)
    {
        $query = "UPDATE lichchieu SET Phim=?, ThoiGianChieu=?, PhongChieu=?, GiaVe=? WHERE id=?";

        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param(
            "sssii",
            $scheduleData['Phim'],
            $scheduleData['ThoiGianChieu'],
            $scheduleData['PhongChieu'],
            $scheduleData['GiaVe'],
            $scheduleId
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteScreening($id)
    {
        $query = "DELETE FROM lichchieu WHERE id=?";

        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function loadone_showtime_by_id_movie($id)
    {
        $query = "SELECT * FROM lichchieu where Phim =" . $id . " AND DATE(ThoiGianChieu) >= CURRENT_DATE ;";
        $result = $this->conn->getConnection()->query($query);

        $screenings = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $screening = new LichChieuEntity(
                    $row['id'],
                    $row['Phim'],
                    $row['ThoiGianChieu'],
                    $row['PhongChieu'],
                    $row['GiaVe']
                );
                array_push($screenings, $screening);
            }
        }
        return $screenings;
    }
    public function getIDShowTime($idPhim, $idPhong, $time)
    {
        $query = "SELECT id FROM lichchieu where Phim =" . $idPhim . " AND PhongChieu = " . $idPhong . " AND TIMESTAMP(ThoiGianChieu) = TIMESTAMP('" . $time . "');";
        $result = $this->conn->getConnection()->query($query);
        if ($result->num_rows > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
    public function getGiaVe($idPhim, $ThoiGian, $Room)
    {

        $query = "SELECT DISTINCT GiaVe FROM lichchieu where Phim =" . $idPhim ." AND ThoiGianChieu LIKE '%" .$ThoiGian . "%' AND PhongChieu = " . $Room . ";";
        $result = $this->conn->getConnection()->query($query);
        if ($result->num_rows > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
    ///
    public function getScreeningByID($id)
    {
        $query = "SELECT * FROM lichchieu WHERE id = ?";

        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $screening = new LichChieuEntity(
                $row['id'],
                $row['Phim'],
                $row['ThoiGianChieu'],
                $row['PhongChieu'],
                $row['GiaVe']
            );
            return $screening;
        } else {
            return null;
        }
    }
}
