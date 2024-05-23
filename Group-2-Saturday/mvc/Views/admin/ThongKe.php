<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê Doanh thu Phim</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./assets/css/ThongKe.css" />
</head>

<body>
    <?php

    require_once './mvc/Controllers/Bill.php';
    require_once './mvc/Controllers/DetailBill.php';
    require_once './mvc/Controllers/QuanLyPhim.php';
    require_once './mvc/Controllers/QuanLyPhongChieu.php';
    require_once './mvc/Controllers/QuanLyLichChieu.php';
    require_once './mvc/Controllers/Account.php';

    $billcontroller = new Bill();
    $detailbillcontroller = new DetailBill();
    $filmcontroller = new QuanLyPhim();
    $schedulecontroller = new QuanLyLichChieu();
    $cinemaRoomcontroller = new QuanLyPhongChieu();
    $customerController = new Account();

    // Lấy danh sách phim từ cơ sở dữ liệu
    $films = $filmcontroller->getFilms();
    $bills = $billcontroller->getAllBills();
    $detailbills = $detailbillcontroller->getAllDetailBills();
    $schedules = $schedulecontroller->getAllScreenings();
    $cinemaRooms = $cinemaRoomcontroller->getAllCinemaRooms();

    $filmTotalRevenue = array();
    foreach ($detailbills as $detailbill) {
        // Lấy ID phim từ chi tiết hóa đơn
        $filmID = $detailbill->IDMovie;
        $totalPrice = "";
        // Tạo một biến để kiểm tra xem hóa đơn có trạng thái "Hoàn thành" không
        $completedBill = false;
        // Lặp qua danh sách các hóa đơn
        foreach ($bills as $bill) {
            if ($detailbill->IDBill == $bill->IDBill && $bill->Status == "Hoàn thành") {
                $totalPrice = $bill->TotalPrice;
                // Đặt cờ "completedBill" thành true nếu hóa đơn được đánh dấu là "Hoàn thành"
                $completedBill = true;
                break; // Thoát khỏi vòng lặp nếu đã tìm thấy hóa đơn "Hoàn thành"
            }
        }
        // Chỉ tiếp tục nếu hóa đơn được đánh dấu là "Hoàn thành"
        if ($completedBill) {
            // Nếu phim chưa tồn tại trong mảng $filmTotalRevenue thì thêm vào và gán giá trị doanh thu
            if (!isset($filmTotalRevenue[$filmID])) {
                $filmTotalRevenue[$filmID] = 0;
            }
            // Cộng thêm giá trị doanh thu vào tổng doanh thu của phim
            $filmTotalRevenue[$filmID] += $totalPrice;
        }
    }


    // Sắp xếp mảng $filmTotalRevenue theo giá trị doanh thu giảm dần
    arsort($filmTotalRevenue);

    // Lấy top 5 bộ phim có doanh thu cao nhất
    $top5Films = array_slice($filmTotalRevenue, 0, 5, true);

    // ==========================Top 5 khách hàng===================
    // Khởi tạo mảng để lưu doanh thu của từng khách hàng
    $customerTotalRevenue = array();

    // Lặp qua danh sách các hóa đơn
    foreach ($bills as $bill) {
        foreach ($detailbills as $detailbill) {
            // Chỉ xử lý các hóa đơn có trạng thái là "Hoàn thành"
            if ($bill->Status == "Hoàn thành") {
                // Lấy ID khách hàng từ hóa đơn
                $customerID = $bill->IDCustomer;

                // Lấy thông tin khách hàng từ ID
                $customerInfo = $customerController->findbyId($customerID);

                // Nếu khách hàng chưa tồn tại trong mảng, thì khởi tạo doanh thu của khách hàng là 0
                if (!isset($customerTotalRevenue[$customerID])) {
                    $customerTotalRevenue[$customerID] = 0;
                }

                // Cộng thêm giá trị doanh thu của hóa đơn vào tổng doanh thu của khách hàng
                $customerTotalRevenue[$customerID] += $bill->TotalPrice;
            }
        }
    }

    // Sắp xếp mảng $customerTotalRevenue theo giá trị doanh thu giảm dần
    arsort($customerTotalRevenue);

    // Lấy top 5 khách hàng có doanh thu cao nhất
    $top5Customers = array_slice($customerTotalRevenue, 0, 5, true);

    // Hiển thị thông tin top 5 khách hàng
    foreach ($top5Customers as $customerID => $totalRevenue) {
        // Lấy thông tin của khách hàng từ ID
        $customerInfo = $customerController->findbyId($customerID);
    }

    if (isset($_POST['ThongKe'])) {
        $fromDate = $_POST['from'];
        $toDate = $_POST['to'];
        // Khởi tạo mảng để lưu doanh thu của từng khách hàng
        $customerTotalRevenue = array();

        // Lặp qua danh sách các hóa đơn
        foreach ($bills as $bill) {
            foreach ($detailbills as $detailbill) {
                // Chỉ xử lý các hóa đơn có trạng thái là "Hoàn thành"
                if ($bill->Status == "Hoàn thành" && $bill->IDBill == $detailbill->IDDetailBill) {
                    foreach ($schedules as $schedule) {
                        if ($detailbill->IDShowTime == $schedule->id && $schedule->ThoiGianChieu >= $fromDate && $schedule->ThoiGianChieu <= $toDate) {
                            // Lấy ID khách hàng từ hóa đơn
                            $customerID = $bill->IDCustomer;

                            // Lấy thông tin khách hàng từ ID
                            $customerInfo = $customerController->findbyId($customerID);

                            // Nếu khách hàng chưa tồn tại trong mảng, thì khởi tạo doanh thu của khách hàng là 0
                            if (!isset($customerTotalRevenue[$customerID])) {
                                $customerTotalRevenue[$customerID] = 0;
                            }

                            // Cộng thêm giá trị doanh thu của hóa đơn vào tổng doanh thu của khách hàng
                            $customerTotalRevenue[$customerID] += $bill->TotalPrice;
                        }
                    }
                }
            }
        }

        // Sắp xếp mảng $customerTotalRevenue theo giá trị doanh thu giảm dần
        arsort($customerTotalRevenue);

        // Lấy top 5 khách hàng có doanh thu cao nhất
        $top5Customers = array_slice($customerTotalRevenue, 0, 5, true);

        // Hiển thị thông tin top 5 khách hàng
        foreach ($top5Customers as $customerID => $totalRevenue) {
            // Lấy thông tin của khách hàng từ ID
            $customerInfo = $customerController->findbyId($customerID);
        }
    }
    // ==========================Biểu đồ===================

    // Mảng để lưu tổng giá trị của từng tháng
    $monthlyTotalPrice = array();
    // Khởi tạo mảng $monthlyTotalPrice với tất cả các tháng có giá trị ban đầu là 0
    for ($i = 1; $i <= 12; $i++) {
        $month = str_pad($i, 2, '0', STR_PAD_LEFT); // Chuyển số tháng thành chuỗi, ví dụ: '01' thay vì '1'
        $monthlyTotalPrice[$month] = 0;
    }
    // Lặp qua danh sách các detail bill
    foreach ($detailbills as $detailbill) {
        // Lấy IDShowTime của detail bill
        $IDShowTime = $detailbill->IDShowTime;

        // Lặp qua danh sách các lịch chiếu
        foreach ($schedules as $schedule) {
            // Nếu IDShowTime của detail bill trùng với ID của lịch chiếu
            if ($IDShowTime == $schedule->id) {
                // Lấy ThoiGianChieu của lịch chiếu
                $ThoiGianChieu = $schedule->ThoiGianChieu;

                // Lấy IDBill của detail bill
                $IDBill = $detailbill->IDBill;

                // Lặp qua danh sách các hóa đơn
                foreach ($bills as $bill) {
                    // Nếu IDBill của detail bill trùng với ID của hóa đơn và hóa đơn có trạng thái là "Hoàn thành"
                    if ($IDBill == $bill->IDBill && $bill->Status == "Hoàn thành") {
                        // Lấy TotalPrice của hóa đơn
                        $TotalPrice = $bill->TotalPrice;

                        // Lấy tháng từ ThoiGianChieu
                        $month = date('m', strtotime($ThoiGianChieu));

                        $monthlyTotalPrice[$month] += $TotalPrice;
                    }
                }
            }
        }
    }

    ?>
    <h1>Top 5 Bộ Phim Có Doanh Thu Cao Nhất</h1>
    <div style="width: 100%; height: 200px;">
        <?php
        // Lặp qua top 5 bộ phim để hiển thị thông tin trên biểu đồ
        foreach ($top5Films as $filmID => $totalRevenue) {
            // Lấy thông tin của bộ phim từ ID
            $filmInfo = $filmcontroller->getFilmByID($filmID);
        ?>
            <div class="movie-item">
                <img src="data:image/jpeg;base64,<?php echo  base64_encode($filmInfo->HinhAnh)?>" alt="<?php echo $filmInfo->TenPhim; ?>">
                <div class="movie-info">
                    <label style="font-size: 12px;"><?php echo $filmInfo->TenPhim; ?></label><br>
                    <label>Doanh thu: <?php echo $totalRevenue; ?> vnđ</label>
                </div>
            </div>
        <?php } ?>
    </div>
    <h1>Top 5 Khách Hàng Có Tổng chi tiêu Cao Nhất</h1>
    <form method="post">
        <div>
            <input type="datetime-local" id="from" name="from">
            <input type="datetime-local" id="to" name="to">
            <button type="submit" name="ThongKe">Xem</button>
        </div>
    </form>
    <div style="width: 100%; height: 200px;">
        <?php
        $customerCount = 0;
        // Lặp qua top 5 khách hàng để hiển thị thông tin
        foreach ($top5Customers as $customerID => $totalRevenue) {
            // Lấy thông tin của khách hàng từ ID
            $customerInfo = $customerController->findbyId($customerID);
            $customerCount++;
        ?>
            <div class="movie-item">
            <img src="data:image/jpeg;base64,<?php echo  base64_encode($customerInfo->avatar)?>" alt="<?php echo $customerInfo->name; ?>">
                <div class="movie-info">
                    <label style="font-size: 12px;"><?php echo $customerInfo->name; ?></label><br>
                    <label>Tổng chi: <?php echo $totalRevenue; ?> vnđ</label>
                </div>
            </div>
        <?php } ?>
        <?php
        if ($customerCount === 0) {
            echo "Không có khách hàng nào mua trong thời điểm này.";
        }
        ?>
    </div>

    <div style="width: 80%;">
        <h1>Biểu đồ doanh thu theo tháng</h1>
        <canvas style="background-color: white; " id="myChart"></canvas>
    </div>


    <script>
        // Lấy thời gian hiện tại
        const currentDatea = new Date();
        // Lấy thời gian hiện tại
        const currentDateb = new Date();
        // Đặt giá trị cho a và b
        const a = currentDatea.setDate(currentDatea.getDate());
        const b = currentDateb.setDate(currentDateb.getDate() + 7);

        // Định dạng lại thời gian để thích hợp với input datetime-local
        const fromDate = new Date(a).toISOString().slice(0, 16);
        const toDate = new Date(b).toISOString().slice(0, 16);

        // Đặt giá trị mặc định cho cả input 'from' và 'to'
        document.getElementById('from').value = fromDate;
        document.getElementById('to').value = toDate;
        // Dữ liệu từ PHP được truyền vào biến JavaScript
        const monthlyTotalPrice = <?php echo json_encode($monthlyTotalPrice); ?>;
        // Tạo mảng chứa các tháng
        const months = Object.keys(monthlyTotalPrice).sort((a, b) => parseInt(a) - parseInt(b));

        // Tạo mảng chứa các giá trị tương ứng với từng tháng
        const totalPriceValues = months.map(month => monthlyTotalPrice[month]);

        // Vẽ biểu đồ cột
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Doanh thu theo tháng',
                    data: totalPriceValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return value.toLocaleString() + ' vnđ'; // Thêm đơn vị tiền tệ vào nhãn
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>