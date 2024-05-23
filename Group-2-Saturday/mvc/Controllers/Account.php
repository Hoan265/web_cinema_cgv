<?php 
require_once __DIR__ . '/../Models/AccountModel.php';

class Account extends Controller{
    private $accountmodel;
    

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this->accountmodel = new AccountModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout",[
                "Page" => "Account",
                "Title" => "Quản lý nhân viên",
                "PageRole" => "admin",                                 
            ]);
            
    }
   public function getUsers(){
    return $this->accountmodel->getAllAccount();
   }
   public function getIDbyEmail($email)  {
    return $this->accountmodel->getUserByEmail($email);
   }
   public function findbyId($id){
    return $this->accountmodel->findbyId($id);
   }

   public function addAccount($id,$email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block){
    return $this->accountmodel->addAccount($id,$email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block);
   }
   public function checkEmailExists($email){
    return $this->accountmodel->checkEmailExists($email);
   }
   public function  updateAccountByEmail($id, $name, $phone, $sex, $birthday) { 
    return $this->accountmodel->updateAccountByEmail($id, $name, $phone, $sex, $birthday);
    }


public function deleteAccountById($id){
    return $this->accountmodel->deleteAccountById($id);
}
public function CheckRole($username) {
    return $this->accountmodel->CheckRole($username);

}
public function setNameUser($username) {
    return $this->accountmodel->setNameUser($username);

}

public function getUserByEmail($email) {
    return $this->accountmodel->getUserByEmail($email);

    }
public function updateAvatar($username, $name_img) {
    return $this->accountmodel->updateAvatar($username, $name_img);
    }
public function updateInfo($username, $phone_number, $name, $birthday, $gender) {
    return $this->accountmodel->updateInfo($username, $phone_number, $name, $birthday, $gender);
    }
    public function resetPassword($username, $newPassword) {

        return $this->accountmodel-> resetPassword($username, $newPassword);
}
public function getOldPass($username) {
    return $this->accountmodel->getOldPass($username);
    }
    public function updatePass($username, $newPass) {
     return $this->accountmodel->updatePass($username, $newPass);

 }
 public function CreateAccount($id, $email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block) {

            return $this->accountmodel->CreateAccount($id, $email, $phone, $password, $name, $sex, $avatar, $birthday, $role, $block);
}
public function CreateClient($id) {
    return $this->accountmodel->CreateClient($id) ;
}
public function generateUniqueID() {
    return $this->accountmodel->generateUniqueID(); 
    }
    public function findBlockByEmail($email) {
        return $this->accountmodel->findBlockByEmail($email); 

        }
            public function  updateBlockStatus($id, $block){
                return $this->accountmodel->updateBlockStatus($id, $block);

            }
    public function findIdByEmail($email) {
        return $this->accountmodel-> findIdByEmail($email);
        }
        public function searchAccounts($searchTerm) {
            return $this->accountmodel->  searchAccounts($searchTerm);
}
}
?>