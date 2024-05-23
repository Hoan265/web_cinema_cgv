<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

require_once './mvc/Controllers/Account.php';
$accountController = new Account();
$email = $_SESSION['email_client']; 
$user = $accountController->getUserByEmail($email);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $sex = $_POST['sex'];
        $birthday = $_POST['date'];
        $accountController->updateInfo($email, $phone, $name, $birthday, $sex);
        $success_message = "Cập nhật thông tin thành công !!!!";
        echo '<script>';
        echo 'setTimeout(function() {';
        echo '    window.location.href = "/Group-2-Saturday/ProfileCustomer";'; // Chuyển hướng đến trang /Group-2-Saturday/home sau 2 giây
        echo '}, 1000);'; // Thời gian chờ là 2000ms (tức là 2 giây)
        echo '</script>';
    }

    if (isset($_POST['confirm-update'])) {
        if (isset($_FILES['new_avatar']) && $_FILES['new_avatar']['error'] === UPLOAD_ERR_OK) {
            $newAvatar = $_FILES['new_avatar'];
            $tmpName = $newAvatar['tmp_name'];
            $imageData = file_get_contents($tmpName);

            if (!empty($imageData)) {
                $imageSize = strlen($imageData);
                echo "Kích thước dữ liệu hình ảnh: $imageSize bytes";
                if ($accountController->updateAvatar($email, $imageData)) {
                    $success_message = "Cập nhật ảnh đại diện thành công";
                    echo '<script>';
                    echo 'setTimeout(function() {';
                    echo '    window.location.href = "/Group-2-Saturday/ProfileCustomer";'; 
                    echo '}, 1000);'; 
                    echo '</script>';
                } else {
                    echo "Cập nhật thất bại.";
                }
            } else {
                echo "Có lỗi xảy ra khi tải lên hình ảnh mới.";
            }
        } else {
            echo "Có lỗi xảy ra khi tải lên hình ảnh mới.";
        }
    }

    if (isset($_POST['savePassword'])) {
        $password = $_POST['oldPassword'];
        $newpass = $_POST['newPassword'];
        $confirmpass = $_POST['confirmNewPassword'];
        $oldPass = $accountController->getOldPass($email);

        if (password_verify($password, $oldPass)) {
            if ($newpass == $confirmpass) {
                if ($accountController->updatePass($email, $newpass)) {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Profile Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style type="text/css">
.avatar {
    width: 200px;
    height: 200px;
}
</style>
</head>
<body>
<div class="container bootstrap snippets bootdey">
<h1 style="margin-top: 40px; color: white;">Thông tin cá nhân</h1>
    <form action="" method="post" enctype="multipart/form-data">
    <hr>
    <div class="row">
    <div class="col-md-3">
    <div class="text-center" style="position: relative;">
    <?php 
        $avatarData = $user->getAvatar(); 
        if ($avatarData) {
            $avatarBase64 = base64_encode($avatarData);
            $avatarSrc = 'data:image/jpeg;base64,' . $avatarBase64; 
        } else {
            $avatarSrc = './assets/img/default_avatar.jpg';
        }
        echo '<img id="avatar_img" class="avatar" src="' . $avatarSrc . '" alt="">';
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="new_avatar" id="new_avatar" style="display: none;" accept=".jpg, .jpeg, .png, .svg">
        <label for="new_avatar" class="change-avatar-btn">
            <img src="./assets/img/camera.png" alt="Change Avatar">
        </label>
        <span id="avatar_name" style="display: none;"></span>
        <input type="hidden" id="avatar_name_input" name="avatar_name">
        <button id="confirm-update" type="submit" style="display:none;" name='confirm-update' class="confirm-update-btn">Xác nhận</button>
    </form>
    </div>
</div>

</form>
<div class="col-md-9 personal-info" style="background-color: #2c2c2c;">
<h3 class="black-text">Chi tiết</h3>
<?php
if (isset($success_message)) {
    echo '<div class="success-msg">' . $success_message . '</div>';
}
?>
<?php if (isset($error_message)) {
    echo '<div class="error-msg">' . $error_message . '</div>';
} ?>
<form action="" method="post">
    <div class="form-group">
        <label class="col-lg-3 control-label">Tên:</label>
        <div class="col-lg-8">
            <input class="form-control" type="text" name="name" value="<?php echo $user->getName(); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Email:</label>
        <div class="col-lg-8">
            <input class="form-control" type="text" name="email" readonly value="<?php echo $_SESSION['email_client']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Số điện thoại:</label>
        <div class="col-lg-8">
            <input class="form-control" type="text" name="phone" value="<?php echo $user->getPhone(); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Giới tính:</label>
        <div class="col-lg-8">
            <select class="form-control" name="sex">
                <option value="Nam" <?php if ($user->getSex() === 'Nam') echo 'selected'; ?>>Nam</option>
                <option value="Nữ" <?php if ($user->getSex() === 'Nữ') echo 'selected'; ?>>Nữ</option>
                <option value="Khác" <?php if ($user->getSex() === 'Khác') echo 'selected'; ?>>Khác</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label">Ngày sinh:</label>
        <div class="col-lg-8">
            <input class="form-control" type="date" name="date" value="<?php echo date_format(new DateTime($user->getBirthday()), 'Y-m-d'); ?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-8">
            <button type="submit" class="btn btn-primary" name="update">Cập nhật</button> 
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#changePasswordModal">Đổi mật khẩu</button>
        </div>
    </div>
</form>            

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel" style="color: black;">Đổi mật khẩu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="oldPassword">Mật khẩu cũ:</label>
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Mật khẩu mới:</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmNewPassword">Xác nhận mật khẩu mới:</label>
                        <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="savePassword">Lưu</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<hr>
</body>
</html>
<style type="text/css">
body {
    background-image: url('./assets/img/bg/movie_details_bg.jpg');
}
.avatar {
    width: 200px;
    height: 200px;
}
.confirm-update-btn {
    position: absolute;
    bottom: -80px; 
    right: 0; 
    background-color: #ffff;
    right: 85px;
    color: black;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.confirm-update-btn:hover {
    background-color: #2c2c2c;
    color: white;
}
.change-avatar-btn {
    position: absolute; 
    bottom: -20px; 
    left: 60%;
    transform: translateX(-5%); 
    cursor: pointer; 
    transition: color 0.3s ease; 
    z-index: 1;
}

.change-avatar-btn img {
    width: 40px; 
    height: 40px; 
}
.success-msg {
    font-weight: bold;
    color: #006400; 
    margin-bottom: 20px;
    background-color: #aeffaeec; 
    padding: 5px;
    border-radius: 2px; 
    text-align: center;
    margin-bottom: 10px;
}

.error-msg {
    font-weight: bold;
    color: #ff0000; 
    margin-bottom: 20px; 
    background-color: #ffdad5ef; 
    padding: 5px; 
    border-radius: 2px; 
    text-align: center;
    margin-bottom: 10px; 
}
.black-text {
    text-align: center;
    margin-top: 40px; 
}
</style>
<script>
document.getElementById('new_avatar').addEventListener('change', function() {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('avatar_img').src = event.target.result;
            document.getElementById('avatar_name').textContent = file.name;
            document.getElementById('avatar_name_input').value = file.name;
            document.getElementById('confirm-update').style.display = 'inline'; 
        };
        reader.readAsDataURL(file);
    }
});

function showMessage(messageElement) {
    messageElement.style.display = 'block';
    setTimeout(function() {
        messageElement.style.display = 'none';
    }, 4000);
}

<?php if (isset($success_message)) { ?>
    var successMessage = document.querySelector('.success-msg');
    showMessage(successMessage);
<?php } ?>

<?php if (isset($error_message)) { ?>
    var errorMessage = document.querySelector('.error-msg');
    showMessage(errorMessage);
<?php } ?>
 document.addEventListener("DOMContentLoaded", function() {
            var successMessage = document.getElementById("success-message");
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = "none";
                }, 5000); 
            }
        });
</script>
