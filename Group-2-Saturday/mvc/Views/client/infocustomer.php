<?php 
require_once './mvc/Controllers/StaffController.php';
require_once './mvc/Controllers/RoleStaffController.php';
require_once './mvc/Controllers/Account.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$staffController = new StaffController();
$accountController = new Account();
$roleController = new RoleStaffController();
$email = $_SESSION['email_client']; 
$user = $accountController->getUserByEmail($email);
$successDisplayed = false; 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm-update'])) {
    $newAvatar = $_FILES['new_avatar']; 
    if ($newAvatar['error'] === UPLOAD_ERR_OK) {
        $tmpName = $newAvatar['tmp_name']; 
        $imageData = file_get_contents($tmpName); 
        if (!empty($imageData)) {
            if ($accountController->updateAvatar($email, $imageData)) {
                $successDisplayed = true;
                echo '<script>';
                echo 'setTimeout(function() {';
                echo '    window.location.href = "/Group-2-Saturday/infocustomer";'; // Chuyển hướng đến trang /Group-2-Saturday/home sau 2 giây
                echo '}, 1000);'; // Thời gian chờ là 2000ms (tức là 2 giây)
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
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            color: #fff;
        }

        .container.infomation {
            max-width: 1200px;
            margin: 70px auto;
            background-color: #2c2c2c;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title h3 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            /* color: #ff5733; */
        }

        hr {
            border: 1px solid #4d4d4d;
            margin: 20px 0;
        }

        .list-group {
            margin: 0;
            padding: 0;
        }

        .list-group-item {
            background-color: whitesmoke;
            border: none;
            border-radius: 0;
            color: black;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #4d4d4d;
            color: white;
        }

        .list-group-item.active {
            font-weight: bold;
            background-color: #007bff;
            color: red;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
        }

        .author {
            margin-top: 20px;
            text-align: center;
        }

        .avatar img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .info {
            margin-top: 20px;
        }

        .active-on {
            color: #fff;
            background-color: #4d4d4d;
        }

        .info h4 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .info h5 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .info p {
            font-size: 18px;
            margin-bottom: 5px;
        }

        body {
            background-image: url('./assets/img/bg/movie_details_bg.jpg');
        }

        .change-avatar-btn {
            position: absolute; 
            bottom: 185px; 
            left: 54%;
            transform: translateX(-5%); 
            color: #808080; 
            cursor: pointer; 
            transition: color 0.3s ease; 
            z-index: 1;
        }

        .change-avatar-btn img {
            width: 30px; 
            height: 30px; 
        }

        .confirm-update-btn {
            position: absolute;
            bottom: 10px; 
            right: 0; 
            background-color: #fff;
            color:#ffd21f;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-update-btn:hover {
            background-color:#ffd21f;
            color:#fff;
        }

        #success-message {
            color: #28a745; 
            margin-left: 10px; 
            font-size: 16px;
        }

    </style>
</head>
<body>
    <div class="container infomation">
        <div class="title">
            <h3>Thông Tin Tài Khoản </h3>
        </div>
        <hr>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-3 col-sm-3 col-12" >
                    <div class="list-group">
                        <a href="infocustomer" class="active-on list-group-item list-group-item-action info">THÔNG TIN CHUNG</a>
                        <a href="ProfileCustomer" class="list-group-item list-group-item-action history">CHI TIẾT TÀI KHOẢN</a>
                        <!-- <a href="updatepassword" class="list-group-item list-group-item-action changeInfo">ĐỔI MẬT KHẨU</a> -->
                        <a href="" class="list-group-item list-group-item-action changePass">ĐIỂM THƯỞNG</a>
                        <a href="" class="list-group-item list-group-item-action changePass">LỊCH SỬ GIAO DỊCH</a>
                    </div>
                </div>
            
                <div class="col-md-9 col-sm-9 col-12">
                    <div class="title">
                        <h3>THÔNG TIN CHUNG
                        <?php if($successDisplayed) { echo '<span id="success-message">Cập nhật ảnh đại diện thành công</span>'; } ?>
                        </h3>
                    </div>

                    <div class="author">
                        <div class="avatar">
                            <?php 
                            $avatarData = $user->getAvatar(); 
                            if ($avatarData) {
                                $avatarBase64 = base64_encode($avatarData);
                                $avatarSrc = 'data:image/jpeg;base64,' . $avatarBase64; 
                            } else {
                                $avatarSrc = './assets/img/default_avatar.jpg'; // Thay đổi đường dẫn đến hình ảnh mặc định của bạn
                            }
                            echo '<img id="avatar_img" class="avatar" src="' . $avatarSrc . '" alt="">';
                            ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="file" name="new_avatar" id="new_avatar" style="display: none;" accept=".jpg, .jpeg, .png, .svg">
                                <label for="new_avatar" class="change-avatar-btn">
                                    <img src="./assets/img/camera.png" alt="Change Avatar">
                                </label>
                                <span id="avatar_name" style="display: none;"></span>
                                <input type="hidden" id="avatar_name_input" name="avatar_name" >
                                <button id="confirm-update" type="submit" style="display:none;" name ='confirm-update'  class="confirm-update-btn">Xác nhận</button>
                            </form>
                        </div>
                    </div>
                    <div class="info">
                        <h4><strong>Thông tin liên hệ</strong></h4>
                        <h6>Liên hệ</h6>
                        <p>Tên:   <?php
                               $name=  $user->getName();
                               echo $name;
                                $_SESSION['name']=$name
                                ?>  </p>
                        <p>Email:  <?php echo  $_SESSION['email_client']; ?>  </p>
                        <p>Số điện thoại : 
                            <?php 
                            $phone= $user->getPhone();
                            $_SESSION['phone']=$phone ;
                           echo $phone;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

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
    </script>
     <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successMessage = document.getElementById("success-message");
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = "none";
                }, 5000); 
            }
        });
    </script>
</body>
</html>
