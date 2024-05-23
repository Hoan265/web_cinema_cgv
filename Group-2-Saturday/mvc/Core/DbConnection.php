<?php
class DbConnection
{
    private $servername = "localhost"; // Tên máy chủ MySQL
    private $username = "root"; // Tên người dùng MySQL (mặc định là 'root')
    private $password = ""; // Mật khẩu MySQL (để trống hoặc thay thế bằng mật khẩu nếu đã thiết lập)
    private $dbname = "cgv"; // Tên cơ sở dữ liệu MySQL
    private $conn;

    public function __construct()
    {
        // Kết nối đến cơ sở dữ liệu
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
    }

    // Phương thức trả về kết nối đến cơ sở dữ liệu
    public function getConnection()
    {
        return $this->conn;
    }
    public function pagination($query, $limit, $start_from)
    {
        $sql = "$query LIMIT $start_from, $limit";
        $result = mysqli_query($this->conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function getNumberPage($query, $limit)
    {
        $page_result = mysqli_query($this->conn, $query);
        $total_records = mysqli_num_rows($page_result);
        $total_pages = ceil($total_records / $limit);
        return $total_pages;
    }
}
