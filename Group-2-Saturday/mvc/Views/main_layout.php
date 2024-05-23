




<?php 
    if($data['PageRole']=='admin'){
        include "inc/admin/config.php" ;
        require "inc/admin/head.php" ;
    }
     else {
        require "inc/client/head.php" ;
    }
    
?>

<?php 
// session_start(); 
include "mvc/Core/database_account.php";

?>


<?php 
// include "inc/admin/config.php" 
?>
<?php 
// require "inc/admin/head.php" 
?>

<body>
    <?php
    // session_start();
    // $check_film = "false";
    // // Kiểm tra xem có tín hiệu để hiển thị trang Quản Lý Phim không
    // if (isset($_SESSION['show_manage_movie']) && $_SESSION['show_manage_movie'] === true) {
    //     // Hiển thị trang Quản Lý Phim trong nội dung
    //     $url_film="/mvc/Views/admin/QuanLyPhim.php";
    //     echo '<script>
    //             function loadContent('.$url_film.') {
    //                 $.get('.$url_film.', function(data) {
    //                     $("#main-container").html(data);
    //                 });
    //             }
    //         </script>';
    //     // Xóa biến session sau khi đã sử dụng
    //     unset($_SESSION['show_manage_movie']);
    // } else {
    //     // Hiển thị nội dung mặc định hoặc trang chính
    //     // Nếu không có tín hiệu hiển thị trang Quản Lý Phim
    // }
    ?>

    <!-- Page Container -->
    <div id="page-container" class="sidebar-o sidebar-light enable-page-overlay side-scroll page-header-fixed main-content-narrow remember-theme">
        <?php
        if($data['PageRole']=='admin'){
            include "inc/admin/navbar.php";
            include "inc/admin/header.php";
        } else if($data['PageRole']=='login'){
            include "./mvc/Views/client/Login.php";
        }else if($data['PageRole']=='signup'){
           include  "./mvc/Views/client/SignUp.php";
        }else if($data['PageRole']=='resetpassword'){
            include  "./mvc/Views/client/ResetPassword.php";
         }else if($data['PageRole']=='otp'){
            include  "mvc/Views/client/OTP.php";
         }else if($data['PageRole']=='otpreset'){
            include  "mvc/Views/client/OTP_ResetPassword.php";
         }else if($data['PageRole']=='createnewpassword'){
            include  "mvc/Views/client/mvc/Views/client/CreateNewPassword.php";
         }else if($data['PageRole']=='profile'){
            include  "mvc/Controllers/ProfileCustomer.php";
         }
        else{
            include 'inc/client/head.php';
           include 'inc/client/navbar.php';
        }
        ?>
        <!-- Main Container -->
        <main id="main-container" ">
        <!-- Page Content -->
        <?php include "./mvc/Views/".$data['PageRole'] ."/". $data['Page'].".php" ?>
        <!-- END Page Content -->
        </main>
        <?php 
        if($data['PageRole']=='admin'){
            include "inc/admin/script.php";
            include "inc/admin/footer.php" ;
        } else if($data['PageRole']=='login'){
            include "./mvc/Views/client/Login.php";
        }else if($data['PageRole']=='signup'){
            include  "./mvc/Views/client/SignUp.php";
         }else if($data['PageRole']=='resetpassword'){
            include  "mvc/Views/client/ResetPassword.php";
         }else if($data['PageRole']=='otp'){
            include  "mvc/Views/client/OTP.php";
         }else if($data['PageRole']=='otpreset'){
            include  "mvc/Views/client/OTP_ResetPassword.php";
         }else if($data['PageRole']=='createnewpassword'){
            include  "mvc/Views/client/CreateNewPassword.php";
         }else if($data['PageRole']=='profile'){
            include  "mvc/Controllers/ProfileCustomer.phpp";
         }
        else{
            include "inc/client/script.php";
            include 'inc/client/footer.php';
        }
        ?>
    </div> 
    <script>
    // $(document).ready(function(){
    //     $('.nav-main-link').click(function(e){
    //         e.preventDefault();
    //         var url = $(this).attr('href');
    //         loadContent(url);
    //     });

    //     function loadContent(url) {
    //         $.get(url, function(data) {
    //             $('#main-container').html(data);
    //         });
    //     }
    // });
</script>


    <!-- END Page Container -->
</body>

</html>