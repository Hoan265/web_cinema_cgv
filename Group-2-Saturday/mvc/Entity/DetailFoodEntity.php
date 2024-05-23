<?php 
  class DetailFoodEntity {
    
    public $id;
    public $price;
    public $status;
    public $idFood;
    public $idSize;

    // Constructor
    public function __construct($id, $price, $status, $idFood, $idSize) {
        $this->id = $id;
        $this->price = $price;
        $this->status = $status;
        $this->idFood = $idFood;
        $this->idSize = $idSize;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getIdFood() {
        return $this->idFood;
    }

    public function getIdSize() {
        return $this->idSize;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setIdFood($idFood) {
        $this->idFood = $idFood;
    }

    public function setIdSize($idSize) {
        $this->idSize = $idSize;
    }
}

?>