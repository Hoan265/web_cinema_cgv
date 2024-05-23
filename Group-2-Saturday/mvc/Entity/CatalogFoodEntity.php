<?php
class CatatalogFoodEntity {
    
    public $id;
    public $ten;

    // Constructor
    public function __construct($id, $ten) {
        $this->id = $id;
        $this->ten = $ten;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTen() {
        return $this->ten;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTen($ten) {
        $this->ten = $ten;
    }
}

?>