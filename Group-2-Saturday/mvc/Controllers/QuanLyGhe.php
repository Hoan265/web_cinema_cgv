<?php
require_once __DIR__ . '/../Models/ChairModel.php';

class QuanLyGhe extends Controller{
    private $chairModel;

    public function __construct() {
        $this->chairModel = new ChairModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout",[
                "Page" => "QuanLyGhe",
                "Title" => "Quản lý ghế",
                "PageRole" => "admin",        
            ]);
    }
    public function getAllChairs() {
        return $this->chairModel->getAllChairs();
    }

    public function addChair($TenGhe, $HinhAnh, $GiaGhe) {
        return $this->chairModel->addChair($TenGhe, $HinhAnh, $GiaGhe);
    }

    public function updateChair($id, $TenGhe, $HinhAnh, $GiaGhe) {
        return $this->chairModel->updateChair($id, $TenGhe, $HinhAnh, $GiaGhe);
    }

    public function deleteChair($id) {
        return $this->chairModel->deleteChair($id);
    }

    ///////
    public function getChairByID($id) {
        return $this->chairModel->getChairByID($id);
        }
}

?>