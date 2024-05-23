<?php
// // // khách hàng (Client)
//include './mvc/Views/inc/client/head.php';
//include './mvc/Views/inc/client/navbar.php';
// include './mvc/Views/pages/promotion.php';
//if (isset($_GET['action'])) {
//      $action = $_GET['action'];
//      switch ($action) {
//          case 'home':
//           include './mvc/Views/client/home.php';
//           break;
//          case 'promotion':
//           include './mvc/Views/client/promotion.php';
//           break;
//          case 'list_movie':
//           include './mvc/Views/client/list_movie.php';
//           break;
//          case 'infocustomer':
//           include './mvc/Views/client/infocustomer.php';
//           break;
//           case 'myticket':
//             include './mvc/Views/client/myticket.php';
//             break;

//          }
//      } else{
//         include './mvc/Views/client/home.php';

//      }
// // include './mvc/Views/pages/user.php';
// include './mvc/Views/inc/client/footer.php';

// admin
//include './mvc/Views/main_layout.php';

//Chạy bằng địa chỉ này http://localhost/Group-2-Saturday
error_reporting(0);
session_start();
require_once "./mvc/Bridge.php";
$myApp = new App();
?>