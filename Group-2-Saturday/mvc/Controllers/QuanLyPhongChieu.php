<?php
require_once __DIR__ . '/../Models/CinemaRoomModel.php';

class QuanLyPhongChieu extends Controller {
    private $cinemaRoomModel;

    public function __construct() {
        $this->cinemaRoomModel = new CinemaRoomModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout",[
                "Page" => "QuanLyPhongChieu",
                "Title" => "Quản lý phòng và ghế",
                "PageRole" => "admin",           
            ]);
    }    

    public function getAllCinemaRooms() {
        return $this->cinemaRoomModel->getAllCinemaRooms();
    }

    public function addCinemaRoom($roomsData) {
        return $this->cinemaRoomModel->addCinemaRoom($roomsData);
    }

    public function updateCinemaRoom($id, $roomsData) {
        return $this->cinemaRoomModel->updateCinemaRoom($id, $roomsData);
    }

    public function deleteCinemaRoom($id) {
        return $this->cinemaRoomModel->deleteCinemaRoom($id);
    }
    /////
    public function getCinemaRoomByID($id) {
        return $this->cinemaRoomModel->getCinemaRoomByID($id);
}
}

?>