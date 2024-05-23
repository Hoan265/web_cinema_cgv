<?php
session_start(); 
require_once './mvc/Controllers/Account.php';
$accountController = new Account();
$email = $_SESSION['email_client']; 
$user = $accountController->getUserByEmail($email);

$isLoggedIn = isset($_SESSION['email_client']); // Kiểm tra xem người dùng đã đăng nhập hay chưa

// Nếu đã đăng nhập, lấy tên người dùng từ session
$name = isset($_SESSION['name_client']) ? $_SESSION['name_client'] : "Đăng Nhập";

function setNameToLogin() {
    $_SESSION['name_client'] = "Đăng Nhập";
}

if (isset($_POST["btnlogout"])) {
    // Huỷ bỏ hết session và đặt $name thành "Đăng Nhập"
    if(isset($_SESSION['email_client'])) {
        $email = $_SESSION['email_client'];
        // Hủy session người dùng
        unset($_SESSION['email_client']);
        unset($_SESSION['name_client']);
    }
    $name = "Đăng Nhập";
    echo '<script>';
            echo 'window.location.href = "/Group-2-Saturday/home";'; // Chuyển hướng đến trang OTP
            echo '</script>';
}

// session_start(); 
// require_once './mvc/Controllers/Account.php';
// $accountController = new Account();

// // Kiểm tra xem session của client và admin có tồn tại không
// $isClientLoggedIn = isset($_SESSION['email_client']);
// $isAdminLoggedIn = isset($_SESSION['email_admin']);

// // Lấy email của client và admin từ session nếu tồn tại
// $emailClient = $isClientLoggedIn ? $_SESSION['email_client'] : null;
// $emailAdmin = $isAdminLoggedIn ? $_SESSION['email_admin'] : null;

// // Lấy thông tin user nếu session client tồn tại
// $user = $isClientLoggedIn ? $accountController->getUserByEmail($emailClient) : null;

// // Kiểm tra xem người dùng đã đăng nhập hay chưa
// if ($isClientLoggedIn) {
//     $name = $user->getName(); // Nếu đã đăng nhập, lấy tên từ đối tượng $user
// } elseif ($isAdminLoggedIn) {
//     $name = "Admin"; // Nếu là admin đã đăng nhập, đặt tên là "Admin"
// } else {
//     $name = "Đăng Nhập"; // Nếu chưa đăng nhập, đặt tên là "Đăng Nhập"
// }

// // Hàm đặt tên khi đăng nhập
// function setNameToLogin() {
//     $_SESSION['name_client'] = "Đăng Nhập";
// }

// // Xử lý đăng xuất
// // Xử lý đăng xuất
// if (isset($_POST["btnlogout"])) {
//     // Kiểm tra và hủy session của client nếu tồn tại
//     if ($isClientLoggedIn) {
//         unset($_SESSION['email_client']);
//         unset($_SESSION['name_client']);
//     } else {
//         // Kiểm tra và hủy session của admin nếu tồn tại
//         if ($isAdminLoggedIn) {
//             unset($_SESSION['email_admin']);
//             unset($_SESSION['name_admin']);
//         }
//     }
//     $name = "Đăng Nhập";
//     echo '<script>';
//     echo 'window.location.href = "/Group-2-Saturday/Login";'; // Chuyển hướng đến trang đăng nhập
//     echo '</script>';
// }
function getActive()
{
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);
    return $components[2];
}
$current_page = getActive();
?>

<!-- navbar -->
<button class="scroll-top scroll-to-target" data-target="html">
    <i class="fas fa-angle-up"></i>
