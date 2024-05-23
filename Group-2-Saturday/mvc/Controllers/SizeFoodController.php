<?php 
require_once __DIR__ . '/../Models/SizeFoodModel.php';
class SizeFoodController extends Controller{
    private $sizemodel;
    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this-> sizemodel = new SizeFoodModel();
        parent::__construct();
    }
    public function getAllSize() {
        return  $this-> sizemodel -> getAllSize();
    }
    public function setNameSize($idS){
        return  $this-> sizemodel -> setNameSize($idS);
    }
    public function setIdSize($ten) {
        return  $this-> sizemodel -> setIdSize($ten);
        }
    public function showSize($idF) {
        return  $this-> sizemodel -> showSize($idF);
        }

}
    ?>