<?php 

class FoodsEntity {
    
    public $id;
    public $name;
    public $status;
    public $idCatalog;
    public $img;

    // Constructor
    public function __construct($id, $name, $status, $idCatalog, $img) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->idCatalog = $idCatalog;
        $this->img = $img;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getIdCatalog() {
        return $this->idCatalog;
    }

    public function getImg() {
        return $this->img;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setIdCatalog($idCatalog) {
        $this->idCatalog = $idCatalog;
    }

    public function setImg($img) {
        $this->img = $img;
    }
}
?> 