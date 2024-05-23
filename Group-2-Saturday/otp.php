<?php  
session_start();
require 'vendor/autoload.php';
require_once "mvc/Controllers/AccountController.php";

use PHPMailer\PHPMailer\PHPMailer;
 $accountController =new AccountController();
function generateRandomCode() {
    return str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
}
function sendmail($email) {
    $code = generateRandomCode();
    $_SESSION['otp_code'] = $code;
    $mail = new PHPMailer(true); 
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'myylyymecafe@gmail.com';
    $mail->Password = "lokuwdebqammhmnv";
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $fromEmail = 'myylyymecafe@gmail.com'; 
    $fromName = 'RAP CHIEU PHIM'; 
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Ma Xac Nhan";
    $mail->Body = "Ma xac nhan cua ban la: $code";
    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}
if(isset($_POST["Resend"])){
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
        if(sendmail($email)){
            header("Location: otp.php");
            exit;
        } else {
            echo "Gửi email không thành công. Vui lòng thử lại sau.";
        }
    } else {
        echo "Không tìm thấy địa chỉ email. Vui lòng thử lại sau.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/otp.css">
    <style>
        .error {
            color: #e71a0f; 
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="confirmOtpBox">
        <h3>Xác nhận OTP</h3>
        <p>Vui lòng nhập mã OTP đã được gửi đến email của bạn.</p>
        <?php
        if(isset($_POST['otp'])) {
            $otp = $_POST['otp'];
            $sent_otp = $_SESSION['otp_code'];
            if($otp == $sent_otp){
               header("Location: mvc/Views/client/login.php");
               $id =  $accountController->generateUniqueID();
               $name = $_SESSION['name'];
                $email = $_SESSION['email'];
                $phone = $_SESSION['phone'];
                $password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);
                $birthday = $_SESSION['birthday'];
                $sex = $_SESSION['sex'];
                 $avatar = 'user.png';
                $role = "client";
                $accountController->CreateAccount($id,$email,$phone,$password,$name,$sex,$avatar,$birthday,$role);
                $accountController->CreateClient($id);
                exit; 
            } else {
                echo '<p class="error" style="color: #e71a0f;">Mã OTP không hợp lệ!</p>';
            }
        }
        ?>
        <form action="otp.php" method="post">
            <div class="inputBox">
                <input type="text" name="otp" placeholder="OTP">
            </div>
            <input type="submit" value="Confirm">
            <input class ="btnresend" type="submit" value="Resend" name="Resend" style="background-color: transparent; color: #ffd21f;; ">
        </form>
    </div>
</body>
</html>