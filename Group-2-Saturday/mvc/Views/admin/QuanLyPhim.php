<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="./assets/css/QuanLyPhim.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <?php
    session_start();
    require_once './mvc/Controllers/Bill.php';
    require_once './mvc/Controllers/DetailBill.php';
    require_once './mvc/Controllers/QuanLyPhim.php';
    // Khởi tạo đối tượng FilmController
    $filmController = new QuanLyPhim();
    $billcontroller = new Bill();
    $detailbillcontroller = new DetailBill();
    $bills = $billcontroller->getAllBills();
    $detailbills = $detailbillcontroller->getAllDetailBills();

    // $films = $filmController->getFilms();
    // Kiểm tra xem session 'search_results' có tồn tại không
    if (isset($_SESSION['search_results']) && !empty($_SESSION['search_results'])) {
        // Lấy kết quả từ session
        $search_results_serialized = $_SESSION['search_results'];

        // Unserialize các đối tượng FilmEntity
        $films = $search_results = array_map(function ($film) {
            return unserialize($film);
        }, $search_results_serialized);
    } else {
        $films = $filmController->getFilms();
    }
    // Chuyển đổi danh sách phim thành chuỗi JSON
    $films_json = json_encode($films);

    // Tính kích thước của danh sách phim hiện tại
    $currentSize = end($films)->id;
    // Kiểm tra xem có yêu cầu thêm phim không
    if (isset($_POST['add_film'])) {
        $films = $filmController->getFilms();
        $currentSize = end($films)->id;
        $error_message = "";
        // Tăng kích thước danh sách phim lên 1 để tạo ID mới
        $currentSize++;
        $tenPhim = $_POST['ten_phim'];
        $daoDien = $_POST['dao_dien'];
        $namSX = $_POST['nam_sx'];
        $noiSX = $_POST['noi_sx'];
        $DVien = $_POST['dien_vien'];
        $thoiLuong = $_POST['thoi_luong'];
        $doTuoi = $_POST['do_tuoi'];
        $noiDung = $_POST['noi_dung'];
        $url_film = $_POST['url'];
        // Định dạng lại ngày theo định dạng "ngày/tháng/năm"
        $ngayKC = date("d/m/Y", strtotime($_POST['khoi_chieu']));
        $ngayKT = date("d/m/Y", strtotime($_POST['ket_thuc']));
        // Lấy dữ liệu từ các checkbox đã chọn và nối chúng lại thành một chuỗi
        $theLoai = isset($_POST['theloai']) ? implode(",", $_POST['theloai']) : "";

        if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['file-input'];
            $fileName = file_get_contents($image['tmp_name']); // Đọc dữ liệu hình ảnh
        } else {
            $fileName = "";
            $error_message = "Please upload an image for the movie!";
        }
        // Kiểm tra nếu độ tuổi không phải là số
        if (!is_numeric($doTuoi)) {
            $error_message = "Độ tuổi phải là một số!";
        }
        if (!is_numeric($namSX)) {
            $error_message = "Năm sản xuất phải là một số!";
        }
        // Kiểm tra định dạng của thời lượng
        if (!preg_match('/^\d+ phút$/', $thoiLuong)) {
            $error_message = "Thời lượng phải có định dạng số nguyên + ' phút' (Ví dụ: 112 phút)!";
        }
        if (!empty($tenPhim) && !empty($daoDien) && !empty($namSX) && !empty($noiSX) && !empty($DVien) && !empty($thoiLuong) && !empty($doTuoi) && !empty($noiDung) && !empty($url_film) && !empty($ngayKC) && !empty($ngayKT) && !empty($theLoai) && !empty($fileName) && empty($error_message)) {
            // Lấy dữ liệu từ biểu mẫu HTML
            $filmData = array(
                'id' => $currentSize, // Sử dụng kích thước mới của danh sách phim làm ID mới
                'TenPhim' => $tenPhim,
                'DV' => $DVien,
                'TheLoai' => $theLoai,
                'ThoiLuong' => $thoiLuong,
                'NgayKC' => $ngayKC,
                'NgayKT' => $ngayKT,
                'NoiSX' => $noiSX,
                'DaoDien' => $daoDien,
                'NamSX' => $namSX,
                'NoiDung' => $noiDung,
                'HinhAnh' => $fileName,
                'DoTuoi' => $doTuoi,
                'url_film' => $url_film

                // Lấy dữ liệu từ các trường biểu mẫu và gán vào mảng $filmData
            );
            // Thêm phim mới vào cơ sở dữ liệu
            $result = $filmController->addFilm($filmData);
            $filmDatas = array(
                'TenPhim' => $tenPhim,
                'DV' => $DVien,
                'TheLoai' => $theLoai,
                'ThoiLuong' => $thoiLuong,
                'NgayKC' => $ngayKC,
                'NgayKT' => $ngayKT,
                'NoiSX' => $noiSX,
                'DaoDien' => $daoDien,
                'NamSX' => $namSX,
                'NoiDung' => $noiDung,
                'HinhAnh' => $fileName,
                'DoTuoi' => $doTuoi,
                'url_film' => $url_film
            );

            // Thực hiện cập nhật thông tin phim trong cơ sở dữ liệu
            $filmController->updateFilm($currentSize++, $filmDatas);
            if ($result) {
                // Sau khi thêm phim mới thành công, cập nhật lại danh sách phim và hiển thị
                $films = $filmController->getFilms();
                // Chuyển đổi danh sách phim thành chuỗi JSON
                $films_json = json_encode($films);
                // Cập nhật kích thước của danh sách phim sau khi đã thêm mới
                $currentSize = end($films)->id;
            } else {
            }
        } else {
            if (empty($error_message)) {
                $error_message = "Vui lòng điền đầy đủ thông tin vào các trường!";
            }
        }
    }

    // Kiểm tra xem có yêu cầu cập nhật phim không
    if (isset($_POST['edit_movie'])) {
        // Lấy ID của phim từ biểu mẫu HTML
        $filmId = $_POST['film_id'];
        // Lấy dữ liệu từ biểu mẫu HTML
        $tenPhim = $_POST['ten_phim'];
        $daoDien = $_POST['dao_dien'];
        $namSX = $_POST['nam_sx'];
        $noiSX = $_POST['noi_sx'];
        $DVien = $_POST['dien_vien'];
        $thoiLuong = $_POST['thoi_luong'];
        $doTuoi = $_POST['do_tuoi'];
        $noiDung = $_POST['noi_dung'];
        $url_film = $_POST['url'];
        if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['file-input'];
            $fileName = file_get_contents($image['tmp_name']); // Đọc dữ liệu hình ảnh
        } else {
            foreach ($films as $film) {
                if ($filmId == $film->id) {
                    $fileName = $film->HinhAnh;
                }
            }
        }
        // Định dạng lại ngày theo định dạng "ngày/tháng/năm"
        $ngayKC = date("d/m/Y", strtotime($_POST['khoi_chieu']));
        $ngayKT = date("d/m/Y", strtotime($_POST['ket_thuc']));
        // Lấy dữ liệu từ các checkbox đã chọn và nối chúng lại thành một chuỗi
        $theLoai = isset($_POST['theloai']) ? implode(",", $_POST['theloai']) : "";
        // Kiểm tra nếu độ tuổi không phải là số
        if (!is_numeric($doTuoi)) {
            $error_message = "Độ tuổi phải là một số!";
        }
        if (!is_numeric($namSX)) {
            $error_message = "Năm sản xuất phải là một số!";
        }
        // Kiểm tra định dạng của thời lượng
        if (!preg_match('/^\d+ phút$/', $thoiLuong)) {
            $error_message = "Thời lượng phải có định dạng số nguyên + ' phút' (Ví dụ: 112 phút)!";
        }
        if (!empty($filmId) && !empty($tenPhim) && !empty($daoDien) && !empty($namSX) && !empty($noiSX) && !empty($DVien) && !empty($thoiLuong) && !empty($doTuoi) && !empty($noiDung) && !empty($url_film) && !empty($ngayKC) && !empty($ngayKT) && !empty($theLoai) && !empty($fileName) && empty($error_message)) {
            // Tạo một mảng chứa dữ liệu phim cần cập nhật
            $filmData = array(
                'TenPhim' => $tenPhim,
                'DV' => $DVien,
                'TheLoai' => $theLoai,
                'ThoiLuong' => $thoiLuong,
                'NgayKC' => $ngayKC,
                'NgayKT' => $ngayKT,
                'NoiSX' => $noiSX,
                'DaoDien' => $daoDien,
                'NamSX' => $namSX,
                'NoiDung' => $noiDung,
                'HinhAnh' => $fileName,
                'DoTuoi' => $doTuoi,
                'url_film' => $url_film
            );

            // Thực hiện cập nhật thông tin phim trong cơ sở dữ liệu
            $result = $filmController->updateFilm($filmId, $filmData);
            if ($result) {
                // Sau khi thêm phim mới thành công, cập nhật lại danh sách phim và hiển thị
                $films = $filmController->getFilms();
                // Chuyển đổi danh sách phim thành chuỗi JSON
                $films_json = json_encode($films);
                //thông báo thành công
            } else {
                //thông báo thất bại
            }
        } else {
            if (empty($error_message)) {
                $error_message = "Vui lòng điền đầy đủ thông tin vào các trường!";
            }
        }
    }


    // Kiểm tra xem có yêu cầu xóa phim không
    if (isset($_POST['delete_movie'])) {
        // Lấy ID của phim từ biểu mẫu HTML
        $filmId = $_POST['film_id'];

        $hasUnprocessedBill = false;
        // Kiểm tra xem lịch chiếu có bill chưa xử lý nào không
        foreach ($bills as $bill) {
            foreach ($detailbills as $detailbill) {
                if ($bill->Status == 'Chưa xử lý' && $detailbill->IDDetailBill == $bill->IDBill && $detailbill->IDMovie == $filmId) {
                    $hasUnprocessedBill = true;
                    break 2; // Thoát khỏi cả hai vòng lặp
                }
            }
        }

        if ($hasUnprocessedBill) {
            // Nếu có bill chưa xử lý, chỉ cập nhật thời gian chiếu của lịch chiếu này
            $newTime = date('d/m/Y', strtotime('-1 day'));
            foreach ($films as $film) {
                if ($film->id == $filmId) {
                    $tenPhim = $film->TenPhim;
                    $daoDien = $film->DaoDien;
                    $namSX = $film->NamSX;
                    $noiSX = $film->NoiSX;
                    $DVien = $film->DV;
                    $thoiLuong = $film->ThoiLuong;
                    $doTuoi = $film->DoTuoi;
                    $noiDung = $film->NoiDung;
                    $url_film = $film->url_film;
                    $fileName = $film->HinhAnh;
                    $ngayKC = $film->NgayKC;
                    $ngayKT = $newTime;
                    $theLoai = $film->TheLoai;
                    break;
                }
            }
            $filmData = array(
                'TenPhim' => $tenPhim,
                'DV' => $DVien,
                'TheLoai' => $theLoai,
                'ThoiLuong' => $thoiLuong,
                'NgayKC' => $ngayKC,
                'NgayKT' => $ngayKT,
                'NoiSX' => $noiSX,
                'DaoDien' => $daoDien,
                'NamSX' => $namSX,
                'NoiDung' => $noiDung,
                'HinhAnh' => $fileName,
                'DoTuoi' => $doTuoi,
                'url_film' => $url_film
            );

            // Gọi phương thức để cập nhật thời gian chiếu của lịch chiếu có ID là $scheduleId
            $result = $filmController->updateFilm($filmId, $filmData);
            if ($result) {
                $films = $filmController->getFilms();
                // Chuyển đổi danh sách phim thành chuỗi JSON
                $films_json = json_encode($films);
            } else {
                // Xử lý lỗi nếu cập nhật không thành công
            }
        } else {
            // //Xóa ở khóa ngoại trc
            // foreach ($bills as $bill) {
            //     foreach ($detailbills as $detailbill) {
            //         if ($bill->IDBill == $detailbill->IDDetailBill && $detailbill->IDMovie == $filmId) {
            //             $billcontroller->deleteBill($bill->IDBill);
            //         }
            //     }
            // }
            // foreach ($detailbills as $detailbill) {
            //     if ($detailbill->IDMovie == $filmId) {
            //         $detailbillcontroller->deleteDetailBill($detailbill->IDDetailBill);
            //     }
            // }
            // Xóa phim khỏi cơ sở dữ liệu
            $result = $filmController->deleteFilm($filmId);
            if ($result) {
                // Sau khi thêm phim mới thành công, cập nhật lại danh sách phim và hiển thị
                $films = $filmController->getFilms();
                // Chuyển đổi danh sách phim thành chuỗi JSON
                $films_json = json_encode($films);
                //thông báo thành công
            } else {
                //thông báo thất bại
            }
        }
    }

    // Xử lý khi nút tìm kiếm được nhấn
    if (isset($_POST['search_button'])) {
        unset($_SESSION['search_results']);
        $films = $filmController->getFilms();
        // Lấy dữ liệu từ trường nhập liệu
        $film_name = $_POST['ten_film'];
        $the_loai = $_POST['th_loai'];
        // Mảng lưu trữ kết quả tìm kiếm
        $search_results = array();

        if (!empty($film_name) && $the_loai == "Tất Cả") {
            foreach ($films as $film) {
                // Chuyển đổi tên phim và từ khóa tìm kiếm thành chữ thường không dấu để so sánh
                $film_lowercase = mb_strtolower($film->TenPhim, 'UTF-8');
                $keyword_lowercase = mb_strtolower($film_name, 'UTF-8');

                // Sử dụng hàm strpos để kiểm tra xem từ khóa có tồn tại trong tên phim không
                if (strpos($film_lowercase, $keyword_lowercase) !== false) {
                    // Nếu tìm thấy, thêm phim vào mảng kết quả
                    $search_results[] = $film;
                }
            }
        } else if (!empty($film_name) && $the_loai != "Tất Cả") {
            foreach ($films as $film) {
                $the_loai_array = explode(",", $film->TheLoai);

                if (in_array($the_loai, $the_loai_array)) {
                    // Chuyển đổi tên phim và từ khóa tìm kiếm thành chữ thường không dấu để so sánh
                    $film_lowercase = mb_strtolower($film->TenPhim, 'UTF-8');
                    $keyword_lowercase = mb_strtolower($film_name, 'UTF-8');

                    // Sử dụng hàm strpos để kiểm tra xem từ khóa có tồn tại trong tên phim không
                    if (strpos($film_lowercase, $keyword_lowercase) !== false) {
                        // Nếu tìm thấy, thêm phim vào mảng kết quả
                        $search_results[] = $film;
                    }
                }
            }
        } else if (empty($film_name) && $the_loai != "Tất Cả") {
            foreach ($films as $film) {
                $the_loai_array = explode(",", $film->TheLoai);

                if (in_array($the_loai, $the_loai_array)) {
                    $search_results[] = $film;
                }
            }
        } else {
            // Nếu không có từ khóa tìm kiếm, hiển thị tất cả các phim
            $search_results = $films;
        }


        // Hiển thị kết quả tìm kiếm
        if (!empty($search_results)) {
            $films = $search_results;
            // Serialize đối tượng FilmEntity thành một chuỗi trước khi lưu vào session
            $search_results_serialized = array_map(function ($film) {
                return serialize($film);
            }, $search_results);

            $_SESSION['search_results'] = $search_results_serialized;
        } else {
            $error_message_tk = "Không tìm thấy kết quả cho \"$film_name\"";
        }
    }
    ?>

    <form action="#" class="quanlyphim" method="post" enctype="multipart/form-data">
        <div>
            <span style="color: red; font-weight: bold;"><?php echo $error_message; ?></span>
        </div>
        <section class="movie-manager">
            <div class="qun-l-phim-wrapper">
                <h2 class="qun-l-phim">Quản lý phim</h2>
            </div>
            <div class="thumbnail-container-parent">
                <div class="thumbnail-container">
                    <img id="preview" class="hinhanhsp" src="" alt="Hình ảnh phim">
                </div>
                <div class="info-panel">
                    <div class="movie-code">
                        <div class="time-display">
                            <b class="m-phim">Mã phim:</b>
                        </div>
                        <input class="txtma" id="film_id" name="film_id" readonly placeholder="Mã sẽ được thêm tự động" />

                        <div class="thi-lng-wrapper">
                            <b class="thi-lng">Thời lượng:</b>
                        </div>
                    </div>
                    <div class="genre-label-parent">
                        <div class="genre-label">
                            <b class="tn-phim">Tên phim:</b>
                        </div>
                        <div class="production-info-panel">
                            <input class="txtten" name="ten_phim" type="text" />
                        </div>
                        <div class="ngy-kc-wrapper">
                            <b class="ngy-kc">Ngày KC:</b>

                        </div>
                    </div>
                    <div class="add-edit-delete-buttons-parent">
                        <div class="add-edit-delete-buttons">
                            <b class="m-t">DV:</b>
                        </div>
                        <div class="txtmota-parent">
                            <input class="txtmota" name="dien_vien" type="text" />
                            <b class="thumbnail">Ngày KT:</b>
                        </div>
                    </div>
                    <div class="genre-container">
                        <div class="description-field">
                            <b class="th-loi">Thể loại:</b>
                        </div>
                        <div class="theloai">
                            <label><input type="checkbox" name="theloai[]" value="Hành Động"> Hành Động</label>
                            <label><input type="checkbox" name="theloai[]" value="Hoạt Hình"> Hoạt Hình</label>
                            <label><input type="checkbox" name="theloai[]" value="Hài"> Hài</label>
                            <label><input type="checkbox" name="theloai[]" value="Viễn Tưởng"> Viễn Tưởng</label>
                            <label><input type="checkbox" name="theloai[]" value="Phiêu Lưu"> Phiêu Lưu</label>
                            <label><input type="checkbox" name="theloai[]" value="Gia Đình"> Gia Đình</label>
                            <label><input type="checkbox" name="theloai[]" value="Tình Cảm"> Tình Cảm</label>
                            <label><input type="checkbox" name="theloai[]" value="Tâm Lý"> Tâm Lý</label>
                            <label><input type="checkbox" name="theloai[]" value="Kinh Dị"> Kinh Dị</label>
                        </div>



                        <div class="genre-container-inner">
                            <div class="sn-xut-parent">
                                <b class="sn-xut">Sản xuất:</b>
                                <b class="o-din">Đạo diễn:</b>
                                <b class="nm-sx">Năm SX:</b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="details-panel">
                    <input class="txtthoiluong" name="thoi_luong" type="text" />
                    <input class="txtkc" name="khoi_chieu" type="date" />
                    <input class="txtkt" name="ket_thuc" type="date" />

                    <input class="txtkt1" name="noi_sx" type="text" />

                    <input class="txtkt2" name="dao_dien" type="text" />

                    <input class="txtkt3" name="nam_sx" type="text" />

                </div>


            </div>
        </section>
        <div class="adjustment-label-wrapper">

            <label for="file-input" class="adjustment-label">
                <img class="btnchinhanh-icon" alt="" src="./public/btnchinhanh.svg" />

                <label for="file-input" class="chnh-nh"><b> Chỉnh ảnh</b></label>
                <input type="file" class="chnh-nh" value="Chỉnh ảnh" name="file-input" id="file-input" style="visibility: hidden" onchange="previewImage(event)" />

            </label>

        </div>

        <div class="additional-details">
            <div>
                <label class="age-label" for="age-input">Giới hạn độ tuổi:</label>
                <input id="age-input" name="do_tuoi" class="age-input" type="number" min="0" max="100" value="18" />
            </div>
            <div>
                <label class="description-label" for="description-input">Nội dung:
                    <input id="input_url" name="url" placeholder="Nhập URL trailer phim!" type="text" style="width: 183px;" />
                </label>

                <textarea id="description-input" name="noi_dung" placeholder="Nhập nội dung phim!" class="description-input"></textarea>
                <label style="margin-top: -24px;margin-left: 120px;">Trailer phim khi được chọn</label>
                <iframe class="form_trailer" id="trailer-video" src="" frameborder="0" allowfullscreen style="display: block;" allow="autoplay"></iframe>

            </div>
        </div>
        <!-- Buttons for CRUD operations -->
        <div class="crud-buttons">

            <button class="btn_xuly" type="submit" name="add_film"><i class="fas fa-plus"></i> Thêm</button>
            <button class="btn_xuly" type="submit" name="edit_movie"><i class="fas fa-pencil-alt"></i> Sửa</button>
            <button class="btn_xuly" type="submit" name="delete_movie" onclick="confirmDelete(event)"><i class="fas fa-trash-alt"></i> Xóa</button>
        </div>

        <!-- Bảng hiển thị dữ liệu phim -->
        <div class="movie-table">
            <?php
            // Số lượng dòng hiển thị trên mỗi trang
            $perPage = 6;

            // Tổng số phim
            $totalFilms = count($films);

            // Tổng số trang
            $totalPages = ceil($totalFilms / $perPage);

            // Xác định trang hiện tại
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

            // Tính vị trí bắt đầu của dữ liệu trên trang hiện tại
            $start = ($current_page - 1) * $perPage;


            // Lấy danh sách phim cho trang hiện tại
            $films_on_page = array_slice($films, $start, $perPage);

            $films_json = json_encode($films_on_page);

            ?>
            <div style="margin-left: 410px; margin-bottom: 2px;">
                <!-- <input type="date" id="bd" name="bd">
                <input type="date" id="kt" name="kt"> -->
                <label for="th_loai">Thể loại phim:</label>
                <select id="th_loai" name="th_loai">
                    <option value="Tất Cả">Tất Cả</option>
                    <option value="Hành Động">Hành Động</option>
                    <option value="Hoạt Hình">Hoạt Hình</option>
                    <option value="Hài">Hài</option>
                    <option value="Viễn Tưởng">Viễn Tưởng</option>
                    <option value="Phiêu Lưu">Phiêu Lưu</option>
                    <option value="Gia Đình">Gia Đình</option>
                    <option value="Tình Cảm">Tình Cảm</option>
                    <option value="Tâm Lý">Tâm Lý</option>
                    <option value="Kinh Dị">Kinh Dị</option>
                </select>
                <input type="text" id="ten_film" name="ten_film" />
                <button type="submit" name="search_button" class="search-button">
                    <i class="fas fa-search"></i>
                    Tìm kiếm
                </button>
                <div>
                    <span style="color: red; font-weight: bold;"><?php echo $error_message_tk; ?></span>
                </div>
            </div>
            <!-- Bảng dữ liệu phim -->
            <table>
                <!-- Tiêu đề bảng -->
                <thead>
                    <!-- Tiêu đề cột -->
                    <tr>
                        <th>Mã phim</th>
                        <th>Tên phim</th>
                        <th>Thể loại</th>
                        <th>Thời lượng</th>
                        <th>Ngày KC</th>
                        <th>Ngày KT</th>
                        <th>Hình ảnh </th>
                    </tr>
                </thead>
                <!-- Dữ liệu phim -->
                <tbody>
                    <?php foreach ($films_on_page as $film) { ?>
                        <tr>
                            <td><?php echo $film->id; ?></td>
                            <td><?php echo $film->TenPhim; ?></td>
                            <td><?php echo $film->TheLoai; ?></td>
                            <td><?php echo $film->ThoiLuong; ?></td>
                            <td><?php echo $film->NgayKC; ?></td>
                            <td><?php echo $film->NgayKT; ?></td>
                            <td> <?php
                                    // Kiểm tra xem có dữ liệu ảnh không
                                    if (!empty($film->HinhAnh)) {
                                        // Hiển thị ảnh
                                        echo '<img src="data:image/jpeg;base64,' . base64_encode($film->HinhAnh) . '" alt="Hình ảnh" style="width: 50px; height: auto;">';
                                    } else {
                                        // Hiển thị một thông báo nếu không có ảnh
                                        echo 'Không có ảnh';
                                    }
                                    ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>


            <div class="pagination-wrapper">
                <?php
                // Hiển thị nút "<<" và "<"
                if ($current_page > 1) {
                    echo '<a href="./QuanLyPhim&page=1">&lt;&lt;</a>'; // Link đến trang đầu tiên
                    echo '<a href="./QuanLyPhim&page=' . ($current_page - 1) . '">&lt;</a>'; // Link đến trang trước
                }

                // Hiển thị số trang
                for ($page = 1; $page <= $totalPages; $page++) {
                    if ($page == $current_page) {
                        echo '<a href="./QuanLyPhim&page=' . $page . '">' . '<strong>' . $page . '</strong> ' . '</a> ';
                    } else {
                        echo '<a href="./QuanLyPhim&page=' . $page . '">' . $page . '</a>'; // Hiển thị các trang khác
                    }
                }

                // Hiển thị nút ">" và ">>"
                if ($current_page < $totalPages) {
                    echo '<a href="./QuanLyPhim&page=' . ($current_page + 1) . '">&gt;</a>'; // Link đến trang kế tiếp
                    echo '<a href="./QuanLyPhim&page=' . $totalPages . '">&gt;&gt;</a>'; // Link đến trang cuối cùng
                }
                ?>
            </div>
        </div>

    </form>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        var linkfilm;
        // Lấy danh sách tất cả các dòng trong bảng
        var rows = document.querySelectorAll(".movie-table tbody tr");
        // Biến lưu trữ dữ liệu phim
        <?php foreach ($films_on_page as $film) {
            $film->HinhAnh = base64_encode($film->HinhAnh);
        }
        $films_json = json_encode($films_on_page); ?>
        var filmsData = <?php echo $films_json ?>
        // Lặp qua từng dòng
        rows.forEach(function(row, index) {
            // Thêm sự kiện click cho từng dòng
            row.addEventListener("click", function() {
                // Lấy dữ liệu từ biến lưu trữ dựa trên chỉ số hàng
                var filmData = filmsData[index];
                linkfilm = filmData;
                console.log(filmData);

                // Gán dữ liệu vào các trường input tương ứng
                document.querySelector(".txtma").value = filmData.id;
                document.querySelector(".txtten").value = filmData.TenPhim;
                document.querySelector(".txtmota").value = filmData.DV;
                document.querySelector(".txtkt1").value = filmData.NoiSX;
                document.querySelector(".txtkt2").value = filmData.DaoDien;
                document.querySelector(".txtkt3").value = filmData.NamSX;
                document.querySelector(".age-input").value = filmData.DoTuoi;
                document.querySelector(".description-input").value = filmData.NoiDung;
                var inputUrl = document.getElementById("input_url");
                inputUrl.value = filmData.url_film;
                // Lấy chuỗi thể loại từ cột dữ liệuk
                var theLoaiString = filmData.TheLoai;

                // Tách chuỗi thành mảng các giá trị thể loại
                var theLoaiArray = theLoaiString.split(',');
                // Lấy tất cả các checkbox có tên là 'theloai[]'
                var checkboxes = document.querySelectorAll('input[name="theloai[]"]');
                // Lặp qua tất cả các checkbox và gỡ bỏ thuộc tính checked
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                // Lặp qua mảng các giá trị thể loại
                theLoaiArray.forEach(function(value) {
                    // Loại bỏ khoảng trắng ở đầu và cuối chuỗi giá trị
                    var trimmedValue = value.trim();

                    // Lặp qua tất cả các checkbox
                    checkboxes.forEach(function(checkbox) {
                        // So sánh giá trị checkbox với giá trị thể loại từ dữ liệu
                        if (checkbox.value === trimmedValue) {
                            // Đánh dấu checkbox nếu giá trị trùng khớp
                            checkbox.checked = true;
                        }
                    });
                });

                document.querySelector(".txtthoiluong").value = filmData.ThoiLuong;
                // Lấy giá trị ngày từ dữ liệu và chuyển đổi sang định dạng chuẩn
                var ngayKC = filmData.NgayKC.split('/').reverse().join('-');
                var ngayKT = filmData.NgayKT.split('/').reverse().join('-');
                // Gán giá trị ngày vào các input
                document.querySelector(".txtkc").value = ngayKC;
                document.querySelector(".txtkt").value = ngayKT;

                var imgSrc = 'data:image/jpeg;base64,' + filmData.HinhAnh;
                console.log(imgSrc);
                if (imgSrc) { // Kiểm tra xem có hình ảnh không
                    document.querySelector(".hinhanhsp").setAttribute("src", imgSrc);
                }
                // Loại bỏ lớp "selected-row" từ tất cả các dòng trong bảng
                rows.forEach(function(row) {
                    row.classList.remove("selected-row");
                });
                // Thêm lớp "selected-row" vào dòng được click
                this.classList.add("selected-row");
                // gán tên file ảnh khi click chọn item film
                // document.getElementById('imgSrc').value = linkfilm.HinhAnh;
                document.getElementById("trailer-video").src = linkfilm.url_film + "?&autoplay=1";
            });
        });

        function previewImage(event) {
            document.getElementById('preview').src = '';

            console.log('File input has changed.');
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        function confirmDelete(event) {
            if (!confirm('Bạn có chắc chắn muốn xóa phim này không?')) {
                event.preventDefault(); // Ngăn chặn form submit nếu người dùng nhấn "Cancel"
            }
        }
    </script>

</body>

</html>