<?php
require_once __DIR__ . '/../Models/LichChieuModel.php';
class QuanLyLichChieu extends Controller
{
    private $lichChieuModel;

    public function __construct()
    {
        $this->lichChieuModel = new LichChieuModel();
        parent::__construct();
    }
    public function default()
    {
        $this->view("main_layout", [
            "Page" => "QuanLyLichChieu",
            "Title" => "Quản lý lịch chiếu",
            "PageRole" => "admin",
        ]);
    }

    public function getAllScreenings()
    {
        return $this->lichChieuModel->getAllScreenings();
    }

    // Phương thức để thêm một lịch chiếu mới
    public function addScreening($scheduleData)
    {
        return $this->lichChieuModel->addScreening($scheduleData);
    }

    // Phương thức để cập nhật thông tin của một lịch chiếu
    public function updateScreening($scheduleId, $scheduleData)
    {
        return $this->lichChieuModel->updateScreening($scheduleId, $scheduleData);
    }


    public function deleteScreening($id)
    {
        return $this->lichChieuModel->deleteScreening($id);
    }
    public function loadone_showtime_by_id_movie($id)
    {
        return $this->lichChieuModel->loadone_showtime_by_id_movie($id);
    }
    public function getScreeningByID($id)
    {
        return $this->lichChieuModel->getScreeningByID($id);
    }
    public function getIDShowTime()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $idPhim = $_POST['idPhim'];
            $idPhong = $_POST['Room'];
            $time = $_POST['ThoiGian'];
            $result = $this->lichChieuModel->getIDShowTime($idPhim, $idPhong, $time);
            echo $result['id'];
        }
        
        
    }
    
}
