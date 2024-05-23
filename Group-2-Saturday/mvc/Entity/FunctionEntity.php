<?php
class FunctionEntity {
    public $id;
    public $name;
    public $idRole;

    // Constructor
    public function __construct($id, $name, $idRole) {
        $this->id = $id;
        $this->name = $name;
        $this->idRole = $idRole;
    }

    // Getter and Setter for id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for name
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    // Getter and Setter for idRole
    public function getIdRole() {
        return $this->idRole;
    }

    public function setIdRole($idRole) {
        $this->idRole = $idRole;
    }
}
?>
