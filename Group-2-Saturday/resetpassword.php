<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/resetpassword.css">
</head>
<body>
    <div class="resetPasswordBox">
        <h3>Reset Password</h3>
        <p>Vui lòng nhập địa chỉ email và chúng tôi sẽ gửi mã OTP để đặt lại mật khẩu của bạn.</p>
    <?php
        session_start();
        require 'vendor/autoload.php';
        use PHPMailer\PHPMailer\PHPMailer;
        require_once "mvc/Controllers/AccountController.php";
        $accountController = new AccountController();
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
// function isEmailUnique($conn, $email) {
//     $query = "SELECT Email FROM Users WHERE Email = '$email'";
//     $result = mysqli_query($conn, $query);
//     // Nếu ID chưa tồn tại, trả về giá trị ID
//     if (mysqli_num_rows($result) > 0) {
//         return true;
//     }else return false;
// }
            if(isset($_POST['email'])){
                $email = $_POST["email"];
                if($accountController->checkEmailExists($email)) {
                    $_SESSION['email'] = $email;
                    sendmail($email);
                    header("Location: otp_resetpass.php");
                }else 
                echo '<p class="error" style="color: #e71a0f;">Email không đúng</p>';
}
        ?>
        <form action="resetpassword.php" method="post">
            <div class="inputBox">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <input type="submit" value="Gửi Email" nanme ='sendEmail'>
        </form>
        <a href="/mvc/Views/client/login.php">Quay lại trang đăng nhập</a>
    </div>
</body>

</html>
