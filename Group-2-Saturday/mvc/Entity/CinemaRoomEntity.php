<?php
class CinemaRoomEntity {
    public $id;
    public $TenPhong;
    public $LoaiPhong;
    public $TinhTrang;
    public $DSGhe;

    public function __construct($id, $TenPhong, $LoaiPhong, $TinhTrang, $DSGhe) {
        $this->id = $id;
        $this->TenPhong = $TenPhong;
        $this->LoaiPhong = $LoaiPhong;
        $this->TinhTrang = $TinhTrang;
        $this->DSGhe = $DSGhe;
    }
    ////
    public function getID() {
        return $this->id;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function getTenPhong() {
        return $this->TenPhong;
    }

    public function setTenPhong($TenPhong) {
        $this->TenPhong = $TenPhong;
    }
    public function getLoaiPhong() {
        return $this->LoaiPhong;
    }

    public function setLoaiPhong($LoaiPhong) {
        $this->LoaiPhong = $LoaiPhong;
    }

    public function getTinhTrang() {
        return $this->TinhTrang;
    }

    public function setTinhTrang($TinhTrang) {
        $this->TinhTrang = $TinhTrang;
    }
    public function getDSGhe() {
        return $this->DSGhe;
    }

    public function setDSGhe($DSGhe) {
        $this->DSGhe = $DSGhe;
    }
}

    



?>