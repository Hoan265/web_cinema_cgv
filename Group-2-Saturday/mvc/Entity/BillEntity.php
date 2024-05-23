<?php
class BillEntity {
    public $IDBill;
    public $IDCustomer;
    public $IDStaff;
    public $IDPromotion;
    public $TotalPrice;
    public $Status;

    public function __construct($IDBill, $IDCustomer, $IDStaff, $IDPromotion, $TotalPrice, $Status) {
        $this->IDBill = $IDBill;
        $this->IDCustomer = $IDCustomer;
        $this->IDStaff = $IDStaff;
        $this->IDPromotion = $IDPromotion;
        $this->TotalPrice = $TotalPrice;
        $this->Status = $Status; // Khởi tạo biến Status
    }
    public function getIDBill() {
        return $this->IDBill;
    }

    public function setIDBill($IDBill) {
        $this->IDBill = $IDBill;
    }
    public function getIDCustomer() {
        return $this->IDCustomer;
    }

    public function setIDCustomer($IDCustomer) {
        $this->IDCustomer = $IDCustomer;
    }

    public function getIDStaff() {
        return $this->IDStaff;
    }

    public function setIDStaff($IDStaff) {
        $this->IDStaff = $IDStaff;
    }
    public function getIDPromotion() {
        return $this->IDPromotion;
    }

    public function setIDPromotion($IDPromotion) {
        $this->IDPromotion = $IDPromotion;
    }
    public function getTotalPrice() {
        return $this->TotalPrice;
    }

    public function setTotalPrice($TotalPrice) {
        $this->TotalPrice = $TotalPrice;
    }
    public function getStatus() {
        return $this->Status;
    }

    public function setStatus($Status) {
        $this->Status = $Status;
    }
}
?>
