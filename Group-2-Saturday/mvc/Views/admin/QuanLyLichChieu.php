<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý lịch chiếu</title>
    <link rel="stylesheet" href="./assets/css/QuanLyLichChieu.css" />

</head>

<body>
    <?php
    session_start();
    // Include các file controller cần thiết
    require_once './mvc/Controllers/Bill.php';
    require_once './mvc/Controllers/DetailBill.php';
    require_once './mvc/Controllers/QuanLyPhim.php';
    require_once './mvc/Controllers/QuanLyPhongChieu.php';
    require_once './mvc/Controllers/QuanLyLichChieu.php';

    // Khởi tạo các đối tượng controller
    $filmController = new QuanLyPhim();
    $cinemaRoomController = new QuanLyPhongChieu();
    $LichChieuController = new QuanLyLichChieu();
    $billcontroller = new Bill();
    $detailbillcontroller = new DetailBill();
    $bills = $billcontroller->getAllBills();
    $detailbills = $detailbillcontroller->getAllDetailBills();
    // Lấy thông tin phim từ cơ sở dữ liệu
    $films = $filmController->getFilms();

    // Lấy thông tin phòng chiếu từ cơ sở dữ liệu
    $cinemaRooms = $cinemaRoomController->getAllCinemaRooms();

    // $schedules = $LichChieuController->getAllScreenings();
    //  unset($_SESSION['loc_rs']);
    //  unset($_SESSION['slected']);
    if (isset($_SESSION['loc_rs']) && !empty($_SESSION['loc_rs'])) {
        // Lấy dữ liệu từ session và unserialize các đối tượng FilmEntity
        $schedules = array_map(function ($schedule) {
            return unserialize($schedule);
        }, $_SESSION['loc_rs']);
    } else {
        // Nếu session không tồn tại, bạn có thể gán danh sách lịch chiếu mặc định cho biến $schedules và $loc_rs
        $schedules = $loc_rs = $LichChieuController->getAllScreenings();
    }


    // Tính kích thước của danh sách phim hiện tại
    $currentSize = end($schedules)->id;
    // Xử lý thêm lịch chiếu
    if (isset($_POST['add_schedule'])) {
        $schedules = $LichChieuController->getAllScreenings();
        // Tính kích thước của danh sách phim hiện tại
        $currentSize = end($schedules)->id;
        // Tăng kích thước danh sách phim lên 1 để tạo ID mới
        $currentSize++;
        // Lấy thông tin từ form
        $movie = $_POST['movieSelect'];
        $startTime = $_POST['startTime'];
        $room = $_POST['roomSelect'];
        $price = $_POST['gia'];
        $checkngay = true;
        $checkgio = true;

        // Kiểm tra xem có lịch chiếu nào trong phòng chiếu và trong khoảng thời gian đã chọn không
        foreach ($schedules as $schedule) {
            if ($schedule->PhongChieu == $room) {
                // Danh sách các lịch chiếu
                $scheduleDates = array();
                // Lấy thông tin về thời lượng của phim trong lịch chiếu đó
                foreach ($films as $film) {
                    if ($film->id == $schedule->Phim) {
                        $scheduleDate = substr($schedule->ThoiGianChieu, 0, 10); // Lấy ngày từ thời gian chiếu
                        $scheduleDates[] = $scheduleDate;
                        $targetDate = substr($startTime, 0, 10);
                        if (in_array($targetDate, $scheduleDates)) {
                            $checkngay = false;
                            //========Thời lượng phim===========//
                            $movieDurationString = $film->ThoiLuong;
                            // Tìm vị trí của từ "phút" trong chuỗi
                            $pos = strpos($movieDurationString, "phút");
                            // Cắt chuỗi từ đầu đến vị trí "phút" và loại bỏ khoảng trắng ở hai đầu
                            $minutes = trim(substr($movieDurationString, 0, $pos));

                            // Lấy giờ và phút từ thời gian bắt đầu trong lịch
                            $endTime = substr($schedule->ThoiGianChieu, 11, 5); // Lấy từ vị trí 11 và lấy 5 ký tự
                            // Chuyển đổi $minutes sang kiểu số nguyên
                            $minutes = intval($minutes);
                            $minutes += 15;
                            // Tách giờ và phút của endTime
                            list($endHour, $endMinute) = explode(':', $endTime);
                            $endHour_2 = $endHour;
                            $endMinute_2 = $endMinute;
                            // Thêm số phút vào endTime
                            $endMinute += $minutes;
                            // Nếu phút vượt quá 60, cộng vào giờ và chỉ giữ lại phần dư
                            if ($endMinute >= 60) {
                                $endHour += floor($endMinute / 60);
                                $endMinute = $endMinute % 60;
                            }

                            // Format lại endTime
                            $endTime = sprintf('%02d:%02d', $endHour, $endMinute);

                            // Lấy giờ và phút từ thời gian bắt đầu khi nhập
                            $newTime = substr($startTime, 11, 5); // Lấy từ vị trí 11 và lấy 5 ký tự
                            // Tách giờ và phút của newTime
                            list($newHour, $newMinute) = explode(':', $newTime);
                            //Trường hợp giờ bắt đầu khi chọn ở web + thời lượng chưa tới thời gian chiếu trong lịch
                            $newMinute_2 = $newMinute + $minutes;
                            $newMinute_2 += 15;
                            $newHour_2 = $newHour;
                            if ($newMinute_2 >= 60) {
                                $newHour_2 += floor($newMinute_2 / 60);
                                $newMinute_2 = $newMinute_2 % 60;
                            }
                            // echo $newHour_2 . " " . $newMinute_2 . "   ";
                            // echo $endHour_2 . " " . $endMinute_2 . "   ";
                            // echo $endHour . " " . $endMinute . "   ";
                            // echo $newHour . " " . $newMinute;
                            if ($endHour < $newHour || ($endHour == $newHour && $endMinute <= $newMinute)) {
                            } else if ($newHour_2 < $endHour_2 || ($newHour_2 == $endHour_2 && $newMinute_2 <= $endMinute)) {
                            } else {
                                $checkgio = false;
                            }
                        } else {
                        }
                    }
                }
            }
        }
        if (!empty($movie) && !empty($startTime) && !empty($room) && !empty($price)) {
            if ($checkngay == true || $checkgio == true) {
                // Lấy dữ liệu từ biểu mẫu HTML
                $screeningData = array(
                    'id' => $currentSize, // Sử dụng kích thước mới của danh sách làm ID mới
                    'Phim' => $movie,
                    'ThoiGianChieu' => $startTime,
                    'PhongChieu' => $room,
                    'GiaVe' => $price,
                );
                // Gọi phương thức để thêm lịch chiếu
                $result = $LichChieuController->addScreening($screeningData);
                if ($result) {
                    $currentSize = end($schedules)->id;
                    // Sau khi thêm phim mới thành công, cập nhật lại danh sách
                    $schedules = $LichChieuController->getAllScreenings();
                } else {
                    // Nếu thất bại, hiển thị thông báo lỗi
                    $message = "Thêm thất bại";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                }
            } else if ($checkgio == false) {
                $message = "Vui lòng chọn lại giờ bắt đầu vì giờ đó phòng đang bận hoặc hôm nay đã kín lịch!";
                echo "<script type='text/javascript'>
                    setTimeout(function() {
                        alert('$message');
                    }, 100); // Hiển thị alert sau 0.1 giây
                  </script>";
            }
        } else {
            $error_message = "Vui lòng điền đầy đủ thông tin vào các trường!";
        }
    }

    // Xử lý sửa lịch chiếu
    if (isset($_POST['edit_schedule'])) {
        // Lấy thông tin từ form
        $scheduleId = $_POST['id_lichchieu'];
        $movie = $_POST['movieSelect'];
        $startTime = $_POST['startTime'];
        $room = $_POST['roomSelect'];
        $price = $_POST['gia'];

        // Lấy dữ liệu từ biểu mẫu HTML
        if (!empty($scheduleId) && !empty($movie) && !empty($startTime) && !empty($room) && !empty($price)) {
            $screeningData = array(
                'Phim' => $movie,
                'ThoiGianChieu' => $startTime,
                'PhongChieu' => $room,
                'GiaVe' => $price,
            );
            // Gọi phương thức để sửa lịch chiếu
            $result = $LichChieuController->updateScreening($scheduleId, $screeningData);
            if ($result) {
                // Nếu sửa thành công, làm mới trang
                // Sau khi thêm phim mới thành công, cập nhật lại danh sách
                $schedules = $LichChieuController->getAllScreenings();
            } else {
                // Nếu thất bại, hiển thị thông báo lỗi
            }
        } else {
            $error_message = "Vui lòng điền đầy đủ thông tin vào các trường!";
        }
    }

    if (isset($_POST['delete_schedule'])) {
        // Lấy ID của lịch chiếu từ form
        $scheduleId = $_POST['id_lichchieu'];

        // Biến để kiểm tra xem có bill chưa xử lý nào liên quan đến lịch chiếu này không
        $hasUnprocessedBill = false;

        // Kiểm tra xem lịch chiếu có bill chưa xử lý nào không
        foreach ($bills as $bill) {
            foreach ($detailbills as $detailbill) {
                if ($bill->Status == 'Chưa xử lý' && $detailbill->IDDetailBill == $bill->IDBill && $detailbill->IDShowTime == $scheduleId) {
                    $hasUnprocessedBill = true;
                    break 2; // Thoát khỏi cả hai vòng lặp
                }
            }
        }

        if ($hasUnprocessedBill) {
            // Nếu có bill chưa xử lý, chỉ cập nhật thời gian chiếu của lịch chiếu này
            $newTime = date('Y-m-d H:i', strtotime('-1 day'));
            foreach ($schedules as $schedule) {
                if ($schedule->id == $scheduleId) {
                    $TenPhim = $schedule->Phim;
                    $phong = $schedule->PhongChieu;
                    $gia = $schedule->GiaVe;
                    break;
                }
            }
            $screeningData = array(
                'Phim' => $TenPhim,
                'ThoiGianChieu' => $newTime,
                'PhongChieu' => $phong,
                'GiaVe' => $gia,
            );

            // Gọi phương thức để cập nhật thời gian chiếu của lịch chiếu có ID là $scheduleId
            $result = $LichChieuController->updateScreening($scheduleId, $screeningData);
            if ($result) {
                // Sau khi cập nhật thành công, cập nhật lại danh sách lịch chiếu
                $schedules = $LichChieuController->getAllScreenings();
            } else {
                // Xử lý lỗi nếu cập nhật không thành công
            }
        } else {
            // //Xóa ở khóa ngoại trc
            // foreach ($bills as $bill) {
            //     foreach ($detailbills as $detailbill) {
            //         if ($bill->IDBill == $detailbill->IDDetailBill && $detailbill->IDShowTime == $scheduleId) {
            //             $billcontroller->deleteBill($bill->IDBill);
            //         }
            //     }
            // }
            // foreach ($detailbills as $detailbill) {
            //     if ($detailbill->IDShowTime == $scheduleId) {
            //         $detailbillcontroller->deleteDetailBill($detailbill->IDDetailBill);
            //     }
            // }
            // Nếu không có bill chưa xử lý, xóa lịch chiếu này
            $result = $LichChieuController->deleteScreening($scheduleId);
            if ($result) {
                // Sau khi xóa thành công, cập nhật lại danh sách lịch chiếu
                $schedules = $LichChieuController->getAllScreenings();
            } else {
                // Xử lý lỗi nếu xóa không thành công
            }
        }
    }
    if (isset($_POST['XemLichFilm'])) {
        unset($_SESSION['loc_rs']);
        unset($_SESSION['slected']);
        $schedules = $LichChieuController->getAllScreenings();
        $new_list = array();
        $selectedFilmId = $_POST['XemLichFilm'];
        if ($selectedFilmId == 'all') {
            $schedules = $LichChieuController->getAllScreenings();

            // Serialize đối tượng FilmEntity thành một chuỗi trước khi lưu vào session
            $search_results_serialized = array_map(function ($schedule) {
                return serialize($schedule);
            }, $schedules);

            $_SESSION['loc_rs'] = $search_results_serialized;
            $_SESSION['slected'] = $selectedFilmId;
        } else {
            foreach ($schedules as $schedule) {
                if ($schedule->Phim == $selectedFilmId) {
                    $new_list[] = $schedule;
                }
            }
            $schedules = $new_list;
            // Serialize đối tượng FilmEntity thành một chuỗi trước khi lưu vào session
            $search_results_serialized = array_map(function ($schedule) {
                return serialize($schedule);
            }, $schedules);

            $_SESSION['loc_rs'] = $search_results_serialized;
            $_SESSION['slected'] = $selectedFilmId;
        }
    }

    ?>
    <form id="myForm" action="#" method="post">
        <div class="container">
            <h1>Quản lý lịch chiếu</h1>
            <div>
                <span style="color: red; font-weight: bold;"><?php echo $error_message; ?></span>
            </div>
            <div>
                <label class="lbid" for="movieSelect">ID:</label>
                <input class="dl_id" type="text" id="id_lichchieu" name="id_lichchieu" readonly placeholder="Mã sẽ được thêm tự động">
            </div>

            <div>
                <label class="lbPhim" for="movieSelect">Chọn phim:</label>
                <select class="cbbPhim" id="movieSelect" name="movieSelect">
                    <?php
                    $currentTime = date('Y-m-d'); // Lấy ngày hiện tại
                    foreach ($films as $film) :
                        // Chuyển đổi NgayKC và NgayKT sang định dạng DateTime của PHP
                        $ngayKC = DateTime::createFromFormat('d/m/Y', $film->NgayKC)->format('Y-m-d');
                        $ngayKT = DateTime::createFromFormat('d/m/Y', $film->NgayKT)->format('Y-m-d');

                        // So sánh thời gian hiện tại với NgayKC và NgayKT của từng phim
                        if ($ngayKT >= $currentTime || $ngayKC >= $currentTime) :
                    ?>
                            <option value="<?php echo $film->id ?>">
                                <?php echo $film->TenPhim ?>
                            </option>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </select>
            </div>

            <div>
                <label class="lbtime" for="startTime">Thời gian bắt đầu:</label>
                <input class="dulieutime" type="datetime-local" id="startTime" name="startTime" min="" max="">

            </div>

            <div>
                <label class=" lbPhong" for="roomSelect">Chọn phòng chiếu:</label>
                <select class="cbbPhong" id="roomSelect" name="roomSelect">
                    <?php foreach ($cinemaRooms as $room) : ?>
                        <?php if ($room->TinhTrang == 'Sẵn sàng') : ?>
                            <option value="<?php echo $room->id ?>">
                                <?php echo $room->TenPhong ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="lbgia" for="roomSelect">Giá vé: </label>
                <input class="gia" type="text" id="gia" name="gia">
            </div>


            <div class="actions">
                <button type="submit" name="add_schedule">Thêm</button>
                <button type="submit" name="edit_schedule">Sửa</button>
                <button type="submit" name="delete_schedule" onclick="confirmDelete(event)">Xóa</button>
                <label style="margin-left: 150px;text-align: center;height: 40px;
                    line-height: 40px; font-weight: bold;">Xem lịch chiếu của phim:
                </label>
                <?php
                // Lấy giá trị đã chọn trong combobox trước đó (nếu có)
                $slected = $_SESSION['slected'];;
                ?>
                <select style="width:300px ; margin-left: 10px;" id="XemLichFilm" name="XemLichFilm">
                    <option value="all" <?php if ($slected == 'all') echo 'selected'; ?>>Tất cả</option>
                    <?php foreach ($films as $film) : ?>
                        <option value="<?php echo $film->id ?>" <?php if ($slected == $film->id) echo 'selected'; ?>>
                            <?php echo $film->TenPhim ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <!-- <button style="width: 200px; margin-left: 160px;" onclick="selectWorkHours()">Xét giờ hoạt động rạp</button> -->
            </div>
            <div class="table-container">

                <?php
                // Số lượng dòng hiển thị trên mỗi trang
                $perPage = 6;

                // Tổng số phim
                $totalschedules = count($schedules);

                // Tổng số trang
                $totalPages = ceil($totalschedules / $perPage);

                // Xác định trang hiện tại
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                // Tính vị trí bắt đầu của dữ liệu trên trang hiện tại
                $start = ($current_page - 1) * $perPage;

                // Lấy danh sách phim cho trang hiện tại
                $schedules_on_page = array_slice($schedules, $start, $perPage);
                ?>
                <table id="scheduleTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Phim</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Phòng chiếu</th>
                            <th>Giá vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules_on_page as $schedule) { ?>
                            <tr>
                                <td><?php echo $schedule->id; ?></td>
                                <td><?php echo $schedule->Phim; ?></td>
                                <td><?php echo $schedule->ThoiGianChieu; ?></td>
                                <td><?php echo $schedule->PhongChieu; ?></td>
                                <td><?php echo $schedule->GiaVe; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
                <div class="pagination-wrapper">
                    <?php
                    // Hiển thị nút "<<" và "<"
                    if ($current_page > 1) {
                        echo '<a href="./QuanLyLichChieu&page=1">&lt;&lt;</a>'; // Link đến trang đầu tiên
                        echo '<a href="./QuanLyLichChieu&page=' . ($current_page - 1) . '">&lt;</a>'; // Link đến trang trước
                    }

                    // Hiển thị số trang
                    for ($page = 1; $page <= $totalPages; $page++) {
                        if ($page == $current_page) {
                            echo '<a href="./QuanLyLichChieu&page=' . $page . '">' . '<strong>' . $page . '</strong> ' . '</a> ';
                        } else {
                            echo '<a href="./QuanLyLichChieu&page=' . $page . '">' . $page . '</a>'; // Hiển thị các trang khác
                        }
                    }

                    // Hiển thị nút ">" và ">>"
                    if ($current_page < $totalPages) {
                        echo '<a href="./QuanLyLichChieu&page=' . ($current_page + 1) . '">&gt;</a>'; // Link đến trang kế tiếp
                        echo '<a href="./QuanLyLichChieu&page=' . $totalPages . '">&gt;&gt;</a>'; // Link đến trang cuối cùng
                    }
                    ?>
                </div>

            </div>

        </div>
    </form>
    <script>
        // Lấy danh sách các dòng trong bảng
        var rows = document.querySelectorAll("#scheduleTable tbody tr");

        // Duyệt qua mỗi dòng và thêm sự kiện click
        rows.forEach(function(row) {
            row.addEventListener("click", function() {
                // Lấy các thông tin từ dòng được click
                var cells = row.querySelectorAll("td");

                // Lấy các giá trị từ các ô trong dòng
                var id = cells[0].innerText;
                var movie = cells[1].innerText;
                var startTime = cells[2].innerText;
                var room = cells[3].innerText;
                var price = cells[4].innerText;

                // Đổ thông tin lên các input tương ứng
                document.getElementById("id_lichchieu").value = id;
                document.getElementById("movieSelect").value = movie;
                document.getElementById("startTime").value = startTime;
                document.getElementById("roomSelect").value = room;
                document.getElementById("gia").value = price;

                // Loại bỏ lớp "selected-row" từ tất cả các dòng trong bảng
                rows.forEach(function(row) {
                    row.classList.remove("selected-row");
                });
                // Thêm lớp "selected-row" vào dòng được click
                this.classList.add("selected-row");


                <?php foreach ($films as $film) : ?>
                    if (<?php echo $film->id ?> == document.getElementById('movieSelect').value) {
                        var minDateStr = '<?php echo $film->NgayKC ?>';
                        var maxDateStr = '<?php echo $film->NgayKT ?>';
                        // Chuyển đổi định dạng từ "20/04/2024" sang "2024-04-20" cho minDate và maxDate
                        var minDateParts = minDateStr.split('/');
                        var minDate = minDateParts[2] + '-' + minDateParts[1].padStart(2, '0') + '-' + minDateParts[0].padStart(2, '0');

                        var maxDateParts = maxDateStr.split('/');
                        var maxDate = maxDateParts[2] + '-' + maxDateParts[1].padStart(2, '0') + '-' + maxDateParts[0].padStart(2, '0');

                        document.getElementById('startTime').setAttribute('min', minDate + 'T00:00');
                        document.getElementById('startTime').setAttribute('max', maxDate + 'T23:59');
                    }
                <?php endforeach; ?>

            });
        });



        function selectWorkHours() {
            // Hiển thị hộp thoại hoặc giao diện cho phép người dùng chọn giờ bắt đầu và kết thúc
            var workStartHour = prompt("Nhập giờ bắt đầu mở rạp chiếu (số từ 1 đến 24):", "8");
            var workEndHour = prompt("Nhập giờ đóng rạp chiếu (số từ 1 đến 24):", "22");

            // Kiểm tra xem giờ bắt đầu và kết thúc làm việc có hợp lệ không và hiển thị thông báo tương ứng
            if (workStartHour != null && workEndHour != null && !isNaN(workStartHour) && !isNaN(workEndHour)) {
                workStartHour = parseInt(workStartHour);
                workEndHour = parseInt(workEndHour);

                if (workStartHour >= 1 && workStartHour <= 24 && workEndHour >= 1 && workEndHour <= 24 && workEndHour > workStartHour) {
                    alert('Đã cập nhật giờ hoạt động rạp chiếu phim: Bắt đầu từ ' + workStartHour + 'h và kết thúc vào ' + workEndHour + 'h');
                } else {
                    alert('Giờ không hợp lệ. Vui lòng nhập giờ từ 1 đến 24 và đảm bảo giờ kết thúc lớn hơn giờ bắt đầu.');
                }
            } else {
                alert('Vui lòng nhập giờ mở cửa và đóng rạp.');
            }
        }
        // Lấy ngày tháng từ PHP và chuyển đổi định dạng
        var minDateStr = '<?php echo $films[0]->NgayKC ?>';
        var minDateParts = minDateStr.split('/');
        var minDate = minDateParts[2] + '-' + minDateParts[1].padStart(2, '0') + '-' + minDateParts[0].padStart(2, '0');

        var maxDateStr = '<?php echo $films[0]->NgayKT ?>';
        var maxDateParts = maxDateStr.split('/');
        var maxDate = maxDateParts[2] + '-' + maxDateParts[1].padStart(2, '0') + '-' + maxDateParts[0].padStart(2, '0');

        // Gán giá trị min và max cho input datetime-local
        document.getElementById('startTime').setAttribute('min', minDate + 'T00:00');
        document.getElementById('startTime').setAttribute('max', maxDate + 'T23:59');

        document.getElementById('movieSelect').addEventListener('change', function() {
            var selectedValue = this.value; // Lấy giá trị của option được chọn
            <?php foreach ($films as $film) : ?>
                if (<?php echo $film->id ?> == selectedValue) {
                    var minDateStr = '<?php echo $film->NgayKC ?>';
                    var maxDateStr = '<?php echo $film->NgayKT ?>';
                    // Chuyển đổi định dạng từ "20/04/2024" sang "2024-04-20" cho minDate và maxDate
                    var minDateParts = minDateStr.split('/');
                    var minDate = minDateParts[2] + '-' + minDateParts[1].padStart(2, '0') + '-' + minDateParts[0].padStart(2, '0');

                    var maxDateParts = maxDateStr.split('/');
                    var maxDate = maxDateParts[2] + '-' + maxDateParts[1].padStart(2, '0') + '-' + maxDateParts[0].padStart(2, '0');

                    document.getElementById('startTime').setAttribute('min', minDate + 'T00:00');
                    document.getElementById('startTime').setAttribute('max', maxDate + 'T23:59');
                }
            <?php endforeach; ?>
        });

        // function filterSchedule() {
        //     var selectedFilmId = document.getElementById("XemLichFilm").value;
        //     var rows = document.querySelectorAll("#scheduleTable tbody tr");

        //     rows.forEach(function(row) {
        //         var cells = row.querySelectorAll("td");
        //         var movieId = cells[1].innerText; // Lấy id phim từ bảng

        //         if (selectedFilmId === "all" || selectedFilmId === movieId) {
        //             row.style.display = ""; // Hiển thị dòng nếu chọn tất cả hoặc id phim trùng khớp
        //         } else {
        //             row.style.display = "none"; // Ẩn dòng nếu không trùng khớp
        //         }
        //     });

        // }

        function confirmDelete(event) {
            if (!confirm('Bạn có chắc chắn muốn xóa lịch này không?')) {
                event.preventDefault(); // Ngăn chặn form submit nếu người dùng nhấn "Cancel"
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            var comboBox = document.getElementById("XemLichFilm");

            comboBox.addEventListener("change", function() {
                document.getElementById("myForm").submit();
            });
        });
    </script>
</body>

</html>