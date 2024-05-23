<?php 
require_once __DIR__ . '/../Models/RoleStaffModel.php';

class RoleStaffController extends Controller{
    private $rolestaffmodel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this->rolestaffmodel = new RoleStaffModel();
        parent::__construct();
    }
   public function getAllRoleStaff(){
    return $this->rolestaffmodel->getAllRoleStaff();
   }
   public function getIdRole($id){
    return $this->rolestaffmodel->findIdRole($id);
   }
   public function getNextRoleID($roleName){
    return $this->rolestaffmodel->getNextRoleID($roleName);
}
public function findIdByName($ten){
    return $this->rolestaffmodel->findIdByName($ten);
    
}
}

?>