<?php
session_start();
require_once './mvc/Controllers/Login.php';
require_once './mvc/Controllers/StaffController.php';
require_once './mvc/Controllers//RoleStaffController.php';
require_once './mvc/Controllers/Account.php';
$staffController = new StaffController();
$accountController = new Account();
$roleController = new RoleStaffController();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            background: url(./assets/img/bg/bg_web1.webp) no-repeat !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            overflow: hidden !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/login.css">
</head>

<body>
    <div class="loginBox">
        <img class="user" src="./assets/img/images/user_account.png" height="100px" width="100px">
        <h3>Đăng Nhập</h3>
        <?php
        // session_start();
        // require_once "../../Core/database_account.php";
        // function CheckRole($conn, $username) {
        //     $sql = "SELECT Role FROM Users WHERE Email = '$username'";
        //     $result = mysqli_query($conn, $sql);
        //     if (!$result) {
        //         echo "Error: " . mysqli_error($conn);
        //         die();
        //     }
        //     $user = mysqli_fetch_assoc($result);
        //     return $user['Role'];
        // }
        // function setNameUser($conn,$username){
        //     $sql = "SELECT Name FROM Users WHERE Email = '$username'";
        //     $result = mysqli_query($conn, $sql);
        //     if (!$result) {
        //         echo "Error: " . mysqli_error($conn);
        //         die();
        //     }
        //     $user = mysqli_fetch_assoc($result);
        //     return $user['Name'];
        // }
        if (isset($_POST['Login'])) {
            $username = $_POST['Username'];
            $password = $_POST['Password'];
            $user = $accountController->getUserByEmail($username);
            $block = $accountController->findBlockByEmail($username);
            $hashedPassword = $user->getPassword();

            if ($user) {
                if (password_verify($password, $hashedPassword)) {
                    if ($block == "Off") {
                        $role = $accountController->CheckRole($username);

                        if ($role == "client") {
                            $_SESSION['email_client'] = $_POST['Username'];
                            $_SESSION['name_client'] = $accountController->setNameUser($_POST['Username']);
                            echo '<div class="error-box" style="background-color: #FFF0F0; color: yellowgreen; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
                            echo 'Đăng nhập thành công!';
                            echo '</div>';
                            
                            echo '<script>';
                            echo 'setTimeout(function() {';
                            echo '    window.location.href = "/Group-2-Saturday/home";'; // Chuyển hướng đến trang /Group-2-Saturday/home sau 2 giây
                            echo '}, 1000);'; // Thời gian chờ là 2000ms (tức là 2 giây)
                            echo '</script>';
                            
                            

                            
                            die();
                        } elseif ($role == "admin") {
                            $_SESSION['email_admin'] = $_POST['Username'];
                            $_SESSION['name_admin'] = $accountController->setNameUser($_POST['Username']);
                            
                            echo '<div class="error-box" style="background-color: #FFF0F0; color: yellowgreen; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
                            echo 'Đăng nhập thành công!';
                            echo '</div>';
                            
                            echo '<script>';
                            echo 'setTimeout(function() {';
                            echo '    window.location.href = "/Group-2-Saturday/ThongKe";'; // Chuyển hướng đến trang /Group-2-Saturday/home sau 2 giây
                            echo '}, 1000);'; // Thời gian chờ là 2000ms (tức là 2 giây)
                            echo '</script>';
                            die();
                        }
                    } else {
                        echo '<div class="error-box" style="background-color: #FFF0F0; color: #e71a0f; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
                        echo 'Tài khoản đã tạm khoá, vui lòng thử lại sau';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="error-box" style="background-color: #FFF0F0; color: #e71a0f; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
                    echo 'Thông tin đăng nhập không đúng';
                    echo '</div>';
                }
            } else {
                echo '<div class="error-box" style="background-color: #FFF0F0; color: #e71a0f; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
                echo 'Thông tin đăng nhập không đúng';
                echo '</div>';
            }
        }

        ?>
        <form action="" method="post">
            <div class="inputBox">
                <input id="uname" type="text" name="Username" placeholder="Email hoặc số điện thoại" required>
                <input id="pass" type="password" name="Password" placeholder="Password" required>
            </div>
            <input type="submit" name="Login" value="Login" id="loginButton">
            <a href="resetpassword">Forgot Password<br> </a>
            <div class="text-center">
                <a href="SignUp" class="signup-link">Sign-Up<br></a>
            </div>
            
                <a href="Home" class="signup-link">Trở về<br></a>
           
        </form>
    </div>
</body>

</html>