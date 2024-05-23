<?php 
require_once __DIR__ . '/../Models/FunctionModel.php';

class FunctionController extends Controller{
    private $functionmodel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this->functionmodel = new FunctionModel();
        parent::__construct();

    }
   public function getUsers(){
    return $this->functionmodel->getAllFunctions();
   }
}
?>