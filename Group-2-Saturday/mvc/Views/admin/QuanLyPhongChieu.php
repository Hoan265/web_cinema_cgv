<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="./assets/css/QuanLyPhongChieu.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" />
</head>

<body>
    <?php
    // Include các file controller cần thiết
    require_once './mvc/Controllers/Bill.php';
    require_once './mvc/Controllers/DetailBill.php';
    require_once './mvc/Controllers/QuanLyPhim.php';
    require_once './mvc/Controllers/QuanLyPhongChieu.php';
    require_once './mvc/Controllers/QuanLyLichChieu.php';
    require_once './mvc/Controllers/QuanLyGhe.php';

    // Khởi tạo các đối tượng controller
    $cinemaRoomController = new QuanLyPhongChieu();
    $chairController = new QuanLyGhe();
    $billcontroller = new Bill();
    $detailbillcontroller = new DetailBill();
    $bills = $billcontroller->getAllBills();
    $detailbills = $detailbillcontroller->getAllDetailBills();
    // Lấy thông tin phòng chiếu từ cơ sở dữ liệu
    $cinemaRooms = $cinemaRoomController->getAllCinemaRooms();
    $firtroom = $cinemaRooms[0];

    $dsghe = $firtroom->DSGhe;

    // Lấy giá trị của phòng được chọn từ biểu mẫu
    $selectedRoomId = $_POST['ds_room'] ?? null;


    foreach ($cinemaRooms as $room_) {
        if ($room_->id == $selectedRoomId) {
            $firtroom = $room_;
            $dsghe = $firtroom->DSGhe;
        }
    }

    $tempDSGheArray = [];
    // Tách chuỗi thành các dòng
    $rows = explode(',', $dsghe);
    // Lặp qua từng dòng
    foreach ($rows as $row) {
        // Tách dữ liệu của mỗi dòng thành các cột
        $columns = explode(':', $row);
        // Thêm mỗi dòng vào mảng 2 chiều
        $tempDSGheArray[] = $columns;
    }
    $chairs = $chairController->getAllChairs();
    // Tính kích thước của danh sách phim hiện tại
    $currentSize = end($cinemaRooms)->id;
    // Xử lý thêm lịch chiếu
    if (isset($_POST['add_room'])) {
        // Lấy giá trị của input ẩn tempDSGheArray và giải mã thành mảng
        $new_tempDSGheArray = json_decode($_POST['tempDSGheArray'], true);
        // Khai báo một chuỗi để lưu kết quả
        $dsgheString = '';
        // Duyệt qua từng hàng trong mảng
        foreach ($new_tempDSGheArray as $row) {
            // Nối dấu phẩy vào chuỗi nếu chuỗi không rỗng
            if ($dsgheString !== '') {
                $dsgheString .= ',';
            }
            // Duyệt qua từng cột trong hàng
            $rowString = '';
            foreach ($row as $column) {
                // Nối giá trị của cột vào chuỗi hàng
                $rowString .= $column . ':';
            }
            // Loại bỏ dấu hai chấm cuối cùng của chuỗi hàng và nối vào chuỗi kết quả
            $dsgheString .= rtrim($rowString, ':');
        }
        // Tăng kích thước danh sách phim lên 1 để tạo ID mới
        $currentSize++;
        // Lấy thông tin từ form
        $ten_phong = $_POST['ten_phong'];
        $loai_phong = $_POST['loai_phong'];
        $trang_thai = $_POST['trang_thai'];
        $ds_ghe = $dsghe;
        // Kiểm tra xem các thông tin đã được gửi từ form và không rỗng
        if (!empty($ten_phong) && !empty($loai_phong) && !empty($trang_thai) && !empty($ds_ghe) && !empty($currentSize)) {
            $roomExists = false;
            foreach ($cinemaRooms as $room) {
                if ($room->TenPhong === $ten_phong) {
                    $roomExists = true;
                    break;
                }
            }

            if ($roomExists) {
                // Hiển thị thông báo lỗi
                $error_message = "Tên phòng đã tồn tại. Vui lòng chọn tên phòng khác.";
            } else {
                $roomData = array(
                    'id' => $currentSize, // Sử dụng kích thước mới của danh sách làm ID mới
                    'TenPhong' => $ten_phong,
                    'LoaiPhong' => $loai_phong,
                    'TinhTrang' => $trang_thai,
                    'DSGhe' => $dsgheString
                );
                // Gọi phương thức để sửa lịch chiếu
                $result = $cinemaRoomController->addCinemaRoom($roomData);
            }
        } else {
            $error_message = "Vui lòng điền đầy đủ thông tin vào các trường!";
        }
        if ($result) {
            // Nếu sửa thành công, làm mới trang
            // Cập nhật kích thước của danh sách 
            $currentSize = end($cinemaRooms)->id;
            // Sau khi thêm phim mới thành công, cập nhật lại danh sách
            $cinemaRooms = $cinemaRoomController->getAllCinemaRooms();
            $dsghe = $dsgheString;
            $tempDSGheArray = [];
            // Tách chuỗi thành các dòng
            $rows = explode(',', $dsghe);
            // Lặp qua từng dòng
            foreach ($rows as $row) {
                // Tách dữ liệu của mỗi dòng thành các cột
                $columns = explode(':', $row);
                // Thêm mỗi dòng vào mảng 2 chiều
                $tempDSGheArray[] = $columns;
            }
            $firtroom = end($cinemaRooms);
        } else {
            // Nếu thất bại, hiển thị thông báo lỗi

        }
    }

    // Xử lý sửa lịch chiếu
    if (isset($_POST['edit_room'])) {
        // Lấy giá trị của input ẩn tempDSGheArray và giải mã thành mảng
        $new_tempDSGheArray = json_decode($_POST['tempDSGheArray'], true);
        // Khai báo một chuỗi để lưu kết quả
        $dsgheString = '';
        // Duyệt qua từng hàng trong mảng
        foreach ($new_tempDSGheArray as $row) {
            // Nối dấu phẩy vào chuỗi nếu chuỗi không rỗng
            if ($dsgheString !== '') {
                $dsgheString .= ',';
            }
            // Duyệt qua từng cột trong hàng
            $rowString = '';
            foreach ($row as $column) {
                // Nối giá trị của cột vào chuỗi hàng
                $rowString .= $column . ':';
            }
            // Loại bỏ dấu hai chấm cuối cùng của chuỗi hàng và nối vào chuỗi kết quả
            $dsgheString .= rtrim($rowString, ':');
        }
        // Lấy thông tin từ form
        $roomId = $_POST['id_room'];
        $ten_phong = $_POST['ten_phong'];
        $loai_phong = $_POST['loai_phong'];
        $trang_thai = $_POST['trang_thai'];
        $ds_ghe = $dsgheString;
        // Kiểm tra xem các thông tin đã được gửi từ form và không rỗng
        if (!empty($roomId) && !empty($ten_phong) && !empty($loai_phong) && !empty($trang_thai) && !empty($ds_ghe)) {
            $roomExists = false;
            foreach ($cinemaRooms as $room) {
                if ($room->TenPhong === $ten_phong) {
                    $roomExists = true;
                    break;
                }
            }

            if ($roomExists) {
                // Hiển thị thông báo lỗi
                $error_message = "Tên phòng đã tồn tại. Vui lòng chọn tên phòng khác.";
            } else {
                $roomData = array(
                    'TenPhong' => $ten_phong,
                    'LoaiPhong' => $loai_phong,
                    'TinhTrang' => $trang_thai,
                    'DSGhe' => $ds_ghe
                );
                // Gọi phương thức để sửa lịch chiếu
                $result = $cinemaRoomController->updateCinemaRoom($roomId, $roomData);
            }
        } else {
            $error_message = "Vui lòng điền đầy đủ thông tin vào các trường!";
        }
        if ($result) {
            // Nếu sửa thành công, làm mới trang
            // Sau khi thêm phim mới thành công, cập nhật lại danh sách
            $cinemaRooms = $cinemaRoomController->getAllCinemaRooms();
            $dsghe = $dsgheString;
            $tempDSGheArray = [];
            // Tách chuỗi thành các dòng
            $rows = explode(',', $dsghe);
            // Lặp qua từng dòng
            foreach ($rows as $row) {
                // Tách dữ liệu của mỗi dòng thành các cột
                $columns = explode(':', $row);
                // Thêm mỗi dòng vào mảng 2 chiều
                $tempDSGheArray[] = $columns;
            }
            foreach ($cinemaRooms as $room_) {
                if ($room_->id == $roomId) {
                    $firtroom = $room_;
                }
            }
        } else {
            // Nếu thất bại, hiển thị thông báo lỗi

        }
    }

    if (isset($_POST['delete_room'])) {
        // Lấy ID của room chiếu từ form
        $roomId = $_POST['id_room'];
        $hasUnprocessedBill = false;
        // Kiểm tra xem lịch chiếu có bill chưa xử lý nào không
        foreach ($bills as $bill) {
            foreach ($detailbills as $detailbill) {
                if ($bill->Status == 'Chưa xử lý' && $detailbill->IDDetailBill == $bill->IDBill && $detailbill->IDRoom == $roomId) {
                    $hasUnprocessedBill = true;
                    break 2; // Thoát khỏi cả hai vòng lặp
                }
            }
        }
        if ($hasUnprocessedBill) {
            foreach ($cinemaRooms as $room) {
                if ($room->id == $roomId) {
                    $ten_phong = $room->TenPhong;
                    $loai_phong = $room->LoaiPhong;
                    $trang_thai = 'Đang hoàn thiện';
                    $ds_ghe = $room->DSGhe;
                    break;
                }
            }
            $filmDatas = array(
                'TenPhong' => $ten_phong,
                'LoaiPhong' => $loai_phong,
                'TinhTrang' => $trang_thai,
                'DSGhe' => $ds_ghe
            );
            $result = $cinemaRoomController->updateCinemaRoom($roomId, $filmDatas);
            if ($result) {
                $cinemaRooms = $cinemaRoomController->getAllCinemaRooms();
                $dsghe = $room->DSGhe;
                $tempDSGheArray = [];
                // Tách chuỗi thành các dòng
                $rows = explode(',', $dsghe);
                // Lặp qua từng dòng
                foreach ($rows as $row) {
                    // Tách dữ liệu của mỗi dòng thành các cột
                    $columns = explode(':', $row);
                    // Thêm mỗi dòng vào mảng 2 chiều
                    $tempDSGheArray[] = $columns;
                }
                foreach ($cinemaRooms as $room_) {
                    if ($room_->id == $roomId) {
                        $firtroom = $room_;
                    }
                }
            } else {
                // Xử lý lỗi nếu cập nhật không thành công
            }
        } else {
            // //Xóa ở khóa ngoại trc
            // foreach ($bills as $bill) {
            //     foreach ($detailbills as $detailbill) {
            //         if ($bill->IDBill == $detailbill->IDDetailBill && $detailbill->IDRoom == $roomId) {
            //             $billcontroller->deleteBill($bill->IDBill);
            //         }
            //     }
            // }
            // foreach ($detailbills as $detailbill) {
            //     if ($detailbill->IDRoom == $roomId) {
            //         $detailbillcontroller->deleteDetailBill($detailbill->IDDetailBill);
            //     }
            // }
            // Gọi phương thức để xóa 
            $result = $cinemaRoomController->deleteCinemaRoom($roomId);
            if ($result) {
                // Sau khi thêm phim mới thành công, cập nhật lại danh sách
                $cinemaRooms = $cinemaRoomController->getAllCinemaRooms();
                $firtroom = $cinemaRooms[0];
                $dsghe = $firtroom->DSGhe;
                $tempDSGheArray = [];
                // Tách chuỗi thành các dòng
                $rows = explode(',', $dsghe);
                // Lặp qua từng dòng
                foreach ($rows as $row) {
                    // Tách dữ liệu của mỗi dòng thành các cột
                    $columns = explode(':', $row);
                    // Thêm mỗi dòng vào mảng 2 chiều
                    $tempDSGheArray[] = $columns;
                }
            } else {
                // Nếu thất bại, hiển thị thông báo lỗi

            }
        }
    }
    if (isset($_POST['btn_themhang'])) {
        // Số cột hiện tại trong mảng
        $num_columns = count($tempDSGheArray[0]);

        // Thêm hàng mới vào mảng tempDSGheArray
        $new_row = array_fill(0, $num_columns, 0); // Tạo một hàng mới có giá trị mặc định là 0 cho mỗi ô
        $tempDSGheArray[] = $new_row; // Thêm hàng mới vào mảng tempDSGheArray
    }
    if (isset($_POST['btn_themcot'])) {
        // Thêm cột mới vào mảng tempDSGheArray
        foreach ($tempDSGheArray as &$row) {
            $row[] = 0; // Thêm dữ liệu mới cho cột mới, ở đây có thể là giá trị mặc định hoặc null tùy vào yêu cầu của bạn
        }
        unset($row); // Hủy tham chiếu cuối cùng
    }
    ?>
    <form id="form_room" action="#" method="post">

        <div class="quanlyphongchieu">
            <div>
                <span style="color: red; font-weight: bold; font-size: 18px;"><?php echo $error_message; ?></span>
            </div>
            <!-- Thêm combobox góc phải trên cùng -->
            <div class="combobox-wrapper">
                <label style="font-size: 16px;font-weight: bold;">Chọn phòng</label>
                <select id="ds_room" name="ds_room" style="font-size: 16px;">
                    <?php foreach ($cinemaRooms as $room) : ?>
                        <?php if ($room->id == $firtroom->id) { ?>
                            <option style="font-size: 16px;" value="<?php echo $room->id ?>" selected>
                                <?php echo $room->TenPhong ?>
                            </option>
                        <?php } else { ?>
                            <option style="font-size: 16px;" value="<?php echo $room->id ?>">
                                <?php echo $room->TenPhong ?>
                            </option>
                        <?php } ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="qun-l-phng-chiu-wrapper">
                <h2 class="qun-l-phng">Quản lý phòng chiếu</h2>
            </div>
            <section class="f-r-a-m-e">
                <div class="ten-phong-parent">
                    <div class="ten-phong">
                        <b class="loai-phong">ID:</b>
                        <input type="text" class="txtten" id="id_room" name="id_room" value="<?php echo $firtroom->id ?>" readonly placeholder="Mã sẽ được thêm tự động" />
                    </div>

                    <div class="ten-phong">
                        <b class="loai-phong">Tên phòng:</b>
                        <input id="ten_phong" name="ten_phong" type="text" class="txtten" value="<?php echo $firtroom->TenPhong ?>" />
                    </div>
                    <div class="ten-phong1">
                        <b class="ten-phong-child">Loại phòng:</b>
                        <select class="txtloai" id="loai_phong" name="loai_phong">
                            <?php
                            // Mảng các tùy chọn
                            $options = array("2D", "3D", "4DX");

                            // Lặp qua từng tùy chọn
                            foreach ($options as $option) {
                                // Kiểm tra xem giá trị của tùy chọn có trùng khớp với $firstroom->Loaiphong không
                                if ($option == $firtroom->LoaiPhong) {
                                    // Nếu có, thêm thuộc tính selected
                                    echo "<option value='$option' selected>$option</option>";
                                } else {
                                    // Nếu không, không thêm thuộc tính selected
                                    echo "<option value='$option'>$option</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="txt-loai">
                        <div class="tinh-trang">
                            <b class="tnh-trng">Tình trạng:</b>
                        </div>
                        <div class="danh-gia-trang-thai">
                            <input class="radiodc" type="radio" id="dang_chieu" name="trang_thai" value="Đang hoàn thiện" <?php echo ($firtroom->TinhTrang == 'Đang hoàn thiện') ? 'checked' : ''; ?>>
                            <label for="dang-chieu">Đang hoàn thiện</label>
                        </div>
                        <div class="danh-gia-trang-thai">
                            <input class="radioss" type="radio" id="san_sang" name="trang_thai" value="Sẵn sàng" <?php echo ($firtroom->TinhTrang == 'Sẵn sàng') ? 'checked' : ''; ?>>
                            <label for="san-sang">Sẵn sàng</label>
                        </div>

                    </div>
                </div>
            </section>

            <section class="ghe-vip">
                <div class="ghe-doi">
                    <button class="btnthem" type="submit" name="add_room">Thêm</button>
                    <button class="btnsua" type="submit" name="edit_room">Sửa</button>
                    <button class="btnxoa" type="submit" name="delete_room" onclick="confirmDelete(event)">Xóa</button>
                    <button id="btn_themhang" class="btnthemhang" type="submit" name="btn_themhang">Thêm một hàng ghế</button>
                    <button id="btn_themcot" class="btnthemcot" type="submit" name="btn_themcot">Thêm một cột ghế</button>
                    <b class="danh-sch-gh">Danh sách ghế</b>
                </div>

                <div>
                    <!-- Màn chiếu -->
                    <div class="screen">Màn hình phòng chiếu</div>

                    <select style="margin-right: 10px;" id="cbb_loaighe" name="cbb_loaighe">
                        <?php foreach ($chairs as $chair) : ?>
                            <option value="<?php echo $chair->HinhAnh ?>">
                                <?php echo $chair->TenGhe ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- <button class="btn_datlam" type="submit" name="dat_lam">Hủy ghế</button> -->
                    <!-- <button class="btn_loaighe">Chỉnh sửa loại ghế</button> -->
                    <div class="table-parent">
                        <table class="table1">
                            <?php
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th></th>';
                            for ($i = 0; $i < count($tempDSGheArray[0]); $i++) {
                                echo '<th>' . $i . '</th>';
                            }
                            echo '</tr>';
                            echo '</thead>';

                            echo '<tbody>';
                            // Biến lưu giá trị của chữ cái, bắt đầu từ 'A'
                            $char = 'A';

                            // Hiển thị các hàng trong bảng
                            foreach ($tempDSGheArray as $rowData) {
                                // Hiển thị hàng mới
                                echo '<tr>';
                                echo '<th>' . $char++ . '</th>'; // Hiển thị chữ cái và tăng giá trị của biến $char
                                foreach ($rowData as $cell) {
                                    $found = false; // Biến cờ để kiểm tra xem có ghế tương ứng không
                                    foreach ($chairs as $chair) {
                                        if ($cell == $chair->id) { // Nếu tìm thấy ghế có ID trùng khớp
                                            echo '<td><img class="seat" src="./assets/img/PhongChieu/' . $chair->HinhAnh . '"></td>'; // Hiển thị hình ảnh của ghế
                                            $found = true; // Đặt cờ thành true để đánh dấu là đã tìm thấy ghế
                                            break; // Thoát khỏi vòng lặp foreach vì đã tìm thấy ghế
                                        }
                                    }
                                    if (!$found) { // Nếu không tìm thấy ghế tương ứng với ID của ô
                                        echo '<td><img class="seat" src="./assets/img/PhongChieu/ghe_mac_dinh.png"></td>'; // Hiển thị hình ảnh mặc định
                                    }
                                }
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            ?>
                        </table>

                    </div>
                    <div class="ds_giaithich">
                        <div class="item_giaithich" style="background-color: darkgrey;"></div>
                        <label>Đã chọn</label>
                        <img class="item_giaithich" src="./assets/img/PhongChieu/ghe_mac_dinh.png">
                        <label>Chưa chọn</label>
                        <img class="item_giaithich" src="./assets/img/PhongChieu/ghe_thuong.png">
                        <label>Ghế thường</label>
                        <img class="item_giaithich" src="./assets/img/PhongChieu/ghe_vip.png">
                        <label>Ghế vip</label>
                        <img class="item_giaithich" src="./assets/img/PhongChieu/ghe_doi.png">
                        <label>Ghế đôi</label>
                    </div>
                </div>
            </section>

        </div>

        <input type="hidden" id="tempDSGheArray" name="tempDSGheArray" value="<?php echo htmlentities(json_encode($tempDSGheArray)); ?>">

    </form>
    <script>
        document.getElementById('ds_room').onchange = function() {
            // Submit form khi có sự thay đổi
            document.getElementById('form_room').submit();
        };

        // Lấy danh sách các ô trong bảng
        var cells = document.querySelectorAll('tbody tr td');

        // Lắng nghe sự kiện click cho từng ô
        cells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                // Lấy giá trị của ô hiện tại
                var cellValue = this.textContent;
                // Lấy giá trị được chọn trong combobox
                var img_chair = document.getElementById('cbb_loaighe').value;
                // Thay đổi hình ảnh của ô thành hình ảnh của ghế được chọn
                this.innerHTML = '<img class="seat" src="./assets/img/PhongChieu/' + img_chair + '">';

                // Sau khi thay đổi, cập nhật lại giá trị của input ẩn tempDSGheArray
                updateTempDSGheArray();
            });
        });
        // Hàm để cập nhật giá trị của input ẩn tempDSGheArray
        function updateTempDSGheArray() {
            // Lấy danh sách các ô trong bảng ghế
            var cells = document.querySelectorAll('tbody tr td');
            var tempDSGheArray = [];
            // Lặp qua từng hàng trong bảng
            cells.forEach(function(cell, index) {
                // Lấy giá trị của ô hiện tại
                var cellValue = cell.querySelector('img') ? cell.querySelector('img').getAttribute('src') : ''; // Lấy đường dẫn hình ảnh ghế

                var chairs = <?php echo json_encode($chairs); ?>;
                var chairId;
                chairs.forEach(function(chair) {
                    // So sánh đường dẫn hình ảnh của ghế với HinhAnh của từng chair
                    if (cellValue === './assets/img/PhongChieu/' + chair.HinhAnh) {
                        chairId = chair.id;
                    }
                });
                // Tính toán chỉ số hàng và cột
                var row = Math.floor(index / <?php echo count($tempDSGheArray[0]); ?>);
                var column = index % <?php echo count($tempDSGheArray[0]); ?>;

                // Kiểm tra xem dòng có tồn tại trong mảng tempDSGheArray chưa
                if (!tempDSGheArray[row]) {
                    tempDSGheArray[row] = [];
                }

                // Lưu giá trị của ô vào mảng tempDSGheArray
                tempDSGheArray[row][column] = chairId;
                // Lưu giá trị của ô vào mảng tempDSGheArray
                tempDSGheArray[row][column] = chairId || 0; // Nếu chairId là null thì gán giá trị 0

                // Thay thế các giá trị null thành 0
                tempDSGheArray[row] = tempDSGheArray[row].map(function(value) {
                    return value || 0;
                });
            });

            // Cập nhật giá trị của input ẩn tempDSGheArray
            document.getElementById('tempDSGheArray').value = JSON.stringify(tempDSGheArray);
        }


        document.getElementById('btn_themcot').addEventListener('click', function() {
            // Thêm cột mới vào bảng HTML
            var table = document.querySelector('.table1');
            var rowCount = table.rows.length;
            for (var i = 0; i < rowCount; i++) {
                var cell = table.rows[i].insertCell(-1);
                cell.innerHTML = '<img class="seat" src="./assets/img/PhongChieu/ghe_mac_dinh.png">'; // Thêm hình ảnh mặc định cho ô mới
            }
            // Cập nhật input ẩn tempDSGheArray
            updateTempDSGheArray();
        });
        document.getElementById('btn_themhang').addEventListener('click', function() {
            // Thêm hàng mới vào bảng HTML
            var table = document.querySelector('.table1');
            var numColumns = table.rows[0].cells.length; // Số lượng cột trong bảng
            var newRow = table.insertRow(-1); // Thêm hàng mới vào cuối bảng
            for (var i = 0; i < numColumns; i++) {
                var cell = newRow.insertCell(i);
                cell.innerHTML = '<img class="seat" src="./assets/img/PhongChieu/ghe_mac_dinh.png">'; // Thêm hình ảnh mặc định cho ô mới
            }

            // Cập nhật input ẩn tempDSGheArray
            updateTempDSGheArray();
        });
        function confirmDelete(event) {
            if (!confirm('Bạn có chắc chắn muốn xóa phòng này không?')) {
                event.preventDefault(); // Ngăn chặn form submit nếu người dùng nhấn "Cancel"
            }
        }
    </script>


</body>

</html>