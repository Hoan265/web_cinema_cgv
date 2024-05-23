<?php 
session_start(); 
require_once "./mvc/Controllers/Account.php";

// require_once "./mvc/Controllers/updatepassword";
 $accountController = new Account();
$email = $_SESSION['email']; 

// session_start(); 
// require_once "../../Core/database_account.php";

// function getOldPass($conn, $username){
//     $sql = "SELECT Password FROM Users WHERE Email = '$username'";
//     $result = mysqli_query($conn, $sql);
//     if (!$result) {
//         echo "Error: " . mysqli_error($conn);
//         die();
//     }
//     $user = mysqli_fetch_assoc($result);
//     return $user['Password'];
// }

// function updatePass($conn, $username, $newPass){
//     $hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
//     $updateQuery = "UPDATE Users SET Password = '$hashedPassword' WHERE Email = '$username'";
//     $updateResult = mysqli_query($conn, $updateQuery);
//     if ($updateResult) {
//         return true; // Trả về true nếu cập nhật thành công
//     } else {
//         return false; // Trả về false nếu có lỗi khi cập nhật
//     }
// }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updatePass"])) {
    $password = $_POST['oldPassword'];
    $newpass = $_POST['newPassword'];
    $confirmpass = $_POST['confirmNewPassword'];
    $email = $_SESSION['email'];
    $oldPass = $accountController->getOldPass($email);
    if (password_verify($password, $oldPass)){
        if($newpass == $confirmpass){
            if($accountController->updatePass( $email, $newpass)){
                 $success_message = "Cập nhật mật khẩu thành công";
            } else {
                $error_message = "Vui lòng kiểm tra lại thông tin";
            }
        } else {
            $error_message = "Mật khẩu mới và xác nhận mật khẩu mới không khớp.";
        }
    } else {
        $error_message = "Mật khẩu cũ không chính xác.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="./assets/css/updatepassword.css">
</head>

<body>
    <div class="changePasswordBox">
        <h3>Đổi mật khẩu</h3>
        <form action="" method="post">
            <div class="inputBox">
                <input id="oldPassword" type="password" name="oldPassword" placeholder="Mật khẩu cũ" required>
            </div>
            <div class="inputBox">
                <input id="newPassword" type="password" name="newPassword" placeholder="Mật khẩu mới" required>
            </div>
            <div class="inputBox">
                <input id="confirmNewPassword" type="password" name="confirmNewPassword" placeholder="Xác nhận mật khẩu mới" required>
            </div>
            <?php
        if(isset($success_message)) {
            echo '<div class="success-msg">' . $success_message . '</div>';
        }
        if(isset($error_message)) {
            echo '<div class="error-msg">' . $error_message . '</div>';
        }
        ?>
            <input type="submit" value="Đổi mật khẩu" name="updatePass">
            <div class="return-link">
                <a href="infocustomer">Trở về</a>
            </div>
        </form>
    </div>
</body>
</html>
