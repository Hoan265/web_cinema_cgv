<?php
class DetailBillEntity {
    public $IDDetailBill;
    public $IDBill;
    public $IDMovie;
    public $IDSeat;
    public $IDRoom;
    public $IDShowTime;
    public $IDFood;
    public $CustomSeats;
    public $IDSize;

    // Constructor
    public function __construct($IDDetailBill, $IDBill, $IDMovie, $IDSeat, $IDRoom, $IDShowTime, $IDFood, $CustomSeats, $IDSize) {
        $this->IDDetailBill = $IDDetailBill;
        $this->IDBill = $IDBill;
        $this->IDMovie = $IDMovie;
        $this->IDSeat = $IDSeat;
        $this->IDRoom = $IDRoom;
        $this->IDShowTime = $IDShowTime;
        $this->IDFood = $IDFood;
        $this->CustomSeats = $CustomSeats;
        $this->IDSize = $IDSize;
    }

    // Getter và setter cho IDDetailBill
    public function getIDDetailBill() {
        return $this->IDDetailBill;
    }

    public function setIDDetailBill($IDDetailBill) {
        $this->IDDetailBill = $IDDetailBill;
    }

    // Getter và setter cho IDBill
    public function getIDBill() {
        return $this->IDBill;
    }

    public function setIDBill($IDBill) {
        $this->IDBill = $IDBill;
    }

    // Getter và setter cho IDMovie
    public function getIDMovie() {
        return $this->IDMovie;
    }

    public function setIDMovie($IDMovie) {
        $this->IDMovie = $IDMovie;
    }

    // Getter và setter cho IDSeat
    public function getIDSeat() {
        return $this->IDSeat;
    }

    public function setIDSeat($IDSeat) {
        $this->IDSeat = $IDSeat;
    }

    // Getter và setter cho IDRoom
    public function getIDRoom() {
        return $this->IDRoom;
    }

    public function setIDRoom($IDRoom) {
        $this->IDRoom = $IDRoom;
    }

    // Getter và setter cho IDShowTime
    public function getIDShowTime() {
        return $this->IDShowTime;
    }

    public function setIDShowTime($IDShowTime) {
        $this->IDShowTime = $IDShowTime;
    }

    // Getter và setter cho IDFood
    public function getIDFood() {
        return $this->IDFood;
    }

    public function setIDFood($IDFood) {
        $this->IDFood = $IDFood;
    }

    // Getter và setter cho CustomSeats
    public function getCustomSeats() {
        return $this->CustomSeats;
    }

    public function setCustomSeats($CustomSeats) {
        $this->CustomSeats = $CustomSeats;
    }

    // Getter và setter cho IDSize
    public function getIDSize() {
        return $this->IDSize;
    }

    public function setIDSize($IDSize) {
        $this->IDSize = $IDSize;
    }
}
?>
