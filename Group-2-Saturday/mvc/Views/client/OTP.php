<?php  
session_start();

 require './vendor/autoload.php';
 use PHPMailer\PHPMailer\PHPMailer;
 require_once './mvc/Controllers/Account.php';
 require_once './mvc/Models/AccountModel.php';
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
if(isset($_POST["Resend"])){
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
        if(sendmail($email)){
            echo '<script>';
            echo 'window.location.href = "/Group-2-Saturday/OTP";'; // Chuyển hướng đến trang OTP
            echo '</script>';
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
    <link rel="stylesheet" href="./assets/css/otp.css">
    <style>
        .error {
            color: #e71a0f; 
            font-size: 14px;
            margin-top: 10px;
        }
        body {
            background: url(./assets/img/bg/bg_web1.webp) no-repeat !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            overflow: hidden !important;
        }
    </style>
</head>
<body>
<?php
    if (isset($success_message)) {
        echo '<div class="success-msg">' . $success_message . '</div>';
    }
    ?>
    <?php if (isset($error_message)) {
        echo '<div class="error-msg">' . $error_message . '</div>';
    } ?>
    <div class="confirmOtpBox">
        <h3>Xác nhận OTP</h3>
        <p>Vui lòng nhập mã OTP đã được gửi đến email của bạn.</p>
        <?php
       if(isset($_POST['otp'])) {
        $otp = $_POST['otp'];
        $sent_otp = $_SESSION['otp_code'];
        if($otp == $sent_otp){
            // Lấy thông tin từ session
            $id = $accountController->generateUniqueID();
            $name = $_SESSION['name'];
            $email = $_SESSION['email'];
            $phone = $_SESSION['phone'];
            $password = $_SESSION['password'];
            $birthday = $_SESSION['birthday'];
            $sex = $_SESSION['sex'];
            $role = "client";
            $block = "Off";
    
            // Đường dẫn tới thư mục chứa ảnh mặc định
            $defaultAvatarLink = '/Applications/XAMPP/xamppfiles/htdocs/Group-2-Saturday/assets/img/avatarUser/user.png';
    
            // Kiểm tra sự tồn tại và quyền truy cập của file ảnh mặc định
            if (!empty($defaultAvatarLink) && is_readable($defaultAvatarLink)) {
                $avatarData = file_get_contents($defaultAvatarLink);
                if ($avatarData === false) {
                    echo '<p class="error" style="color: #e71a0f;">Không thể đọc nội dung file ảnh mặc định!</p>';
                    // Sử dụng một giá trị mặc định khác cho avatar
                    $defaultAvatarLink = '/Applications/XAMPP/xamppfiles/htdocs/Group-2-Saturday/assets/img/avatarUser/default_avatar.png';
                    $avatarData = file_get_contents($defaultAvatarLink);
                    if ($avatarData === false) {
                        echo '<p class="error" style="color: #e71a0f;">Không thể đọc được file ảnh mặc định thứ hai!</p>';
                        $avatarData = '';
                    }
                }
            } else {
                echo '<p class="error" style="color: #e71a0f;">Đường dẫn file ảnh mặc định không hợp lệ hoặc không thể truy cập được!</p>';
                // Thiết lập giá trị mặc định cho avatar thành chuỗi rỗng
                $avatarData = ''; 
            }
    
            // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
            if ($accountController->checkEmailExists($email)) {
                echo '<p class="error" style="color: #e71a0f;">Email đã tồn tại trong cơ sở dữ liệu!</p>';
            } else {
                // Thêm tài khoản mới
                $accountController->addAccount($id, $email, $phone, $password, $name, $sex, $avatarData, $birthday, $role, $block);
                // Tạo khách hàng mới
                $accountController->CreateClient($id);
                echo '<div class="success-box" style="background-color: #FFF0F0; color: yellowgreen; padding: 1em 1em; border-radius: 5px; margin: 0 0 1.5em;">';
                echo 'Bạn đã đăng ký thành công tài khoản, vui lòng đăng nhập để sử dụng hệ thống';
                echo '</div>';
                echo '<script>';
                echo 'setTimeout(function() {';
                echo 'window.location.href = "/Group-2-Saturday/Login";';  // Chuyển hướng đến trang đăng nhập
                echo '}, 7000);';
                echo '</script>';
            }
        } else {
            echo '<p class="error" style="color: #e71a0f;">Mã OTP không hợp lệ!</p>';
        }
    }
    
        ?>
        <form action="" method="post">
            <div class="inputBox">
                <input type="text" name="otp" placeholder="OTP">
            </div>
            <input type="submit" value="Confirm">
            <input class ="btnresend" type="submit" value="Resend" name="Resend" style="background-color: transparent; color: #ffd21f;; ">
        </form>
    </div>
</body>
</html>
<script>
    // Hiển thị thông báo thành công hoặc thông báo lỗi trong 5 giây
    function showMessage(messageElement) {
        messageElement.style.display = 'block';
        setTimeout(function() {
            messageElement.style.display = 'none';
        }, 4000); // 5 giây
    }

    // Hiển thị thông báo thành công
    <?php if (isset($success_message)) { ?>
        var successMessage = document.querySelector('.success-msg');
        showMessage(successMessage);
    <?php } ?>

    // Hiển thị thông báo lỗi
    <?php if (isset($error_message)) { ?>
        var errorMessage = document.querySelector('.error-msg');
        showMessage(errorMessage);
    <?php } ?>
</script>