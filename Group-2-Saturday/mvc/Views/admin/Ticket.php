<?php

require_once './mvc/Controllers/Bill.php';
require_once './mvc/Controllers/Foods.php';
require_once './mvc/Controllers/Foods.php';
require_once './mvc/Controllers/SizeFoodController.php';
require_once './mvc/Controllers/DetailBill.php';
require_once './mvc/Controllers/Account.php';
require_once './mvc/Controllers/TicketController.php';
require_once './mvc/Controllers/QuanLyPhim.php';
require_once './mvc/Controllers/QuanLyGhe.php';
require_once './mvc/Controllers/QuanLyPhongChieu.php';
require_once './mvc/Controllers/QuanLyLichChieu.php';
require_once './mvc/Controllers/promotion.php';



$foodsController = new Foods();
$billController = new Bill();
$details = new DetailBill();
$accountController = new Account();
$promotionController = new TicketController();
$filmController = new QuanLyPhim();
$gheController = new QuanLyGhe();
$roomController = new QuanLyPhongChieu();
$lichhchieuController = new QuanLyLichChieu();
$sizeController = new SizeFoodController();
$Promotioncontroller = new Promotion();
$current_url = $_SERVER['REQUEST_URI'];
$url_parts = parse_url($current_url);
if (isset($url_parts['query'])) {
    parse_str($url_parts['query'], $query_params);
    if (isset($query_params['id'])) {
        $id = $query_params['id'];
        $detail = $details->findByID($id);
        $film = $filmController->getFilmById($detail->getIDMovie());
        $ghe = $gheController->getChairByID($detail->getIDSeat());
        $namefood = $foodsController->setNameFood($detail->getIDFood());
        $idF = $detail->getIDFood();
        $sizefood = $sizeController->setNameSize($detail->getIDSize());
        $room = $roomController->getCinemaRoomByID($detail->getIDRoom());
        $lichchieu = $lichhchieuController->getScreeningByID($detail->getIDShowTime());
        $vitrighe = $detail->getCustomSeats();
        $tenfilm = $film->getTenPhim();
        $tenghe = $ghe->getTenGhe();
        $tenroom = $room->getTenPhong();
        $loairoom = $room->getLoaiPhong();
        $time = $lichchieu->getThoiGianChieu();
        $formattedTime = date("H:i d/m/Y", strtotime($time));
        $idBill = $detail->getIDDetailBill();
    }
    $bill_array = array();
    $selectedStatus = "";
    if (isset($query_params['status'])) {
        $selectedStatus = $query_params['status'];
        switch ($selectedStatus) {
            case 'Tất cả':
                $totalRecords = $billController->countAllBills();
                break;
            case 'Đã xử lý':
                $totalRecords = $billController->countBillsByStatus('Đã xử lý');
                break;
            case 'Chưa xử lý':
                $totalRecords = $billController->countBillsByStatus('Chưa xử lý');
                break;
            case 'Hoàn thành':
                $totalRecords = $billController->countBillsByStatus('Hoàn thành');
                break;
            default:
                $totalRecords = $billController->countAllBills();
                break;
        }
    } else {
        $totalRecords = $billController->countAllBills();
    }

    $recordsPerPage = 5;
    $totalPages = ceil($totalRecords / $recordsPerPage);
    $page = isset($query_params['page']) ? $query_params['page'] : 1;
    $offset = ($page - 1) * $recordsPerPage;
    $searched = false;

    if (isset($query_params['search'])) {
        $search_text = isset($_POST['search_txt']) ? $_POST['search_txt'] : (isset($query_params['search']) ? $query_params['search'] : '');
        if (!empty($search_text)) {
            $bills = $billController->searchBills($search_text);
            $searched = true;
            $totalRecords = count($bills);
            $recordsPerPage = 5;
            $totalPages = ceil($totalRecords / $recordsPerPage);
            $page = isset($query_params['page']) ? $query_params['page'] : 1;
            $offset = ($page - 1) * $recordsPerPage;
            $bills = array_slice($bills, $offset, $recordsPerPage);
        }
    }

    if (!$searched && isset($query_params['status'])) {
        $selectedStatus = $query_params['status'];
        switch ($selectedStatus) {
            case 'Tất cả':
                $bills = $billController->getPaginatedBills($recordsPerPage, $offset);
                break;
            case 'Đã xử lý':
                $bills = $billController->getPaginatedBillsByStatus('Đã xử lý', $recordsPerPage, $offset);
                break;
            case 'Chưa xử lý':
                $bills = $billController->getPaginatedBillsByStatus('Chưa xử lý', $recordsPerPage, $offset);
                break;
            case 'Hoàn thành':
                $bills = $billController->getPaginatedBillsByStatus('Hoàn thành', $recordsPerPage, $offset);
                break;
            default:
                $bills = $billController->getPaginatedBills($recordsPerPage, $offset);
                break;
        }
    }

    if (!$searched && !isset($query_params['status'])) {
        $bills = $billController->getPaginatedBills($recordsPerPage, $offset);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateStatus'])) {
        $newstatus = $_POST['newStatus'];
        $billController->updateStatus($id, $newstatus);
        $success_message = "Cập nhật thành công hoá đơn có id là $id";
        echo '<script>';
        echo 'setTimeout(function() {';
        echo 'window.location.href = "/Group-2-Saturday/Ticket?id=' . $id . '&status=&page='.$page.'";';
        echo '}, 2000);'; // Thời gian chờ là 1000ms (tức là 1 giây)
        echo '</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Quản lý Hóa đơn</title>
    <style>
        .tbody-container {
            max-height: 1000px;
            display: block;
            margin-bottom: -10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        thead {
            position: sticky;
            top: 0;
            background-color: darkgrey;
            color: #000;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: lightskyblue;
            color: #fff;
        }

        h2 {
            position: sticky;
            color: rgb(0, 123, 255);
            top: 0;
            background-color: #f2f2f2;
            margin-top: 0;
            margin-bottom: 0;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        .selected {
            background-color: lightsteelblue;
        }

        button {
            background-color: #0073ff;
            color: rgb(0, 0, 0);
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            border-radius: 4px;
            max-width: 200px;

        }

        button:hover {
            background-color: #0041b9;
            color: aliceblue;
            font-weight: bold;
        }

        .pagination {
            display: flex;
            justify-content: center;
            /* Canh giữa nội dung */
            align-items: center;
            /* Canh theo chiều dọc */
        }

        .pagination ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .pagination li {
            margin: 0 5px;
            /* Khoảng cách giữa các trang */
        }

        .pagination li a {
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #ccc;
            color: #333;
        }

        .pagination li a:hover {
            background-color: #f0f0f0;
        }

        .table-wrapper {
            max-width: 1700px;
            /* Đặt kích thước tối đa cho table-wrapper */
            margin: 0 auto;
            /* Canh giữa table-wrapper */
            padding: 20px;
            max-height: 800px;
            /* Tạo khoảng cách giữa table-wrapper và các phần tử bên ngoài */
        }

        .table-container {
            height: 450px;
            /* Đặt chiều cao mong muốn, ví dụ 600px */
        }

        .small-button {
            padding: 5px 10px;
            /* Điều chỉnh kích thước padding theo nhu cầu */
            font-size: 14px;
            /* Điều chỉnh kích thước chữ theo nhu cầu */
        }

        .container_search {

            margin-bottom: -20px;
            margin-right: 50px;
        }

        .reload {
            position: relative;
            top: 30px;
            /* Dịch xuống 10px */
            left: 40px;
            /* Dịch sang phải 10px */
            padding: 5px 10px;
            /* Điều chỉnh kích thước padding theo nhu cầu */
            font-size: 14px;
        }

        .food-row.selected {
            background-color: rgb(108, 186, 255);
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
    </style>
</head>

<body>

    <h2>Hóa đơn</h2>
    <?php
    if (isset($success_message)) {
        echo '<div class="success-msg">' . $success_message . '</div>';
    }
    ?>

    <!-- /////////////////////////////////////////////////////// CẬP NHẬT TÌNH TRẠNG /////////////////////////////////////////////////////// -->
    <div class="modal" id="updateStatusModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Cập nhật trạng thái đơn hàng</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="billID">Mã hoá đơn:</label>
                            <input type="text" class="form-control" id="billID" name="billID" value="<?php echo $idBill; ?>" readonly>
                        </div>
                        <?php
                        foreach ($bills as $bill) {
                            if ($bill->getIDBill() == $idBill) {
                                $total = $bill->getTotalPrice();
                                $status = $bill->getStatus();
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="totalPrice">Tổng tiền:</label>
                            <input type="text" class="form-control" id="totalPrice" name="totalPrice" value="<?php echo $total ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="newStatus">Trạng thái:</label>
                            <select class="form-control" id="newStatus" name="newStatus">
                                <option value="Đã xử lý" <?php if ($status == 'Đã xử lý') echo 'selected'; ?>>Đã xử lý</option>
                                <option value="Chưa xử lý" <?php if ($status == 'Chưa xử lý') echo 'selected'; ?>>Chưa xử lý</option>
                                <option value="Hoàn thành" <?php if ($status == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                            </select>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="updateStatus">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <button type="button" name="add_size" data-toggle="modal" data-target="#updateStatusModal" class="btn btn-primary">Cập nhật trạng thái</button>

    <!-- /////////////////////////////////////////////////////// LỌC HOÁ ĐƠN /////////////////////////////////////////////////////// -->
    <div class="table-container">
        <div class="tbody-container">
            <h6>Lọc theo tình trạng hoá đơn</h6>
            <form action="" method="post">
                <select id="status" name="status" onchange="getStatusValue()">
                    <option value=""></option>
                    <option value="Tất cả" <?php if ($selectedStatus == 'Tất cả') echo 'selected'; ?>>Tất cả</option>
                    <option value="Đã xử lý" <?php if ($selectedStatus == 'Đã xử lý') echo 'selected'; ?>>Đã xử lý</option>
                    <option value="Chưa xử lý" <?php if ($selectedStatus == 'Chưa xử lý') echo 'selected'; ?>>Chưa xử lý</option>
                    <option value="Hoàn thành" <?php if ($selectedStatus == 'Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                </select>
            </form>
        </div>
        <!-- /////////////////////////////////////////////////////// TÌM KIẾM /////////////////////////////////////////////////////// -->


        <form>
            <button type="submit" class="reload" name="reload">
                <i class="fas fa-sync-alt"></i> Reload
            </button>
        </form>
        <div class="container_search" style="text-align: right;">
            <form id="search_form" onsubmit="updatePage(1); return false;">
                <input type="text" id="search_txt" placeholder="Nhập từ khoá cần tìm kiếm..." style="width: 300px;">
                <button type="submit" class="small-button">Tìm kiếm</button>
            </form>
        </div>
        <!-- /////////////////////////////////////////////////////// BẢNG HOÁ ĐƠN /////////////////////////////////////////////////////// -->


        <div class="table-wrapper">
            <table id="bill-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã Hóa đơn</th>
                        <th>Khách hàng</th>
                        <th>Nhân viên</th>
                        <th>Phần trăm giảm giá</th>
                        <th>Tổng tiền</th>
                        <th>Tình trạng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $stt = $offset;
                    $stt = $offset + 1; // Khởi tạo STT từ vị trí bắt đầu của trang hiện tại
                    foreach ($bills as $bill) {
                        $khachhang = $accountController->findbyId($bill->getIDCustomer());
                        $nhanvien = $accountController->findbyId($bill->getIDStaff());
                        $promotion = $Promotioncontroller->findDiscountPercentById($bill->getIDPromotion());
                    ?>
                        <tr class='food-row <?php if ($bill->getIDBill() == $id) echo "selected"; ?>' data-id='<?php echo $bill->getIDBill() ?>' data-status='<?php echo rawurlencode($status) ?>' onclick='myFunction(this)'>
                            <td><?php echo $stt; ?></td>
                            <td><?php echo $bill->getIDBill(); ?></td>
                            <td><?php echo $bill->getIDCustomer(); ?></td>
                            <td><?php echo $bill->getIDStaff(); ?></td>
                            <td><?php echo $promotion . "%"; ?></td>
                            <td><?php echo $bill->getTotalPrice(); ?></td>
                            <td><?php echo $bill->getStatus(); ?></td>
                        </tr>
                    <?php
                        $stt++; // Tăng giá trị của STT cho mỗi hóa đơn
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <ul>
                <?php if ($page > 1) { ?>
                    <li><a href="?page=<?php echo ($page - 1); ?>&status=<?php echo urlencode($selectedStatus); ?>&search=<?php echo urlencode($search_text); ?>"><<</a></li>

                <?php } ?>
                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                    <?php if ($i == $page) { ?>
                        <li><a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($selectedStatus); ?>&search=<?php echo urlencode($search_text); ?>" style="background-color:rgb(108, 186, 255);"><?php echo $i; ?></a></li>
                    <?php } else { ?>
                        <li><a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($selectedStatus); ?>&search=<?php echo urlencode($search_text); ?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                <?php } ?>
                <?php if ($page < $totalPages) { ?>
                    <li><a href="?page=<?php echo ($page + 1); ?>&status=<?php echo urlencode($selectedStatus); ?>&search=<?php echo urlencode($search_text); ?>">>></a></li>
                <?php } ?>
            </ul>
        </div>
        <!-- /////////////////////////////////////////////////////// BẢNG CHI TIẾT HOÁ ĐƠN /////////////////////////////////////////////////////// -->
        <h2> Chi tiết</h2>
        <div class="tbody-container">
            <div class="table-wrapper">
                <table id="detail-table">
                    <thead>
                        <tr>
                            <th>Mã Chi tiết Hóa đơn</th>
                            <th>Tên phim</th>
                            <th>Ghế</th>
                            <th>Tên phòng</th>
                            <th>Loại phòng</th>
                            <th>Thời gian chiếu</th>
                            <th>Vị trí ghế</th>
                            <th>Đồ ăn</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo  $idBill; ?></td>
                            <td><?php echo $tenfilm  ?></td>
                            <td><?php echo $tenghe ?></td>
                            <td><?php echo $tenroom ?></td>
                            <td><?php echo $loairoom ?></td>
                            <td><?php echo $formattedTime ?></td>
                            <td><?php echo $vitrighe ?></td>
                            <td><?php echo $idF ?></td>
                            <td><?php echo $sizefood ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var rows = document.querySelectorAll("tr");
        rows.forEach(function(row) {
            row.addEventListener("click", function() {
                rows.forEach(function(row) {
                    row.classList.remove("selected");
                });
                this.classList.add("selected");
            });
        });
    </script>

    <script>
        function myFunctionStatus(row) {
            var selectedFoodId = row.getAttribute('data-id');
            console.log("ID của món ăn được chọn: " + selectedFoodId);
            window.location.href = '/Group-2-Saturday/Ticket?id=' + selectedFoodId;
        }

        function getStatusValue() {
            var statusSelect = document.getElementById("status");
            var selectedStatus = statusSelect.options[statusSelect.selectedIndex].value;
            console.log("Giá trị option được chọn: " + selectedStatus);
            window.location.href = '/Group-2-Saturday/Ticket?status=' + selectedStatus;
        }

        function myFunction(row) {
            var currentPage = <?php echo $page; ?>;
            var selectedFoodId = row.getAttribute('data-id');
            var statusSelect = document.getElementById("status");
            var selectedStatus = statusSelect.options[statusSelect.selectedIndex].value;
            console.log("ID của món ăn được chọn: " + selectedFoodId);
            console.log("Giá trị status: " + selectedStatus);
            var url = '/Group-2-Saturday/Ticket?id=' + selectedFoodId + '&status=' + selectedStatus + '&page=' + currentPage;
            var searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('search')) {
                url += '&search=' + searchParams.get('search');
            }
            window.location.href = url;
        }

        function updatePage(pageNumber) {
            var searchTxt = document.getElementById('search_txt').value.trim();
            var statusSelect = document.getElementById("status");
            var url = "?page=" + pageNumber + "&search=" + encodeURIComponent(searchTxt);
            window.location.href = url;
        }
    </script>
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