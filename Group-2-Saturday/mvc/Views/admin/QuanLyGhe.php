<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý ghế</title>
    <link rel="stylesheet" href="./assets/css/QuanLyGhe.css">
</head>

<body>
    <h2>Quản lý ghế</h2>

    <form action="process.php" method="post" enctype="multipart/form-data">
        <label for="id">ID:</label><br>
        <input type="text" id="id" name="id"><br>

        <label for="ten">Tên ghế:</label><br>
        <input type="text" id="ten" name="ten"><br>

        <label for="hinhanh">Hình ảnh:</label><br>
        <img class="img_ghe" src="" alt="">
        <input type="file" id="hinhanh" name="hinhanh"><br>

        <label for="gia">Giá ghế:</label><br>
        <input type="text" id="gia" name="gia"><br><br>

        <input type="submit" name="submit" value="Thêm">
        <input type="submit" name="submit" value="Sửa">
        <input type="submit" name="submit" value="Xóa">
    </form>

    <br>

    <table >
        <tr>
            <th>ID</th>
            <th>Tên ghế</th>
            <th>Hình ảnh</th>
            <th>Giá ghế</th>
        </tr>
        <?php
        // Kết nối đến cơ sở dữ liệu và truy vấn dữ liệu ghế
        // Giả sử bạn đã có hàm để lấy dữ liệu ghế từ cơ sở dữ liệu
        // Ví dụ:
        // $seats = getAllSeatsFromDatabase();
        
        // Dùng dữ liệu ghế lấy được để hiển thị trong bảng
        // foreach ($seats as $seat) {
        //     echo "<tr>";
        //     echo "<td>{$seat['id']}</td>";
        //     echo "<td>{$seat['ten']}</td>";
        //     echo "<td><img src='images/{$seat['hinhanh']}' width='100'></td>"; // Giả sử hình ảnh được lưu trong thư mục 'images'
        //     echo "<td>{$seat['gia']}</td>";
        //     echo "</tr>";
        // }
        ?>
    </table>

    <br>

    <button class="button_trove">Trở về</button>
</body>

</html>
