<?php
// Include database connection
// require_once __DIR__ . '/../Core/DbConnection.php';
require_once __DIR__ . '/../Entity/FilmEntity.php';

class FilmModel extends DbConnection{
    private $conn;

    public function __construct() {
        // Create a new database connection
        $this->conn = new DbConnection();
        parent::__construct();

    }

    public function getFilmById($filmId) {
        $query = "SELECT * FROM film WHERE id = ?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $filmId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $film = new FilmEntity(
                $row['id'],
                $row['TenPhim'],
                $row['DV'],
                $row['TheLoai'],
                $row['ThoiLuong'],
                $row['NgayKC'],
                $row['NgayKT'],
                $row['NoiSX'],
                $row['DaoDien'],
                $row['NamSX'],
                $row['NoiDung'],
                $row['HinhAnh'],
                $row['DoTuoi'],
                $row['url_film']
            );
            return $film;
        } else {
            return null;
        }
    }
    public function getById($id)
    {
        $sql = "SELECT * FROM `film` as `f`
                JOIN `lichchieu` as `lc` ON `f`.`id` = `lc`.`id`  WHERE `f`.`id` = '$id'";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        return mysqli_fetch_assoc($result);
    }

    public function getAllFilms() {
        $films = array();
        $query = "SELECT * FROM film";

        $result = $this->conn->getConnection()->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $film = new FilmEntity(
                    $row['id'],
                    $row['TenPhim'],
                    $row['DV'],
                    $row['TheLoai'],
                    $row['ThoiLuong'],
                    $row['NgayKC'],
                    $row['NgayKT'],
                    $row['NoiSX'],
                    $row['DaoDien'],
                    $row['NamSX'],
                    $row['NoiDung'],
                    $row['HinhAnh'],
                    $row['DoTuoi'],
                    $row['url_film']
                );
                array_push($films, $film);
            }
        } else {
            echo "Không có dữ liệu phim";
        }

        return $films;
    }

    public function addFilm($filmData) {
        $query = "INSERT INTO film (id,TenPhim, DV, TheLoai, ThoiLuong, NgayKC, NgayKT, NoiSX, DaoDien, NamSX, NoiDung, HinhAnh, DoTuoi, url_film) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("isssssssisssss", $filmData['id'], $filmData['TenPhim'], $filmData['DV'], $filmData['TheLoai'], $filmData['ThoiLuong'], 
            $filmData['NgayKC'], $filmData['NgayKT'], $filmData['NoiSX'], $filmData['DaoDien'], $filmData['NamSX'], $filmData['NoiDung'], 
            $filmData['HinhAnh'], $filmData['DoTuoi'], $filmData['url_film']);


        if ($stmt->execute()) {
            return true; 
        } else {
            return false;
        }
    }

    public function updateFilm($filmId, $filmData) {
        $query = "UPDATE film SET TenPhim=?, DV=?, TheLoai=?, ThoiLuong=?, NgayKC=?, NgayKT=?, NoiSX=?, DaoDien=?, NamSX=?, NoiDung=?, 
                  HinhAnh=?, DoTuoi=?, url_film=? WHERE id=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("ssssssssissssi", $filmData['TenPhim'], $filmData['DV'], $filmData['TheLoai'], $filmData['ThoiLuong'], 
            $filmData['NgayKC'], $filmData['NgayKT'], $filmData['NoiSX'], $filmData['DaoDien'], $filmData['NamSX'], $filmData['NoiDung'], 
            $filmData['HinhAnh'], $filmData['DoTuoi'], $filmData['url_film'], $filmId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteFilm($filmId) {
        $query = "DELETE FROM film WHERE id=?";
        
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bind_param("i", $filmId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getAll()
    {
        $sql = "SELECT * FROM `film`";
        $result = mysqli_query($this->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    function convertGenres($list) {
        $genres = [];
    
        // Kiểm tra nếu $list là một mảng đa chiều
        if (is_array($list) && count($list) > 0 && is_array($list[0])) {
            foreach ($list as $subList) {
                foreach ($subList as $item) {
                    // Tách các thể loại ra từ chuỗi dựa vào dấu phẩy
                    $split_genres = explode(',', $item);
                    
                    foreach ($split_genres as $genre) {
                        // Loại bỏ các khoảng trắng ở đầu và cuối chuỗi thể loại
                        $genre = trim($genre);
                        
                        // Kiểm tra xem thể loại đã tồn tại trong mảng chưa
                        if (!in_array($genre, $genres)) {
                            // Nếu chưa tồn tại thì thêm vào mảng
                            $genres[] = $genre;
                        }
                    }
                }
            }
        } else { // Nếu $list là một mảng một chiều
            foreach ($list as $item) {
                // Tách các thể loại ra từ chuỗi dựa vào dấu phẩy
                $split_genres = explode(',', $item);
                
                foreach ($split_genres as $genre) {
                    // Loại bỏ các khoảng trắng ở đầu và cuối chuỗi thể loại
                    $genre = trim($genre);
                    
                    // Kiểm tra xem thể loại đã tồn tại trong mảng chưa
                    if (!in_array($genre, $genres)) {
                        // Nếu chưa tồn tại thì thêm vào mảng
                        $genres[] = $genre;
                    }
                }
            }
        }
    
        // Sắp xếp lại mảng thể loại theo thứ tự abc
        sort($genres);
    
        return $genres;
    }
    
    public function getAllTheLoai()
    {
        $sql = "SELECT DISTINCT TheLoai  FROM `film`";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $this->convertGenres($rows);
    }
    public function getAllNoiSX()
    {
        $sql = "SELECT DISTINCT NoiSX  FROM `film`";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getAllNamSX()
    {
        $sql = "SELECT DISTINCT NamSX  FROM `film` ORDER BY NamSX ASC";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getAllDoTuoi()
    {
        $sql = "SELECT DISTINCT DoTuoi  FROM `film` ORDER BY DoTuoi ASC";
        $result = mysqli_query($this->conn->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT * FROM film WHERE STR_TO_DATE(NgayKT, '%d/%m/%Y') >= CURRENT_DATE  ";        
        if(isset($args['title'])){
            if($args['title']=='1'){ //Đang chiếu
                $query .= " AND STR_TO_DATE(NgayKT, '%d/%m/%Y') <= CURRENT_DATE";
            }
            if($args['title']=='2'){ //Sắp chiếu
                $query .= " AND STR_TO_DATE(NgayKC, '%d/%m/%Y') >= CURRENT_DATE";
            }            
        }
        if (isset($filter['TheLoai'])) {
            $query .= " AND TheLoai LIKE N'%${filter['TheLoai']}%'";
        }
        if (isset($filter['NoiSX'])) {
            $query .= " AND NoiSX LIKE N'${filter['NoiSX']}'";
        }
        if (isset($filter['NamSX'])) {
            $query .= " AND NamSX = " .$filter['NamSX'];
        }
        if (isset($filter['DoTuoi'])) {
            $query .= " AND DoTuoi >=" . $filter['DoTuoi'];
        }
        if ($input) {
            $query = $query . " AND (TenPhim LIKE N'%${input}%')";
        }
        $query = $query . " ORDER BY id ASC";
        return $query;
    }
    public function getByTheLoai($TheLoai)
    {
        $theloailist = explode(",", $TheLoai);
        $films = array();
        foreach ($theloailist as $theloai) {
            $like_conditions[] = "TheLoai LIKE '%" . trim($theloai) . "%'";
        }
        $query = "SELECT * FROM film WHERE " . implode(" OR ", $like_conditions);

        $result = $this->conn->getConnection()->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $film = new FilmEntity(
                    $row['id'],
                    $row['TenPhim'],
                    $row['DV'],
                    $row['TheLoai'],
                    $row['ThoiLuong'],
                    $row['NgayKC'],
                    $row['NgayKT'],
                    $row['NoiSX'],
                    $row['DaoDien'],
                    $row['NamSX'],
                    $row['NoiDung'],
                    $row['HinhAnh'],
                    $row['DoTuoi'],
                    $row['url_film']
                );
                array_push($films, $film);
            }
        } else {
            echo "Không có dữ liệu phim";
        }

        return $films;
    }
}
?>
