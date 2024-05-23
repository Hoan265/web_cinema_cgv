<?php 
// require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/CatalogFoodEntity.php';

class CatalogFoodModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }
    public function getAllCatalog() {
        $query = "SELECT * FROM CatalogFood";
        $result = $this->conn->getConnection()->query($query);
        $catalogs = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                  $catalog = new CatatalogFoodEntity(
                    $row['Id'],
                    $row['Ten']
                  );
                  array_push( $catalogs, $catalog);
            }
        }
        return $catalogs;
}
public function setCatalog($id) {
    $query = "SELECT Ten FROM CatalogFood WHERE Id = '$id'";
    $result = $this->conn->getConnection()->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Ten'];
    } else {
        return null;
    }
}
public function setIdCatalog($ten) {
    $query = "SELECT Id FROM CatalogFood WHERE Ten = '$ten'";
    $result = $this->conn->getConnection()->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Id'];
    } else {
        return null;
    }
}



}
?>