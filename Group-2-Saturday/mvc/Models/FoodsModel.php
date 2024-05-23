<?php
// require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/FoodsEntity.php';

class FoodsModel extends DbConnection
{
    private $conn;

    public function __construct()
    {
        $this->conn = new DbConnection();
    }
    public function getAllFoods()
    {
        $query = "SELECT * FROM Foods";
        $result = $this->conn->getConnection()->query($query);
        $foods = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $food = new FoodsEntity(
                    $row['Id'],
                    $row['Name'],
                    $row['Status'],
                    $row['IdCatalog'],
                    $row['img']
                );
                array_push($foods, $food);
            }
        }
        return $foods;
    }
    public function getAll()
    {
        $sql = "SELECT * FROM `Foods` as `f`  
             JOIN `Detailfood` as `df` ON `f`.`Id` = `df`.`IdFoods` 
             JOIN `SizeFood` as `sf` ON `sf`.`id` = `df`.`IdSize` 
             Where `df`.`Status` = 'Đang bán';";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function setNameFood($id)
    {
        $query = "SELECT Name FROM Foods  WHERE Id = '$id'";
        $result = $this->conn->getConnection()->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Name'];
        } else {
            return null;
        }
    }
    public function addFoods($id, $name, $status, $idCatalog, $img)
    {
        if ($id === NULL || $name === NULL || $status === NULL || $idCatalog === NULL) {
            throw new InvalidArgumentException('One or more required parameters are NULL');
        }
        if ($img === NULL) {
            $img = ''; 
        }

        $query = "INSERT INTO Foods (Id, Name, Status, IdCatalog, img) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->getConnection()->prepare($query);

        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->getConnection()->error);
        }
        if (!$stmt->bind_param("sssss", $id, $name, $status, $idCatalog, $img)) {
            throw new Exception('Binding parameters failed: ' . $stmt->error);
        }
        if (!empty($img)) {
            $stmt->send_long_data(4, $img);
        }

        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    public function deleteFood($selectedFoodId)
    {
        $query = "DELETE FROM Foods WHERE Id = '$selectedFoodId'";
        if ($this->conn->getConnection()->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    public function updateFoods($id, $name, $status, $idcatalog, $img)
    {
        $query = "UPDATE Foods SET Name = ?, Status = ?, IdCatalog = ?";
        if ($img !== null) {
            $query .= ", img = ?";
        }
        $query .= " WHERE Id = ?";
        $stmt = $this->conn->getConnection()->prepare($query);
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->getConnection()->error);
        }
        if ($img !== null) {
            $null = NULL;
            if (!$stmt->bind_param("sssbs", $name, $status, $idcatalog, $null, $id)) {
                throw new Exception('Binding parameters failed: ' . $stmt->error);
            }
            $stmt->send_long_data(3, $img);
        } else {
            if (!$stmt->bind_param("sssi", $name, $status, $idcatalog, $id)) {
                throw new Exception('Binding parameters failed: ' . $stmt->error);
            }
        }
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    public function getF($id)
    {
        $query = "SELECT * FROM Foods WHERE Id = '$id'";
        $result = $this->conn->getConnection()->query($query);
        if (!$result) {
            echo "Error: " . $this->conn->getConnection()->error;
            die();
        }
        $foodData = $result->fetch_assoc();
        $food = new FoodsEntity(
            $foodData['Id'],
            $foodData['Name'],
            $foodData['Status'],
            $foodData['IdCatalog'],
            $foodData['img']
        );
        return $food;
    }
    public function generateFoodId($idCatalog)
    {
        $sql_check = "SELECT COUNT(*) AS count FROM Foods WHERE IdCatalog = '$idCatalog'";
        $result_check = $this->conn->getConnection()->query($sql_check);
        if (!$result_check) {
            echo "Error: " . $this->conn->getConnection()->error;
            die();
        }
        $row_check = $result_check->fetch_assoc();
        $count = intval($row_check['count']);
        $newId = $idCatalog . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $sql_existing_id = "SELECT Id FROM Foods WHERE Id = '$newId'";
        $result_existing_id = $this->conn->getConnection()->query($sql_existing_id);
        if ($result_existing_id->num_rows > 0) {
            $sql_max_id = "SELECT MAX(SUBSTRING(Id, LENGTH('$idCatalog') + 1)) AS max_id FROM Foods WHERE Id LIKE '$idCatalog%'";
            $result_max_id = $this->conn->getConnection()->query($sql_max_id);
            $row_max_id = $result_max_id->fetch_assoc();
            $max_id = intval($row_max_id['max_id']);
            $newId = $idCatalog . str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }
    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT * FROM `Foods` as `f` 
        JOIN `Detailfood` as `df` ON `f`.`Id` = `df`.`IdFoods` 
        JOIN `SizeFood` as `sf` ON `sf`.`id` = `df`.`IdSize` 
        Where `df`.`Status` = 'Đang được bán' ";
        // if (isset($filter['role'])) {
        //     $query .= " AND ND.manhomquyen = " . $filter['role'];
        // }
        // if ($input) {
        //     $query = $query . " Where (`f`.`Name` LIKE N'%${input}%' OR `f`.`id` LIKE '%${input}%')";
        // }
        $query = $query . " ORDER BY `f`.`id` ASC";
        return $query;
    }
    public function updateFoodStatus($id, $newStatus)
    {
        $query = "UPDATE Foods SET Status = '$newStatus' WHERE Id = '$id'";
        if ($this->conn->getConnection()->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function checkFoodIdWithCatalog($idFood, $idCatalog)
    {
        $foodCatalogId = substr($idFood, 0, strlen($idCatalog));
        if ($foodCatalogId === $idCatalog) {
            return true;
        } else {
            return false;
        }
    }
    public function getPaginatedFoods($limit, $offset)
    {
        $query = "SELECT * FROM Foods LIMIT $limit OFFSET $offset";
        $result = $this->conn->getConnection()->query($query);
        $foods = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $food = new FoodsEntity(
                    $row['Id'],
                    $row['Name'],
                    $row['Status'],
                    $row['IdCatalog'],
                    $row['img']
                );
                array_push($foods, $food);
            }
        }
        return $foods;
    }

    public function countAllFoods()
    {
        $query = "SELECT COUNT(*) AS total FROM Foods";
        $result = $this->conn->getConnection()->query($query);
        $total = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total = $row['total'];
        }
        return $total;
    }
    public function searchFoods($keyword)
    {
        $searchTerm = $this->conn->getConnection()->real_escape_string($keyword);
        $query = "SELECT * FROM Foods WHERE Name LIKE '%$searchTerm%' OR Id LIKE '%$searchTerm%'";
        $result = $this->conn->getConnection()->query($query);
        $searchResults = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $food = new FoodsEntity(
                    $row['Id'],
                    $row['Name'],
                    $row['Status'],
                    $row['IdCatalog'],
                    $row['img']
                );
                array_push($searchResults, $food);
            }
        }
        return $searchResults;
    }
    public function updateFoodImage($foodId, $imageData)
    {
        $stmt = $this->conn->getConnection()->prepare("UPDATE Foods SET img = ? WHERE Id = ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->getConnection()->error);
        }

        $null = NULL;
        if (!$stmt->bind_param("bs", $null, $foodId)) {
            throw new Exception('Binding parameters failed: ' . $stmt->error);
        }

        $stmt->send_long_data(0, $imageData);

        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
        return true;
    }
}
