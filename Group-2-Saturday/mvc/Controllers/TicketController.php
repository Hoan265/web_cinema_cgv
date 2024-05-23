<?php 
require_once __DIR__ . '/../Models/PromotionModel.php';

class TicketController extends Controller{
    private $promotionmodel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this->promotionmodel = new PromotionModel();
        parent::__construct();
    }
    public function findByID($id) {
        return $this->promotionmodel->findByID($id);
        }
        
   }