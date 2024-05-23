
<?php 
session_start(); 
$email = $_SESSION['email_admin'] ;
require_once './mvc/Controllers/Account.php';
$accountController = new Account();
$user=$accountController->getUserByEmail($email);


if (isset($_POST["update_session_button"])) {
    echo '<script>';
    echo 'window.location.href = "/Group-2-Saturday/ThongKe";'; // Chuyển hướng đến trang đăng nhập
    echo '</script>';
}
?> 
<?php require_once "config.php" ?>
<!-- Sidebar -->
<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
            <!-- Logo -->
            <form action="" method="post">
                <button style="border: none;background-color: rgba(255, 255, 255, 0.05) !important;" type="submit" class="fw-semibold text-white tracking-wide" name="update_session_button">
                    <span class="smini-visible">
                        <span class="opacity-75">CGV</span>
                    </span>
                    <span class="smini-hidden">
                        <span class="opacity-75">CGV</span>
                    </span>
                </button>
            </form>
            <!-- END Logo -->
            <!-- Options -->
            <div>
                <!-- Dark Mode -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle"
                    data-target="#dark-mode-toggler" data-class="far fa" onclick="Dashmix.layout('dark_mode_toggle');">
                    <i class="far fa-moon" id="dark-mode-toggler"></i>
                </button>
                <!-- END Dark Mode -->

                <!-- Close Sidebar, Visible only on mobile screens -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout"
                    data-action="sidebar_close">
                    <i class="fa fa-times-circle"></i>
                </button>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->
    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                        <i class="nav-main-link-icon fa fa-rocket"></i>
                        <span class="nav-main-link-name">Tổng quan</span>
                    </a>
                </li>
                <!-- Tạo navbar item  -->
                <!-- <li class="nav-main-heading">Phim </li>
                <li class="nav-main-item\">
                <a class="nav-main-link active">
                    <i class="nav-main-link-icon fas fa-film"></i>
                    <span class="nav-main-link-name">Danh sách phim</span>
                </a>                    
            </li> -->
                <?php build_navbar() ?>
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
<!-- END Sidebar -->