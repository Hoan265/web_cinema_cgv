<?php 
 class PromotionModel extends DbConnection{
     public function create($id, $name,$img, $start, $end, $percent,$trangthai)
     {
         $sql = "INSERT INTO `Promotion`(`IDPromotion`,`NamePromotion`,`image`, `Start`,`End`,`Percent`, `Status`) 
         VALUES ('$id','$name','$img','$start','$end','$percent', $trangthai)";
         $check = true;
         $result = mysqli_query($this->getConnection(), $sql);
         if (!$result) {
             $check = false;
         }
         return $check;
     }
     
    public function checkUser($id)
    {
        $sql = "SELECT * FROM `Promotion` WHERE `IDPromotion` = $id";
        $result = mysqli_query($this->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getAll()
    {
        $sql = "SELECT * FROM `Promotion` WHERE Status = 1 ";
        $result = mysqli_query($this->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT * FROM Promotion WHERE Status = 1";
        if ($input) {
            $query = $query . " AND (NamePromotion LIKE N'%${input}%' OR IDPromotion LIKE '%${input}%')";
        }
        $query = $query . " ORDER BY IDPromotion ASC";
        return $query;
    }
    public function getById($id)
    {
        $sql = "SELECT * FROM `Promotion` WHERE `IDPromotion` = '$id'";
        $result = mysqli_query($this->getConnection(), $sql);
        return mysqli_fetch_assoc($result);
    }
    public function getIDMax(){
        $sql = "SELECT MAX(IDPromotion) as MaxID FROM `Promotion`";
        $result = mysqli_query($this->getConnection(), $sql);
        return mysqli_fetch_assoc($result);
    }
    public function delete($id)
    {
        $check = true;
        $sql = "DELETE FROM `Promotion` WHERE `IDPromotion`='$id'";
        $result = mysqli_query($this->getConnection(), $sql);
        if (!$result) $check = false;
        return $check;
    }

    public function update($id, $ten,$hinhanh,$start,$end,$percent, $trangthai)
    {
        $sql = "UPDATE `Promotion` SET 
        `NamePromotion`='$ten',
        --  `Image`='$hinhanh', 
        `Image` = CASE WHEN '$hinhanh' != '' THEN '$hinhanh' ELSE `Image` END,
         `Start`='$start',
         `End`='$end',
         `Percent`='$percent',
         `Status`='$trangthai' 
         WHERE `IDPromotion`='$id'";
        // return $sql;
        $check = true;
        $result = mysqli_query($this->getConnection(), $sql);
        if (!$result) $check = false;
        return $check;
    }

    //////////
    public function findDiscountPercentById($id) {
        $sql = "SELECT Percent FROM `Promotion` WHERE `IDPromotion` = '$id'";
        $result = mysqli_query($this->getConnection(), $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['Percent'];
    }
}

?>