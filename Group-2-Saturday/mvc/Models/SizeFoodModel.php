<?php 
require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/SizeFoodEntity.php';

class SizeFoodModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }
    public function getAllSize() {
        $query = "SELECT * FROM SizeFood";
        $result = $this->conn->getConnection()->query($query);
        $sizes = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                 $size = new CatatalogFoodEntity(
                    $row['Id'],
                    $row['Ten']
                  );
                  array_push( $sizes,  $size );
            }
        }
        return $sizes;
}
public function setNameSize($idS) {
    $query = "SELECT Ten FROM SizeFood WHERE Id = '$idS'";
    $result = $this->conn->getConnection()->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Ten'];
    } else {
        return null; 
    }
}
public function setIdSize($ten) {
    $sql = "SELECT Id FROM SizeFood WHERE Ten = '$ten'";
    $result = $this->conn->getConnection()->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Id'];
    } else {
        return null;
    }
}
public function showSize($idF) {
    $sizes = array();
    $sql = "SELECT *
            FROM SizeFood
            WHERE Id NOT IN (
                SELECT DISTINCT IdSize
                FROM DetailFood
                WHERE IdFoods = '$idF'
            );";
    $result = $this->conn->getConnection()->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sizes[] = $row['Ten']; 
        }
    }
    return $sizes;
}

}
?>