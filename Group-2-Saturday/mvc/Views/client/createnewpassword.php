<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP và Đổi Mật khẩu</title>
    <style>
        body {
            background: url(./assets/img/bg/bg_web1.webp) no-repeat !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            overflow: hidden !important;
        }
    </style>
    <link rel="stylesheet" href="./assets/css/createnewpassword.css">
</head>
<?php
session_start(); 
require_once './mvc/Controllers/CreateNewPassword.php';
require_once './mvc/Controllers/Account.php';
$accountController = new Account();
$newpass = new CreateNewPassword();


$email = $_SESSION['email_pass']; 

// function resetPassword($username, $newPassword) {
//     global $conn;

//     // Kiểm tra xem username có tồn tại trong bảng Users không
//     $query = "SELECT * FROM Users WHERE Email = '$username'";
//     $result = mysqli_query($conn, $query);

//     if ($result && mysqli_num_rows($result) > 0) {
//         // Mật khẩu mới được hash trước khi cập nhật vào cơ sở dữ liệu
//         $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

//         // Cập nhật mật khẩu mới vào cơ sở dữ liệu
//         $updateQuery = "UPDATE Users SET Password = '$hashedPassword' WHERE Email = '$username'";
//         $updateResult = mysqli_query($conn, $updateQuery);

//         if ($updateResult) {
//             return true; // Trả về true nếu cập nhật thành công
//         } else {
//             return false; // Trả về false nếu có lỗi khi cập nhật
//         }
//     } else {
//         return false; // Trả về false nếu username không tồn tại trong cơ sở dữ liệu
//     }
// }

if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword === $confirmPassword) {
        $username = $_SESSION['email_pass'];// Thay đổi để lấy giá trị của username từ form hoặc session
        if ($accountController->resetPassword($username, $newPassword)) {
            $success_message= "Đổi mật khẩu thành công";
            echo '<script>';
                echo 'window.location.href = "/Group-2-Saturday/Login";'; // Chuyển hướng đến trang OTP
                echo '</script>';
                exit;
        } else {
            $error_message= "Đổi mật khẩu không thành công. Vui lòng thử lại sau.";
        }
    } else {
        $error_message= "Mật khẩu mới và xác nhận mật khẩu không khớp.";
    }
}
?>

<body>
    <div class="confirmPasswordBox">
        <h3>Đổi Mật Khẩu</h3>
        <form action="" method="post">
            <div class="inputBox">
                <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
            </div>
            <div class="inputBox">
                <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required>
            </div>
            <?php
        if(isset($success_message)) {
            echo '<div class="success-msg">' . $success_message . '</div>';
        }
        if(isset($error_message)) {
            echo '<div class="error-msg">' . $error_message . '</div>';
        }
        ?>
            <input type="submit" value="Đổi Mật Khẩu">
        </form>
    </div>
</body>

</html>
