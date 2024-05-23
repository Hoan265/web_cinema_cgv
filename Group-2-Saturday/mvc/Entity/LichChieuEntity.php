<?php
class LichChieuEntity {
    public $id;
    public $Phim;
    public $ThoiGianChieu;
    public $PhongChieu;
    public $GiaVe;

    public function __construct($id, $Phim, $ThoiGianChieu, $PhongChieu, $GiaVe) {
        $this->id = $id;
        $this->Phim = $Phim;
        $this->ThoiGianChieu = $ThoiGianChieu;
        $this->PhongChieu = $PhongChieu;
        $this->GiaVe = $GiaVe;
    }

   
    public function getID() {
        return $this->id;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function getPhim() {
        return $this->Phim;
    }

    public function setPhim($Phim) {
        $this->Phim = $Phim;
    }

    public function getThoiGianChieu() {
        return $this->ThoiGianChieu;
    }

    public function setThoiGianChieu($ThoiGianChieu) {
        $this->ThoiGianChieu = $ThoiGianChieu;
    }

  
    public function getPhongChieu() {
        return $this->PhongChieu;
    }

    public function setPhongChieu($PhongChieu) {
        $this->PhongChieu = $PhongChieu;
    }


    public function getGiaVe() {
        return $this->GiaVe;
    }

    public function setGiaVe($GiaVe) {
        $this->GiaVe = $GiaVe;
    }
}

?>