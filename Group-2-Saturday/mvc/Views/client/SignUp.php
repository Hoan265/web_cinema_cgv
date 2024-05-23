<?php
session_start();
require './vendor/autoload.php';
//require_once './mvc/Controllers/SignUp.php';
use PHPMailer\PHPMailer\PHPMailer;
require_once "./mvc/Controllers/Account.php";
require_once './mvc/Controllers/SignUp.php';

$accountController = new Account();
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
//     if (mysqli_num_rows($result) == 0) {
//         return true;
//     }else return false;
// }

if (isset($_POST["Signup"])) {
    $email = $_POST["email"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $birthday = $_POST["birthday"];
    $sex = $_POST["sex"];
    // Check if the email already exists
    if ($accountController->checkEmailExists($email)) {
        // If email exists, display error message
        echo '<div class="error-box" style="background-color: #FFF0F0; color: #e71a0f; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
        echo 'Email đã tồn tại, vui lòng chọn email khác';
        echo '</div>';
    } else {
        // If email doesn't exist, proceed with signup process
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['phone'] = $phone;
        $_SESSION['password'] = $password;
        $_SESSION['birthday'] = $birthday;
        $_SESSION['sex'] = $sex;
        // Send verification email
        if (sendmail($email)) {
            echo '<script>';
            echo 'window.location.href = "/Group-2-Saturday/OTP";'; // Chuyển hướng đến trang OTP
            echo '</script>';
            exit;
        } else {
            echo "Gửi email không thành công. Lỗi: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
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
    <link rel="stylesheet" href="./assets/css/signup.css">
</head>
<body>
    <div class="signupBox">
        <h3>Đăng Ký</h3>
        <form action="" method="post">
        <input type="hidden" name="code" value="<?php echo $code; ?>">
            <div class="inputBox">
                <input type="text" name="name" placeholder="Tên" required>
            </div>
            <div class="inputBox">
                <input type="tel" name="phone" placeholder="Số điện thoại" required  pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số">
            </div>
            <div class="inputBox">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="inputBox">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="inputBox">
                <input type="date" name="birthday" min="1944-01-01" max="2008-12-31" required >
            </div>
            <div class="inputBox">
                <select name="sex" >
                    <option value="" disabled selected>Chọn giới tính</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <input type="submit" value="Signup" name="Signup" id="signupButton">
        
        <div class="text-center">
            <span class="already-have-account">Đã có tài khoản? </span>
            <a href="Login" class="signin-link">Đăng nhập</a>
        </div>
        </form>
    </div>
</body>

</html>
