<?php 
require_once __DIR__ . '/../Models/StaffModel.php';

class StaffController extends  Controller{
    private $staffmodel;

    public function __construct() {
        $this->staffmodel = new StaffModel();
        parent::__construct();
    }
   public function getStaffs(){
    return $this->staffmodel->getAllStaff();
   }
   public function findbyId($id){
    return $this->staffmodel->findbyId($id);
   }
   public function addStaff($id, $idRole, $idFunction, $status){
    return $this->staffmodel->addStaff($id, $idRole, $idFunction, $status);
    }
    public function getNextStaffID(){
        return $this->staffmodel->getNextStaffID();
    }
    function updateStaff($id, $idRole, $status){
        return $this->staffmodel->updateStaff($id, $idRole, $status);
    }
    public function deleteStaffById($id) {
        return $this->staffmodel->deleteStaffById($id);
        }
   public function findRoleByIdStaff($idStaff) {
    return $this->staffmodel->findRoleByIdStaff($idStaff);
    }
    public function getPaginatedStaff($limit, $offset) {
        return $this->staffmodel->getPaginatedStaff($limit, $offset);
    }
    public function countAllStaff() {
        return $this->staffmodel->countAllStaff();
    }
    public function searchStaff($keyword) {
        return $this->staffmodel->searchStaff($keyword);
    }
    }
?>