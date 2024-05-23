<?php 
require_once __DIR__ . '/../Models/ClientModel.php';

class Client extends Controller{
    private $clientModel;

    public function __construct() {
        $this->clientModel = new ClientModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout", [
                "Page" => "Client" ,
                "Title" => "Quản lý khách hàng ",
                "PageRole" => "admin",
            ]);
    }
    public function getAllClient(){
        return $this->clientModel->getAllClient();
    }     
    public function getPOINT($id){
        return $this->clientModel->getPOINT($id);
        }
        public function getPaginatedClients($limit, $offset) {
            return $this->clientModel->getPaginatedClients($limit, $offset);
    }
    public function countAllClients() {
                return $this->clientModel-> countAllClients();
        }
        public function searchClient($keyword) {
            return $this->clientModel-> searchClient($keyword);
}
        }
?>