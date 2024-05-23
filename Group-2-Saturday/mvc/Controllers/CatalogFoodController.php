<?php 
require_once __DIR__ . '/../Models/CatalogFoodModel.php';

class CatalogFoodController extends Controller{
    private $catalogModel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this-> catalogModel = new CatalogFoodModel();
        parent::__construct();
    }
    public function getAllCatalog() {
        return  $this-> catalogModel -> getAllCatalog();
    }
    public function setCatalog($id) {
        return  $this-> catalogModel -> setCatalog($id);
    }
    public function setIdCatalog($ten) {
        return  $this-> catalogModel -> setIdCatalog($ten) ;
    }
}
    ?>