<?php 
class RoleStaffEntity {
    public $id;
    public $ten;

    // Constructor
    public function __construct($id, $ten) {
        $this->id = $id;
        $this->ten = $ten;
    }

    // Getter and Setter for id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for ten
    public function getTen() {
        return $this->ten;
    }

    public function setTen($ten) {
        $this->ten = $ten;
    }
}
?>
