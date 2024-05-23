<?php 
require_once __DIR__ . '/../Models/DetailFoodModel.php';

class DetailFoodController extends Controller{
    private $detailmodel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this-> detailmodel = new DetailFoodModel();
        parent::__construct();
    }
    public function getAllDetail() {
        return  $this-> detailmodel -> getAllDetail();
    }
    public function getDetailFoods($selectedFoodId) {
        return  $this-> detailmodel -> getDetailFoods($selectedFoodId);
    }
    public function getDetailSizeFoods($id) {

        return  $this-> detailmodel -> getDetailSizeFoods($id);
}
public function createIDetailFoods() {
    return  $this-> detailmodel -> createIDetailFoods();
}
public function updateSizeFoods($id, $price, $status) {
    return  $this-> detailmodel -> updateSizeFoods($id, $price, $status);
}
public function addSize($id, $price, $status, $idF, $idS) {
    return  $this-> detailmodel -> addSize($id, $price, $status, $idF, $idS);
    }
    public function deleteDetailByFoodsId($idFoods) {
        return  $this-> detailmodel -> deleteDetailByFoodsId($idFoods);
}
public function updateStatusByFoodsId($idFoods, $status) {
    return  $this-> detailmodel -> updateStatusByFoodsId($idFoods, $status);

    }
}
    ?>