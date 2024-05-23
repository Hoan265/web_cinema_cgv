<?php 

class ClientEntity {
    public $idClient;
    public $point;
    private $email;
    private $phone;
    private $userName;

    // Constructor
    public function __construct($idClient, $point, $email = null, $phone = null, $userName = null) {
        $this->idClient = $idClient;
        $this->point = $point;
        $this->email = $email;
        $this->phone = $phone;
        $this->userName = $userName;
    }
    public function getIdClient() {
        return $this->idClient;
    }
    public function setIdClient($idClient) {
        $this->idClient = $idClient;
    }
    public function getPoint() {
        return $this->point;
    }
    public function setPoint($point) {
        $this->point = $point;
    }
}

?>