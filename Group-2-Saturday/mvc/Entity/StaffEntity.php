<?php 
class StaffEntity {
    public $idStaff;
    public $idRole;
    public $idFunction;
    public $Status;

    // Constructor
    public function __construct($idStaff, $idRole, $idFunction, $Status) {
        $this->idStaff = $idStaff;
        $this->idRole = $idRole;
        $this->idFunction = $idFunction;
        $this->Status = $Status;
    }

    // Getter and Setter for idStaff
    public function getIdStaff() {
        return $this->idStaff;
    }

    public function setIdStaff($idStaff) {
        $this->idStaff = $idStaff;
    }

    // Getter and Setter for idRole
    public function getIdRole() {
        return $this->idRole;
    }

    public function setIdRole($idRole) {
        $this->idRole = $idRole;
    }

    // Getter and Setter for idFunction
    public function getIdFunction() {
        return $this->idFunction;
    }

    public function setIdFunction($idFunction) {
        $this->idFunction = $idFunction;
    }

    // Getter and Setter for Status
    public function getStatus() {
        return $this->Status;
    }

    public function setStatus($Status) {
        $this->Status = $Status;
    }
}
?>
