<?php 
 require_once __DIR__ . '/../Core/dataAccount.php';
require_once __DIR__ . '/../Entity/ClientEntity.php';

class ClientModel extends DbConnection{
    private $conn;
    
    public function __construct() {
        $this->conn = new DbConnection();
    }
    public function getAllClient() {
        $query = "SELECT * FROM Client";
        $result = $this->conn->getConnection()->query($query);

        $clients = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                  $client = new ClientEntity(
                    $row['IdClient'],
                    $row['Ponit'],
                  );
                  array_push($clients, $client);
            }
        }
        return $clients;
}
public function getPOINT($id){
    $query = "SELECT Point FROM  Client WHERE IdClient = '$id'";
        $result = $this->conn->getConnection()->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Point'];
        } else {
            return null;
        }

   }
   public function getPaginatedClients($limit, $offset) {
    $query = "SELECT * FROM Client LIMIT ? OFFSET ?";
    $stmt = $this->conn->getConnection()->prepare($query);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $clientList = array();
    while ($row = $result->fetch_assoc()) {
        $client = new ClientEntity(
            $row['IdClient'],
            $row['Point']
        );
        array_push($clientList, $client);
    }
    return $clientList;
}
public function countAllClients() {
    $query = "SELECT COUNT(*) AS total FROM Client";
    $result = $this->conn->getConnection()->query($query);
    $total = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    return $total;
}
public function searchClient($keyword) {
    $searchTerm = $this->conn->getConnection()->real_escape_string($keyword);
    $query = "SELECT * FROM Client WHERE IdClient LIKE '%$searchTerm%' OR Point LIKE '%$searchTerm%'";
    $result = $this->conn->getConnection()->query($query);
    $searchResults = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $client = new ClientEntity(
                $row['IdClient'],
                $row['Point']
            );
            array_push($searchResults, $client);
        }
    }
    return $searchResults;
    }

    }