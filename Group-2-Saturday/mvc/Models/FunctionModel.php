<?php 
// require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/FunctionEntity.php';

class FunctionModel extends DbConnection{
    private $conn;

    public function __construct() {
        $this->conn = new DbConnection();
    }

    public function getAllFunctions() {
        $query = "SELECT * FROM Function";
        $result = $this->conn->getConnection()->query($query);

        $functions = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $function = new FunctionEntity(
                    $row['Id'],
                    $row['Name'],
                    $row['IdRole']
                  );
                  array_push($functions,  $function);
            }
        }
        return   $functions;
}
}
?>