</button>
<header class="header-style-two">
    <div id="sticky-header" class="menu-area">
        <div class="container custom-container">
            <div class="row">
                <div class="col-12">
                    <div class="mobile-nav-toggler"><i class="fas fa-bars"></i></div>
                    <div class="menu-wrap">
                        <nav class="menu-nav show">
                            <div class="logo" style="width: 10%;">
                                <a href="./home">
                                    <img src="assets/img/logo/logo.png" alt="Logo" style="width: 100%;">
                                </a>
                            </div>
                            <div id="save-id-movie" data-id="" hidden></div>
                            <div class="navbar-wrap main-menu d-none d-lg-flex" id="menu">
                                <ul class="navigation">
                                    <li class="menu-item-has-children <?php echo $current_page == "home" ? " active" : ""; ?>"><a href="./home">TRANG CHỦ</a>
                                    </li>
                                    <li class="menu-item-has-children <?php echo $current_page == 'list_movie' ? " active" : ""  ?>">
                                        <a href="./list_movie" data-click="">PHIM</a>
                                        <ul class="sub-menu" id="submenu">
                                            <li><a href="./list_movie?upcoming_movies" data-click="" class="level1" style="<?php echo $chitiet == "upcoming_movies" ? " color: yellow" : ""; ?>">Phim Sắp Chiếu</a></li>
                                            <li><a href="./list_movie?current_movies" data-click="" class="level1 " style="<?php echo $chitiet == "current_movies" ? " color: yellow" : ""; ?>">Phim Đang Chiếu</a></li>
                                        </ul>

                                    </li>
                                    <li class="menu-item-has-children <?php echo ($current_page == 'infocustomer' || $current_page == 'membership') ? " active" : ""  ?>">
                                        <a href="#">THÀNH VIÊN</a>
                                        <ul class="sub-menu" id="submenu">
                                            <li class="nav-1-1 first"><a href="infocustomer" class="level1 ">Tài khoản CGV</li>
                                            <li class="nav-1-2 active last"><a href="membership" class="level1 ">Quyền lợi</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children <?php echo $current_page == 'voucher' ? " active" : ""  ?>"><a href="./voucher">KHUYẾN MÃI</a></li>
                                    <?php
                                    if ($name!= "Đăng Nhập") {
                                        if ($current_page == 'myticket') {
                                            echo '<li class="menu-item-has-children active"><a href="./myticket">VÉ CỦA TÔI</a></li>';
                                        } else {
                                            echo '<li class="menu-item-has-children"><a href="./myticket">VÉ CỦA TÔI</a></li>';
                                        }
                                    } else {
                                        // echo '<li class="menu-item-has-children"><a href="./Login">VÉ CỦA TÔI</a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="header-action d-none d-md-block">
                                <ul>
                                    <li class="d-none d-xl-block">
                                        <div class="footer-search">
                                            <form action="" method="post">
                                                <input type="text" name="search-input" id="search-input" placeholder="Tìm kiếm phim...">
                                                <button type="submit"><i class="fa fa-search"></i></button>
                                            </form>
                                        </div>
                                    </li>
                                    <!-- <li class="header-btn"><a href="mvc/Views/client/login.php" class="btn">ĐĂNG NHẬP</a></li> -->
                                    <form action="" method="post">
                                        <li class="header-btn">
                                        <?php if ($name !== "Đăng Nhập" && !isset($_POST["btnlogout"])) { ?>
                                                <!-- Hiển thị tên người dùng và dropdown menu -->
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo $name; ?>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="infocustomer">Tài khoản</a>
                                                        <button class="dropdown-item btnlogout" type="submit" name="btnlogout">Đăng xuất</button>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <a href="Login" class="btn"><?php echo $name; ?></a>
                                            <?php } ?>
                                        </li>
                                    </form>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- navbar -->
</header>
<style>
    .menu-wrap {
        display: flex;
        /* align-items: center; */
        height: 80px;
        /* flex-wrap: nowrap; */
    }

    .menu-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        flex-direction: column;
        /* flex-wrap: nowrap; */
    }

    /* Căn giữa logo */
    .logo {
        flex: 0 0 auto;
    }

    /* Căn giữa menu */
    .navbar-wrap {
        flex: 1;
    }

    /* Căn giữa các phần tử con trong menu */
    .navigation {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }



    /* Định dạng các mục menu */
    .navigation li a {
        color: #f0f0f0;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.3s ease-in-out;
    }

    /* .navigation li a:hover {
        color: #ff6600;
    } */

    /* Định dạng phần tìm kiếm */
    /* .footer-search {
        flex: 0 0 auto;
    }

    .footer-search input {
        padding: 8px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
    }

    .footer-search button {
        background-color: #ff6600;
        color: #fff;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
    } */

    /* Định dạng phần user và logout */
    /* .header-btn a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        margin-right: 15px;
        transition: color 0.3s ease-in-out;
    }

    .header-btn a:hover {
        color: #ff6600;
    } */

    /* .btn {
        background-color: #ff6600;
        color: #fff;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .btn:hover {
        background-color: #333;
        color: #fff;
    } */
    .user-menu {
        list-style: none;
        padding: 0;
        margin: auto 35px;
    }

    .header-btn {
        display: inline-block;
        position: relative;
    }

    .header-btn:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: transparent;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 0px;
        z-index: 2;
    }

    .dropdown-menu li {
        display: block;
        margin: 5px auto;
    }

    .dropdown-menu a {
        display: block;
        padding: 8px 10px;
        text-decoration: none;
        color: #333;
    }

    .dropdown-menu a:hover {
        background-color: #f0f0f0;
    }

    .btnlogout {
        color: crimson !important;
        text-decoration: none !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        margin-left: 10px !important;
    }

    .btnlogout:hover {
        color: #333 !important;
    }

    .dropdown-item:hover {
        background-color: yellow !important;
        color: black !important;
    }

    .dropdown-item {
        color: white !important;
        /* Màu chữ trắng */
        border: 1px solid yellow !important;
        /* Viền màu vàng */
    }
</style>