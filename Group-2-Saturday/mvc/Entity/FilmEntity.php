<?php
class FilmEntity {
    public $id;
    public $TenPhim;
    public $DV;
    public $TheLoai;
    public $ThoiLuong;
    public $NgayKC;
    public $NgayKT;
    public $NoiSX;
    public $DaoDien;
    public $NamSX;
    public $NoiDung;
    public $HinhAnh;
    public $DoTuoi;
    public $url_film;

    public function __construct($id, $TenPhim, $DV, $TheLoai, $ThoiLuong, $NgayKC, $NgayKT, $NoiSX, $DaoDien, $NamSX, $NoiDung, $HinhAnh, $DoTuoi, $url_film) {
        $this->id = $id;
        $this->TenPhim = $TenPhim;
        $this->DV = $DV;
        $this->TheLoai = $TheLoai;
        $this->ThoiLuong = $ThoiLuong;
        $this->NgayKC = $NgayKC;
        $this->NgayKT = $NgayKT;
        $this->NoiSX = $NoiSX;
        $this->DaoDien = $DaoDien;
        $this->NamSX = $NamSX;
        $this->NoiDung = $NoiDung;
        $this->HinhAnh = $HinhAnh;
        $this->DoTuoi = $DoTuoi;
        $this->url_film = $url_film;
    }
    // Getter và setter cho id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter và setter cho TenPhim
    public function getTenPhim() {
        return $this->TenPhim;
    }

    public function setTenPhim($TenPhim) {
        $this->TenPhim = $TenPhim;
    }

    // Getter và setter cho DV
    public function getDV() {
        return $this->DV;
    }

    public function setDV($DV) {
        $this->DV = $DV;
    }

    // Getter và setter cho TheLoai
    public function getTheLoai() {
        return $this->TheLoai;
    }

    public function setTheLoai($TheLoai) {
        $this->TheLoai = $TheLoai;
    }

    // Getter và setter cho ThoiLuong
    public function getThoiLuong() {
        return $this->ThoiLuong;
    }

    public function setThoiLuong($ThoiLuong) {
        $this->ThoiLuong = $ThoiLuong;
    }

    // Getter và setter cho NgayKC
    public function getNgayKC() {
        return $this->NgayKC;
    }

    public function setNgayKC($NgayKC) {
        $this->NgayKC = $NgayKC;
    }

    // Getter và setter cho NgayKT
    public function getNgayKT() {
        return $this->NgayKT;
    }

    public function setNgayKT($NgayKT) {
        $this->NgayKT = $NgayKT;
    }

    // Getter và setter cho NoiSX
    public function getNoiSX() {
        return $this->NoiSX;
    }

    public function setNoiSX($NoiSX) {
        $this->NoiSX = $NoiSX;
    }

    // Getter và setter cho DaoDien
    public function getDaoDien() {
        return $this->DaoDien;
    }

    public function setDaoDien($DaoDien) {
        $this->DaoDien = $DaoDien;
    }

    // Getter và setter cho NamSX
    public function getNamSX() {
        return $this->NamSX;
    }

    public function setNamSX($NamSX) {
        $this->NamSX = $NamSX;
    }

    // Getter và setter cho NoiDung
    public function getNoiDung() {
        return $this->NoiDung;
    }

    public function setNoiDung($NoiDung) {
        $this->NoiDung = $NoiDung;
    }

    // Getter và setter cho HinhAnh
    public function getHinhAnh() {
        return $this->HinhAnh;
    }

    public function setHinhAnh($HinhAnh) {
        $this->HinhAnh = $HinhAnh;
    }

    // Getter và setter cho DoTuoi
    public function getDoTuoi() {
        return $this->DoTuoi;
    }

    public function setDoTuoi($DoTuoi) {
        $this->DoTuoi = $DoTuoi;
    }
    public function getUrl() {
        return $this->url_film;
    }

    public function setUrl($url_film) {
        $this->url_film = $url_film;
    }
}

?>
