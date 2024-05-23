<?php 
class PromotionEntity {
    public $idPromotion;
    public $namePromotion;
    public $image;
    public $start;
    public $end;
    public $percent;
    public $status;

    // Constructor
    public function __construct($idPromotion, $namePromotion, $image, $start, $end, $percent, $status) {
        $this->idPromotion = $idPromotion;
        $this->namePromotion = $namePromotion;
        $this->image = $image;
        $this->start = $start;
        $this->end = $end;
        $this->percent = $percent;
        $this->status = $status;
    }

    // Getter và setter cho idPromotion
    public function getIdPromotion() {
        return $this->idPromotion;
    }

    public function setIdPromotion($idPromotion) {
        $this->idPromotion = $idPromotion;
    }

    // Getter và setter cho namePromotion
    public function getNamePromotion() {
        return $this->namePromotion;
    }

    public function setNamePromotion($namePromotion) {
        $this->namePromotion = $namePromotion;
    }

    // Getter và setter cho image
    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    // Getter và setter cho start
    public function getStart() {
        return $this->start;
    }

    public function setStart($start) {
        $this->start = $start;
    }

    // Getter và setter cho end
    public function getEnd() {
        return $this->end;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

    // Getter và setter cho percent
    public function getPercent() {
        return $this->percent;
    }

    public function setPercent($percent) {
        $this->percent = $percent;
    }

    // Getter và setter cho status
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>
