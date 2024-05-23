<?php 
require_once __DIR__ . '/../Models/FoodsModel.php';

class Foods extends Controller{
    private $foodModel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this-> foodModel = new FoodsModel();
        parent::__construct();
         require_once "./mvc/Core/Pagination.php";
    }
    public function default()
    {
            $this->view("main_layout",[
                "Page" => "Foods",
                "Title" => "Quản lý Thức Ăn", 
                "PageRole" => "admin", 
                
            ]);
           
            
    }
    public function getAllFoods() {
        return  $this-> foodModel -> getAllFoods();
    }
    public function setNameFood($id){
        return  $this-> foodModel -> setNameFood($id);
            }
    public function addFoods($id, $name, $status, $idCatalog, $img) {
        return  $this-> foodModel -> addFoods($id, $name, $status, $idCatalog, $img);

        }
    public function deleteFood($selectedFoodId) {
        return  $this-> foodModel -> deleteFood($selectedFoodId);
        }

   public function updateFoods($id, $name, $status, $idcatalog, $img) {
    return  $this-> foodModel -> updateFoods($id, $name, $status, $idcatalog, $img);
    }
    public function getF($id) {
        return  $this-> foodModel -> getF($id);

        }
        public function generateFoodId($idCatalog) {
            return  $this-> foodModel ->  generateFoodId($idCatalog);
    }
    public function getAll(){
        return  $this-> foodModel -> getAll();
    }
    public function updateFoodStatus($id, $newStatus) {
        return  $this-> foodModel -> updateFoodStatus($id, $newStatus);
    }
    public function checkFoodIdWithCatalog($idFood, $idCatalog) {
        return  $this-> foodModel -> checkFoodIdWithCatalog($idFood, $idCatalog);

}
public function getPaginatedFoods($limit, $offset) {
    return  $this-> foodModel -> getPaginatedFoods($limit, $offset);
}
public function countAllFoods() {
    return  $this-> foodModel -> countAllFoods();
    }
public function searchFoods($keyword) {
    return  $this-> foodModel -> searchFoods($keyword);
}
public function updateFoodImage($foodId, $imageData) {
    return  $this-> foodModel -> updateFoodImage($foodId, $imageData) ;
            
}

}
    ?>