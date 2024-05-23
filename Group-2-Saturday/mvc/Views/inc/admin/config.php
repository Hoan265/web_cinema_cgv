<?php 
error_reporting(0);
session_start(); 
require_once './mvc/Controllers/StaffController.php';
require_once './mvc/Controllers/Account.php';

 $accountController = new Account();
$staffController = new StaffController();
$email = $_SESSION['email'] ;
$idStaff =  $accountController->findIdByEmail($email);
$roleStaff = $staffController->findRoleByIdStaff($idStaff);
?>

<?php
$GLOBALS['navbar'] = [
    array(
        'name'  => 'Thống kê',
        'icon'  => 'fas fa-chart-line',
        'url'   => 'ThongKe',
        'role' => 'thongke'
    ),
    array(
        'name'  => 'Quản lý',
        'type'  => 'heading',
        'navbarItem' => [
            array(
                'name'  => 'Thống kê',
                'icon'  => 'fas fa-chart-line',
                'url'   => 'ThongKe',
                'role' => 'thongke'
            ),
            array(
                'name'  => 'Quản lý phim',
                'icon'  => 'fas fa-film',
                'url'   => 'QuanLyPhim',
                'role' => 'phim'
            ),
            array(
                'name'  => 'Quản lý lịch chiếu',
                'icon'  => 'fas fa-calendar-alt',
                'url'   => 'QuanLyLichChieu',
                'role' => 'lichchieu'
            ),
            array(
                'name'  => 'Quản lý nhân viên',
                'icon'  => 'fa fa-user-friends',
                'url'   => 'Account?page=1',
                'role' => 'QLHT'
            ),
            array(
                'name'  => 'Quản lý phòng và ghế',
                'icon'  => 'fas fa-door-open',
                'url'   => 'QuanLyPhongChieu',
                'role' => 'phongvaghe'
            ),
            array(
                'name'  => 'Quản lý đặt vé',
                'icon'  => 'fa fa-person-harassing',
                'url'   => 'Ticket?page=1',
                'role' => 'qldatve'
            ),
            array(
                'name'  => 'Quản lý đồ ăn',
                'icon'  => 'fas fa-pizza-slice',
                'url'   => 'Foods?page=1',
                'role' => 'doan'
            ), 
            array(
                'name'  => 'Quản lý khách hàng',
                'icon'  => 'fa fa-users',
                'url'   => 'Client?page=1',
                'role' => 'customer'
            ),           
           array(
               'name'  => 'Quản lý khuyến mãi',
               'icon'  => 'fa fa-gift',
               'url'   => 'promotion',
               'role' => 'khuyenmai'
           ),
        //    array(
        //     'name'  => 'Thống kê',
        //     'icon'  => 'fas fa-chart-line',
        //     'url'   => 'ThongKe',
        // ),
        ]
    ),
];
function getActiveNav() {
     $directoryURI = $_SERVER['REQUEST_URI'];
     $path = parse_url($directoryURI, PHP_URL_PATH);
     $components = explode('/',$path);
     return $components[2];
 }
 
 function build_navbar() {
     // Loại bỏ các navbar item không có trong session nhóm quyền
    //  foreach($GLOBALS['navbar'] as $key => $nav) {
    //      if(isset($nav['navbarItem'])) {
    //          foreach ($nav['navbarItem'] as $key1 => $navItem) {
    //              if(!array_key_exists($navItem['role'],$_SESSION['user_role'])) {
    //                  unset($GLOBALS['navbar'][$key]['navbarItem'][$key1]);
    //              }
    //          }
    //      }
    //  }
    //  Sau khi xoá các navbar item không có trong session nhóm quyền thì duyệt mảng tạo navbar
     $html = '';
     $current_page = getActiveNav();
     foreach($GLOBALS['navbar'] as $nav) {
         if(isset($nav['navbarItem']) && isset($nav['type']) && count($nav['navbarItem']) > 0) {
             $html .= "<li class=\"nav-main-heading\">".$nav['name']."</li>";
             foreach ($nav['navbarItem'] as $navItem) {
                 $link_name = '<span class="nav-main-link-name">' . $navItem['name'] . '</span>' . "\n";
                 $link_icon = '<i class="nav-main-link-icon ' . $navItem['icon'] . '"></i>' . "\n";
                 $html .= "<li class=\"nav-main-item\">"."\n";
                //  $html .= "<a class=\"nav-main-link".($current_page == $navItem['url'] ? " active" : "")."\" href=\"/mvc/Views/admin/".$navItem['url']."\">";
                 $html .= "<a class=\"nav-main-link".($current_page == $navItem['url'] ? " active" : "")."\" href=\"./".$navItem['url']."\">";
                 $html .= $link_icon;
                 $html .= $link_name;
                 $html .= "</a></li>\n";
             }
         }
     }
     echo $html;
 }
 // Kiểm tra quyền cho mục 'Quản lý phim'
?>