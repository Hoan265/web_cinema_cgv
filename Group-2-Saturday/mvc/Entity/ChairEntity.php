<?php
class ChairEntity {
    public $id;
    public $TenGhe;
    public $HinhAnh;
    public $GiaGhe;

    public function __construct($id, $TenGhe, $HinhAnh, $GiaGhe) {
        $this->id = $id;
        $this->TenGhe = $TenGhe;
        $this->HinhAnh = $HinhAnh;
        $this->GiaGhe = $GiaGhe;
    }
    public function getID() {
        return $this->id;
    }

    public function setID($id) {
        $this->id = $id;
    }
    public function getTenGhe() {
        return $this->TenGhe;
    }

    public function setTenGhe($TenGhe) {
        $this->TenGhe = $TenGhe;
    }
    public function getHinhAnh() {
        return $this->HinhAnh;
    }

    public function setHinhAnh($HinhAnh) {
        $this->HinhAnh = $HinhAnh;
    }
    public function getGiaGhe() {
        return $this->GiaGhe;
    }
    public function setGiaGhe($GiaGhe) {
        $this->GiaGhe = $GiaGhe;
    }
}
   

?>