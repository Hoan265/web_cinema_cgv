<!DOCTYPE html>
<html lang="en">
<?php
require_once './mvc/Controllers/QuanLyPhim.php';
require_once './mvc/Controllers/QuanLyGhe.php';
require_once './mvc/Controllers/QuanLyLichChieu.php';
require_once './mvc/Controllers/QuanLyPhongChieu.php';
require_once './mvc/Controllers/Foods.php';
require_once './mvc/Controllers/promotion.php';
require_once './mvc/Controllers/DetailBill.php';
require_once './mvc/Controllers/Account.php';

$account = new Account();
$phimctl = new QuanLyPhim();
$lichchieu = new QuanLyLichChieu();
$ghe = new QuanLyGhe();
$phongchieu = new QuanLyPhongChieu();
$food = new Foods();
$promotions = new Promotion();
$detailbill = new DetailBill();
$idPhong = 0;
$idUser = $account->getIDbyEmail($_SESSION['email_client'])->getId();
?>
<!-- <head> -->
<!-- <meta charset="UTF-8"> -->
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> -->
<!-- <link rel="stylesheet" href="./assets/css/style_booking.css">  -->
<!-- <title>Movie Ticket Booking Website </title>  -->
<!-- </head>  -->

<?php
// $id_movie = isset($_GET['id']) ? $_GET['id'] : 0;
$movie = $phimctl->getDetailFilmById($_SESSION['data_id']);
// $loadone_showtime = $lichchieu -> loadone_showtime_by_id_movie($id_movie);
$list_combo = $food->getAll();
$list_custom_seat = $detailbill->getAllDetailBills();
?>

<body style="background-color: black;"">
<div class=" alert alert-danger" role="alert" style="display: none;">
    Thanh toán không thành công
    </div>
    <div class="alert alert-success" id="success" role="alert" style="display: none;">
        Đã đặt vé thành công
    </div>
    <div class=" book">
        <div class="popup_box">
            <i class="fas fa-exclamation"></i>
            <h1>Bạn sẽ thanh toán vé xem phim</h1>
            <label>Bạn có chắc chắn không?</label>
            <div class="btns">
                <a href="javascript:void(0)" class="btn1">Không</a>
                <a href="javascript:void(0)" class="btn2">Chắc chắn</a>
            </div>
        </div>
        <div class="left">
            <img src="./assets/img/poster/<?php
                                            echo $movie['HinhAnh']
                                            ?>" alt="" id="poster">
            <div class="play">
                <i class="bi bi-play-fill" id="play"></i>
            </div>
            <form novalidate="novalidate" action="./vnpay_create_payment" id="frmCreateOrder" class="cont form-add-bill" method="post">
                <h2><?php
                    echo $movie['TenPhim']
                    ?></h2>
                <hr>
                <h5>Suất chiếu</h5>
                <input type="text" name="hours" id="hours" value="" readonly>
                <input type="text" name="month" id="month" value="" readonly>
                <h5>Rạp</h5>
                <input type="text" name="cinema" id="cinema" value="CGV" readonly>
                <input type="text" hidden name="id_movie" id="id_movie" value="<?php echo $movie['id'] ?>" readonly>
                <h5>Phòng</h5>
                <input type="text" name="room" id="room" value="" readonly>
                <h5>Vị trí ghế</h5>
                <input type="text" name="seats" id="seats" value="" readonly>
                <h5>Giá vé</h5>
                <input type="text" name="tickets" id="tickets" value="0" readonly>
                <h5>Giá combo</h5>
                <input type="text" name="combos" id="combos" value="0" readonly>
                <div>
                    <h5>Ưu đãi</h5>
                    <select class="form-select" name="id_voucher" id="id_voucher">
                        <option data-percent="0" value="0">Ưu đãi</option>
                        <?php
                        foreach ($promotions->getData() as $pro) {
                            echo '<option data-percent="' . $pro['Percent'] . '" value="' . $pro['IDPromotion'] . '">' . $pro['NamePromotion'] . " - " . $pro['Percent'] . "%" . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <hr>
                <h5>Giám giá</h5>
                <input type="text" name="giamgia" id="giamgia" value="0" readonly>
                <h3>Tổng tiền</h3>
                <div class="form-group">
                    <input class="form-control" data-val="true" data-val-number="The field amount must be a number." data-val-required="The amount field is required." id="amount" max="100000000" min="10000" name="amount" type="number" value="0" readonly style="color:white;background-color: transparent;border: none; " />
                </div>
                <!-- <h4>Chọn phương thức thanh toán</h4> -->
                <div class="form-group">
                    <!-- <input type="radio" id="bankCode1" name="bankCode" value="" checked="true">
                    <label for="bankCode1">Cổng thanh toán VNPAYQR</label><br>
                    <h5 hidden>Cách 2: Tách phương thức tại site của đơn vị kết nối</h5>
                    <input hidden type="radio" id="bankCode2" name="bankCode" value="VNPAYQR">
                    <label hidden for="bankCode2">Thanh toán bằng ứng dụng hỗ trợ VNPAYQR</label><br>
                    <input type="radio" id="bankCode3" name="bankCode" value="VNBANK">
                    <label for="bankCode3">Thanh toán tại quầy vé</label><br> -->
                    <!-- <label for="bankCode3">Thanh toán qua thẻ ATM/Tài khoản nội địa</label><br> -->
                    <!-- <input hidden type="radio" id="bankCode4" name="bankCode" value="INTCARD">
                    <label hidden for="bankCode4">Thanh toán qua thẻ quốc tế</label><br> -->
                </div>
                <hr>
                <input type="hidden" name="id_account" id="id_account" value="<?php
                                                                                $idUser
                                                                                ?>">
                <input type="hidden" name="name_movie" id="name_movie" value="<?php
                                                                                echo $movie['TenPhim']
                                                                                ?>">
                <input type="hidden" name="email" id="email" value="<?php
                                                                    $_SESSION['email_client']
                                                                    ?>">
                <input type="hidden" name="name_clinet" id="name_clinet" value="<?php
                                                                                // echo $name_clinet 
                                                                                ?>">
                <!-- <input type="submit" name="redirect" class="btn btn-default" value="Thanh toán" hidden> -->
                <button type="submit" name="redirect" class="btn btn-default btn-primary click" id="btn-add-bill">Thanh toán</button>



            </form>

            <style>
                input {
                    background-color: #2E3037;
                    outline: none;
                    border: none;
                    color: white;
                }
            </style>
        </div>
        <div class="right">
            <video src="./assets/video/Gadar2 Official Trailer - 11th August - Sunny Deol - Ameesha Patel - Anil Sharma - Zee Studios.mp4" id="video"></video>
            <div class="head_time">
                <h1 id="title"><?php
                                echo $movie['TenPhim']
                                ?></h1>
                <div class="time">
                    <h6><i class="bi bi-clock"></i><?php
                                                    echo $movie['ThoiLuong']
                                                    ?></h6>
                    <select class="btn min" name="id_room" id="id_room">
                        <option value="">Chọn Phòng</option>
                        <?php
                        $list_room = $phongchieu->getAllCinemaRooms(); //get tất cả phòng chiếu
                        $loadone_showtime = $lichchieu->loadone_showtime_by_id_movie($movie['id']); //get thời gian chiếu phim ở phòng nào
                        $displayedRoomNames = array();
                        // mảng để lưu trữ tên phòng đã hiển thị
                        foreach ($loadone_showtime as $room) {
                            $name = "";
                            foreach ($list_room as $room_item) {
                                if ($room_item->id == $room->PhongChieu) {
                                    $name = $room_item->TenPhong;
                                    break;
                                }
                            }
                            // chỉ hiển thị tùy chọn nếu tên phòng chưa được hiển thị trước đó
                            if (!in_array($name, $displayedRoomNames)) {
                                echo '<option value="' . $room->PhongChieu . '">' . $name . '</option>';
                                $displayedRoomNames[$room->PhongChieu] = $name; // Add the room name to the displayed list
                            }
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="date_type">
                <div id="list-date">

                </div>
                <?php
                // // Set the starting date as the current date
                // $currentDate = new DateTime();
                // // Create an array to store the day abbreviations
                // $dayAbbreviations = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
                // // Output the left card and date points
                // echo '<div class="left_card">';
                // echo '<div class="card_month crd">';
                // // Loop to generate the next 14 date points (for two weeks)
                // for ($i = 0; $i < 6; $i++) {
                //     // Output the day abbreviation and day of the month
                //     echo '<li>';
                //     echo '<h6 class="date_point not_pick">' . $dayAbbreviations[$currentDate->format('N') - 1] . '</h6>';
                //     echo '<h6 class="date_point">' . $currentDate->format('d/m') . '</h6>';
                //     echo '</li>';
                //     // Move to the next day
                //     $currentDate->add(new DateInterval('P1D'));
                // }
                // echo '</div>';
                // echo '</div>';
                ?>
                <div class="right_card">
                    <h6 class="title">Chọn suất chiếu</h6>
                    <div class="card_month crd required-div" id="showTimes" required>
                        <?php
                        // $showTimes = load_showtime_by_id_room($room['id_room']);
                        // Tien comment
                        // foreach ($showTimes as $time) {
                        //     echo '<li>';
                        //     echo '<h6></h6>';
                        //     echo '<h6>' . explode("T", $time->ThoiGianChieu)[1] . '</h6>';
                        //     echo '</li>';
                        // }
                        ?>
                    </div>
                </div>
            </div>
            <div class="screen" id="screen">
                Màn hình
            </div>
            <div class="chair" id="chair">
                <!-- add list ghe  -->
            </div>
            <style>
                <?php
                // Tien comment 
                $showTimes = $lichchieu->loadone_showtime_by_id_movie($movie['id']);
                // $showTimes2 = $detailbill->getAllByIDPhim($movie['id']);
                // foreach ($list_custom_seat as $seat) {
                //     $seatString = $seat['custom_seat'];
                //     $seats = explode(',', $seatString);
                //     $seatClasses = array_map(function ($seat) {
                //         return ".red" . strtolower($seat);
                //     }, $seats);
                //     $resultString = implode(',', $seatClasses);
                //     echo $resultString;
                //     echo '.red' . $resultString . '{
                //             pointer-events: none;
                //             background-color: red !important;
                //         }';
                // }
                ?>
            </style>

            <div>
                <div class="details" id="det">
                    <div class="details_chair">
                        <li>Có thể đặt</li>
                        <li>Ghế thường</li>
                        <li>Ghế VIP</li>
                        <li>Ghế đôi</li>
                        <li>Đã có người đặt</li>
                        <li>Bạn chọn</li>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table border="1" class="datatable table table-stripped dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>TÊN COMBO</th>
                                                <th>ẢNH MINH HỌA</th>
                                                <th>GIÁ COMBO</th>
                                                <th colspan="1">SIZE</th>
                                                <th colspan="1">CHỌN COMBO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($list_combo as $combo) {
                                                // extract(($combo));
                                                if (is_array($combo)) {
                                                    $hinhpath = "./assets/img/Foods/" . $combo['img'];
                                                    if (is_file($hinhpath)) {
                                                        $img_combo = "<img src='" . $hinhpath . "' height='80px'>";
                                                    } else {
                                                        $img_combo = "no photo";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>" . $combo["Name"] . "</td>";
                                                    echo "<td>" . $img_combo . "</td>";
                                                    echo "<td>" . $combo["Price"] . "</td>";
                                                    echo "<td>" . $combo["Ten"] . "</td>";
                                                    echo "<td class='edit-delete-btn'>";
                                                    echo ' <button>-</button>';
                                                    echo '<input type="text" name="quantity" value="0" min="0" max="1000" data-id="' . $combo['IdFoods'] . '">';
                                                    echo ' <button>+</button>';
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div hidden>
                                        <tbody id="list-foods " hidden>
                                        </tbody>
                                        <?php if (isset($data["Plugin"]["pagination"]))
                                            require "./mvc/Views/inc/admin/pagination.php" ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script> -->
        <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.js" integrity="sha512-wkHtSbhQMx77jh9oKL0AlLBd15fOMoJUowEpAzmSG5q5Pg9oF+XoMLCitFmi7AOhIVhR6T6BsaHJr6ChuXaM/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
        <!--   -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            var idST = -1;;
            document.getElementById('btn-add-bill').addEventListener('click', function() {
                // var seatsInput = document.querySelector("input[name='seats']");
                // var hoursInput = document.querySelector("input[name='hours']");
                // var roomInput = document.querySelector("input[name='room']");
                // var monthInput = document.querySelector("input[name='month']");
                // var alertDiv = document.querySelector(".alert");
                // if (roomInput.value.trim() === '') {
                //     alertDiv.innerHTML = "Chưa chọn phòng, vui lòng chọn phòng";
                //     alertDiv.classList.add("alert-danger");
                //     alertDiv.style.display = "block";
                //     setTimeout(function() {
                //         alertDiv.style.display = "none";
                //     }, 2000);
                // } else if (monthInput.value.trim() === '') {
                //     alertDiv.innerHTML = "Chưa chọn ngày xem, vui lòng làm theo đúng thứ tự";
                //     alertDiv.classList.add("alert-danger");
                //     alertDiv.style.display = "block";
                //     setTimeout(function() {
                //         alertDiv.style.display = "none";
                //     }, 2000);
                // } else if (hoursInput.value.trim() === '') {
                //     alertDiv.innerHTML = "Chưa chọn thời gian, vui lòng chọn thời gian";
                //     alertDiv.classList.add("alert-danger");
                //     alertDiv.style.display = "block";
                //     setTimeout(function() {
                //         alertDiv.style.display = "none";
                //     }, 2000);
                // } else if (seatsInput.value.trim() === '') {
                //     alertDiv.innerHTML = "Chưa chọn ghế, vui lòng chọn ghế";
                //     alertDiv.classList.add("alert-danger");
                //     alertDiv.style.display = "block";
                //     setTimeout(function() {
                //         alertDiv.style.display = "none";
                //     }, 2000);
                // } else {
                //     $('.popup_box').css("display", "block");
                //     // console.log($("#hours").val());
                //     // Nếu các điều kiện không khớp, bạn có thể thực hiện hành động tiếp theo ở đây, ví dụ chuyển hướng đến trang thanh toán.
                //     // window.location.href = "trang-thanh-toan.html";
                // }
                // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                // document.body.scrollTop = 0;
                $.ajax({
                        type: "post",
                        url: "./Bill/add",
                        data: {
                            idcustommer: '<?php echo $idUser ?>',
                            idprom: $("#id_voucher").val(),
                            totalPrice: $("#amount").val(),
                            //detailbill
                            idmovie: $("#id_movie").val(),
                            idseat: 1,
                            idroom: $("#id_room").val(),
                            idshowtime: idST,
                            listidfood: dataList,
                            customseats: $("#seats").val(),
                        },
                        success: function(response) {
                            console.log('reponse : ' + response)
                            // clearInputFields();
                            // var successAlert = document.querySelector(".alert-success");
                            // console.log(successAlert);
                            // successAlert.style.display = "block";

                            // // Ẩn cảnh báo sau 2 giây
                            // setTimeout(function() {
                            //     successAlert.style.display = "none";
                            // }, 2000);
                            // Lấy phần tử có id="success"
                            // var alertBox = document.getElementById("success");

                            // Hiển thị phần tử
                            // alertBox.style.display = "block";

                            // Sau 2 giây, ẩn phần tử
                            //Tienaaa comment
                            // setTimeout(function() {
                                // alertBox.style.display = "none";
                            //     window.location.href = "./home"
                            // }, 2000);
                            // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                            // document.body.scrollTop = 0;                            

                        },
                    });
            });

            var dataList = [];

            function getIDFoods() {
                dataList = [];
                // Lấy tất cả các thẻ input có name là "quantity"
                var inputs = document.querySelectorAll('input[name="quantity"]');

                // Lặp qua từng thẻ input
                inputs.forEach(function(input) {
                    // Lấy giá trị của thuộc tính "value"
                    var value = parseInt(input.value);
                    // Kiểm tra nếu giá trị lớn hơn 0
                    if (value > 0) {
                        // Lấy giá trị của thuộc tính "data-id"
                        var dataId = input.getAttribute('data-id');
                        // Thêm giá trị data-id vào mảng
                        dataList.push(dataId);
                    }
                });

                console.log('dataList là')
                console.log(dataList)
            }

            var dataPercent = 0;
            var valueIDPromotion = 0;
            console.log("data-percent 1:", dataPercent);
            console.log("value1:", valueIDPromotion);
            $('#id_voucher').change(function() {
                if ($("input[name='hours']").val().trim() == "" || $("input[name='month']").val().trim() == "" || $("input[name='seats']").val().trim() == "") {

                }
                var selectedOption = $(this).find(':selected');
                dataPercent = selectedOption.data('percent');
                valueIDPromotion = selectedOption.val();

                // In ra giá trị của data-percent và value
                console.log("data-percent:", dataPercent);
                console.log("value:", valueIDPromotion);
                console.log("data-percent2:", selectedOption.data('percent'));
                console.log("value:", selectedOption.val());
                if (valueIDPromotion != 0) {
                    $("input[name='giamgia']").val((parseInt($("input[name='amount']").val()) * (parseInt(dataPercent) / 100)).toLocaleString('vi-VN') + ' VND');
                    $("input[name='amount']").val(parseInt($("input[name='amount']").val()) * (1 - parseInt(dataPercent) / 100));
                }
            });

            $(document).ready(function() {
                // $('.redirect').click(function() {
                    
                // });
                // $('.btn1').click(function() {
                //     $('.popup_box').css("display", "none");
                // });
                // $('.btn2').click(function() {
                //     $('.popup_box').css("display", "none");
                //     $.ajax({
                //         type: "post",
                //         url: "./Bill/add",
                //         data: {
                //             idcustommer: '<?php echo $idUser ?>',
                //             idprom: $("#id_voucher").val(),
                //             totalPrice: $("#amount").val(),
                //             //detailbill
                //             idmovie: $("#id_movie").val(),
                //             idseat: 1,
                //             idroom: $("#id_room").val(),
                //             idshowtime: idST,
                //             listidfood: dataList,
                //             customseats: $("#seats").val(),
                //         },
                //         success: function(response) {
                //             console.log('reponse : ' + response)
                //             // clearInputFields();
                //             // var successAlert = document.querySelector(".alert-success");
                //             // console.log(successAlert);
                //             // successAlert.style.display = "block";

                //             // // Ẩn cảnh báo sau 2 giây
                //             // setTimeout(function() {
                //             //     successAlert.style.display = "none";
                //             // }, 2000);
                //             // Lấy phần tử có id="success"
                //             var alertBox = document.getElementById("success");

                //             // Hiển thị phần tử
                //             // alertBox.style.display = "block";

                //             // Sau 2 giây, ẩn phần tử
                //             //Tienaaa comment
                //             // setTimeout(function() {
                //             //     alertBox.style.display = "none";
                //             //     window.location.href = "./home"
                //             // }, 2000);
                //             document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                //             document.body.scrollTop = 0;                            

                //         },
                //     });
                // });
                // $
            });
            var seatPriceMap = {};
            let thoiGianPost = '';
            let idPhong = '';
            var priceDefaul = 0;
            var dateBook = '';

            function splitString(str) {
                // Use regular expression to split the string
                var parts = str.match(/[A-Z]\d/g);
                return parts;
            }

            function updateamountPrice() {
                var amount = 0;
                var selectedSeats = $("input[name='seats']").val();
                var pricePerSeat = priceDefaul; // Set your price per seat here
                var amount = (selectedSeats.split(',').length) * pricePerSeat;
                var amount = (seatSelected.length) * pricePerSeat;
                console.log("Gia ShowTime " + pricePerSeat);

                Object.keys(seatPriceMap).forEach(function(seat) {
                    var price = seatPriceMap[seat];
                    console.log("Ghế " + seat + " có giá: " + price);

                    amount += price;
                });
                // Update the tickets input field with the calculated amount price
                $("input[name='tickets']").val(amount.toLocaleString('vi-VN') + ' VND');
                // You can also update other fields if needed (e.g., combos, amount money, etc.)
                var combo = $("input[name='combos']").val();
                console.log(dataPercent);
                if (dataPercent != 0) {
                    $("input[name='amount']").val((parseInt($("input[name='amount']").val()) * (1 - parseInt(dataPercent) / 100)).toLocaleString('vi-VN') + ' VND');

                } else {
                    $("input[name='amount']").val(parseInt(amount) + parseInt(combo) * 1000);
                }
            }
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".edit-delete-btn button:first-child").forEach(function(button, index) {
                    button.addEventListener("click", function() {
                        updateQuantity(index, -1);
                    });
                });
            });
            document.addEventListener("DOMContentLoaded", function() {

                var comboData = <?php echo json_encode($list_combo); ?>;
                var selectedCombos = Array(comboData.length).fill(0);
                document.querySelectorAll(".edit-delete-btn button:last-child").forEach(function(button, index) {
                    button.addEventListener("click", function() {
                        updateQuantity(index, 1);
                    });
                });
                document.querySelectorAll(".edit-delete-btn button:first-child").forEach(function(button, index) {
                    button.addEventListener("click", function() {
                        updateQuantity(index, -1);
                    });
                });

                function updateQuantity(index, value) {
                    var inputElement = document.querySelectorAll(".edit-delete-btn input")[index];
                    var quantity = parseInt(inputElement.value) + value;
                    // Kiểm tra nếu số lượng là lớn hơn hoặc bằng 0 thì mới cập nhật giá trị
                    if (quantity >= 0) {
                        inputElement.value = quantity;
                        selectedCombos[index] = quantity;
                        updateComboPrice();
                    }
                }

                function updateComboPrice() {
                    var combosPrice = 0;
                    for (var i = 0; i < comboData.length; i++) {
                        combosPrice += selectedCombos[i] * parseFloat(comboData[i].Price);
                    }
                    $("input[name='combos']").val(combosPrice.toLocaleString('vi-VN') + ' VND');
                    updateamountPrice();
                    getIDFoods();
                }
            });

            function clearInputFields() {
                $('#list-date').empty();
                updateShowtimes([]);
                $("#hours").val("");
                $("#month").val("");
                $("#room").val("");
                $("#seats").val("");
                $("#tickets").val("");
                $("#combos").val("");
                $("#giamgia").val("");
                $("#amount").val(0);
            }
            document.addEventListener("DOMContentLoaded", function() {
                function formatDateTime(input) {
                    // Tách ngày và thời gian
                    const [datePart, timePart] = input.split('T');

                    // Tách ngày, tháng, năm
                    const [day, month, year] = datePart.split('/');

                    // Tách giờ và phút
                    let [hour, minute] = timePart.split(':');
                    hour = parseInt(hour); // Cộng thêm 1 giờ

                    // Định dạng giờ lại để đảm bảo có 2 chữ số
                    hour = hour < 10 ? `0${hour}` : hour;

                    // Tạo chuỗi định dạng mới
                    const formattedDateTime = `${year}-${month}-${day}T${hour}:${minute}`;

                    return formattedDateTime;
                }
                // Load all showtimes initially
                var allShowtimes = <?php echo json_encode($lichchieu->loadone_showtime_by_id_movie($movie['id'])); ?>;
                var allDate;
                // Get the showtimes container
                var showtimesContainer = document.getElementById("showTimes");
                // Get the hours input field
                var hoursInput = document.querySelector("input[name='hours']");
                var dayInput = document.querySelector("input[name='month']");
                // Add change event listener to id_room select
                let selectedRoom = document.getElementById("id_room");
                selectedRoom.addEventListener("change", function() {
                    // Get the selected room ID
                    var selectedRoomId = selectedRoom.value;
                    // Filter showtimes based on the selected room
                    console.table(allShowtimes);
                    var filteredShowtimes = allShowtimes.filter(function(showtime) {
                        console.log(`Giá trị: ${selectedRoomId} - ${showtime.PhongChieu}`);
                        return showtime.PhongChieu == selectedRoomId;
                    });
                    if (selectedRoom.value == "") {
                        console.log("Please select room");
                    } else {
                        console.log(`you have selected room ${selectedRoom.value}`);
                        updateDate(filteredShowtimes);
                        $(".date_point").click(function() {
                            //chonj ngày
                            // Remove the 'selected' class from all date points
                            $(".date_point").removeClass("selected");
                            var selectedDate = $(this).text().trim();
                            // Update the month input field
                            $("input[name='month']").val(selectedDate);

                            // Add the 'selected' class to the clicked date point
                            $(this).addClass("selected");
                            updateShowtimes(filteredShowtimes);
                            $(".time_point").click(function() {
                                updateRoom();

                                // dateBook = formatDateTime(dateBook);
                                // Assuming $list_custom_seat is your list of custom seats in PHP, and you want to convert it to JavaScript
                                var list_custom_seat = <?php echo json_encode($detailbill->getAllByIDPhim($movie['id'])); ?>;
                                console.log($(this).text().trim());
                                dateBook = $("#month").val() + "/" + new Date().getFullYear() + "T" + $(this).text().trim();
                                console.log(`datebook adn list room`);
                                dateBook = formatDateTime(dateBook);
                                console.log(selectedRoom.value);
                                $.ajax({
                                    type: "post",
                                    url: "./QuanLyLichChieu/getIDShowTime",
                                    data: {
                                        idPhim: $('#id_movie').val(),
                                        ThoiGian: dateBook,
                                        Room: selectedRoom.value,
                                    },
                                    success: function(response) {
                                        console.log("res : " + response);
                                        idST = response;
                                        list_custom_seat.forEach(function(seat) {
                                            console.log("so sanh :" + dateBook == seat['ThoiGian']);

                                            if (seat['IDMovie'] == $('#id_movie').val() && $("#id_room").val() == seat['IDRoom'] && idST == seat['IDShowTime']) {
                                                var seatString = seat['CustomSeats'];
                                                var seatClasses = generateSeatClasses(seatString);

                                                // Apply styles to the elements with generated CSS classes
                                                var elements = document.querySelectorAll(seatClasses);
                                                console.log('Css ReD');
                                                elements.forEach(function(element) {
                                                    element.style.pointerEvents = 'none';
                                                    element.style.backgroundColor = 'red';
                                                });
                                            }
                                        });
                                    },
                                });

                                console.log(dateBook);
                                console.log(list_custom_seat);
                                // Function to generate CSS classes for seats
                                function generateSeatClasses(seatString) {
                                    var seats = seatString.split(',');
                                    var seatClasses = seats.map(function(seat) {
                                        return ".red" + seat.toLowerCase();
                                    });
                                    return seatClasses.join(',');
                                }
                                if (list_custom_seat.length > 0) {

                                    // Loop through each custom seat and generate CSS classes

                                }

                                $(".seat").click(function() {
                                    var selectedSeat = $(this).data("seat");
                                    var currentSeatsValue = $("input[name='seats']").val();
                                    //  $("input[name='seats']").val(splitString($("input[name='seats']").val()))
                                    var newSeatsValue;
                                    if (currentSeatsValue === "") {
                                        newSeatsValue = selectedSeat;
                                    } else {
                                        newSeatsValue = currentSeatsValue.includes(selectedSeat) ?
                                            currentSeatsValue.replace(',' + selectedSeat, '').replace(selectedSeat + ',', '').replace(selectedSeat, '') :
                                            currentSeatsValue + '' + selectedSeat;
                                    }
                                    $("input[name='seats']").val(splitString(newSeatsValue));

                                    $(this).toggleClass("selected");
                                    // Kiểm tra xem ghế đã được chọn hay không và thêm hoặc loại bỏ khỏi mảng seatSelected
                                    var index = seatSelected.indexOf(selectedSeat);
                                    if (index === -1) {
                                        // Nếu ghế chưa được chọn, thêm vào mảng
                                        seatSelected.push(selectedSeat);
                                    } else {
                                        // Nếu ghế đã được chọn, loại bỏ khỏi mảng
                                        seatSelected.splice(index, 1);
                                    }
                                    seatPriceMap = {};
                                    var selectedS = document.querySelectorAll('li.seat.selected');
                                    selectedS.forEach(function(seat) {
                                        // Lấy giá trị của thuộc tính 'data-seat' và 'data-price' từ mỗi phần tử
                                        var seatName = seat.getAttribute('data-seat');
                                        var price = parseInt(seat.getAttribute('data-price'));

                                        // Kiểm tra nếu cả hai giá trị đều hợp lệ
                                        if (seatName && !isNaN(price)) {
                                            // Lưu vào mảng với key là tên data-seat và value là data-price
                                            seatPriceMap[seatName] = price;
                                        }
                                    });
                                    console.log('gia ghe');
                                    console.log(seatPriceMap);
                                    var hoursInput = document.querySelector("input[name='hours']").value;
                                    var dayInput = document.querySelector("input[name='month']").value;
                                    let selectedRoom = document.getElementById("id_room").value;
                                    let idPhim = document.querySelector("input[name='id_movie']").value;

                                    // Calculate and update the amount price                        
                                    // updateamountPrice();
                                    function convertDateFormat(dateString) {
                                        var parts = dateString.split('/');
                                        var day = parts[0];
                                        var months = parts[1];
                                        // Nếu bạn muốn chèn năm cố định, bạn có thể thay đổi năm ở đây, ví dụ: var year = 2024;
                                        var year = new Date().getFullYear();
                                        var formattedDate = year + '-' + months + '-' + day;
                                        return formattedDate;
                                    }

                                    function convertHourFormat(dateString) {
                                        var parts = dateString.split(':');
                                        var gio = parts[0];
                                        var phut = parts[1];
                                        // Nếu bạn muốn chèn năm cố định, bạn có thể thay đổi năm ở đây, ví dụ: var year = 2024;
                                        var formattedDate = addLeadingZero(gio) + ':' + phut;
                                        return formattedDate;
                                    }

                                    function addLeadingZero(value) {
                                        return value < 10 ? '0' + value : value;
                                    }
                                    // convertDateFormat(month) +'T'+hoursInput
                                    console.log(`Giờ : ${convertHourFormat(hoursInput)} , Ngày : ${convertDateFormat(dayInput) +'T'+hoursInput} , Phòng : ${selectedRoom} , Phim: ${idPhim}`)

                                    $(document).ready(function() {
                                        $.ajax({
                                            type: "post",
                                            url: "./booking/getGiaVe",
                                            data: {
                                                idPhim: idPhim,
                                                ThoiGian: convertDateFormat(dayInput) + 'T' + convertHourFormat(hoursInput),
                                                Room: selectedRoom,
                                            },
                                            success: function(response) {
                                                console.log(response);
                                                priceDefaul = response;
                                                var amount = 0;
                                                var selectedSeats = $("input[name='seats']").val();
                                                // var pricePerSeat = priceDefaul; // Set your price per seat here
                                                var amount = (selectedSeats.split(',').length) * response;
                                                var amount = (seatSelected.length) * response;
                                                console.log("Gia ShowTime " + response);
                                                Object.keys(seatPriceMap).forEach(function(seat) {
                                                    var price = seatPriceMap[seat];
                                                    console.log("Ghế " + seat + " có giá: " + price);
                                                    amount += price;
                                                });
                                                // Update the tickets input field with the calculated amount price
                                                $("input[name='tickets']").val(amount.toLocaleString('vi-VN') + ' VND');
                                                // You can also update other fields if needed (e.g., combos, amount money, etc.)
                                                // $("input[name='combos']").val(amount.toLocaleString('vi-VN') + ' đ');
                                                $("input[name='amount']").val(amount);
                                                updateamountPrice();

                                            },
                                        });

                                    });
                                });
                            });
                        });

                    }

                });
                //Tien comment
                function updateDate(showtimes) {
                    // Set the starting date as the current date
                    // var currentDate = new Date();
                    // Tạo một hash map để lưu trữ các ngày đã xuất hiện
                    var ngayDaXuatHien = {};
                    // Mảng chứa các ngày không trùng nhau
                    // var fillterDate = [];

                    showtimes.forEach(function(time) {
                        // Lấy phần ngày từ thời gian chiếu và chuyển đổi thành chuỗi
                        var ngay = (new Date(time.ThoiGianChieu)).toISOString().split("T")[0];

                        // Kiểm tra xem ngày đã xuất hiện trong hash map chưa
                        if (!ngayDaXuatHien[ngay]) {
                            // Nếu chưa xuất hiện, thêm vào danh sách kết quả và đánh dấu đã xuất hiện trong hash map
                            // fillterDate.push(ngay);
                            ngayDaXuatHien[ngay] = true;
                        }
                    });
                    var fillterDate = Object.keys(ngayDaXuatHien);
                    var html = '';
                    // Create an array to store the day abbreviations
                    var dayAbbreviations = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                    // Output the left card and date points
                    html += '<div class="left_card">';
                    html += '<div class="card_month crd">';
                    // // Loop to generate the next 14 date points (for two weeks)
                    // for (var i = 0; i < 6; i++) {
                    //     // Output the day abbreviation and day of the month
                    //     html += '<li> ';
                    //     html += '<h6 class="date_point not_pick">' + dayAbbreviations[currentDate.getDay()] + '</h6> ';
                    //     html += '<h6 class="date_point">' + currentDate.getDate() + '/' + (currentDate.getMonth() + 1) + '</h6>';
                    //     html += '</li> ';
                    //     // Move to the next day
                    //     currentDate.setDate(currentDate.getDate() + 1);
                    // }
                    // html += ' </div> ';
                    // html += ' </div> ';
                    // $('#list-date').html(html);
                    var ngayHienTai = new Date();
                    console.log(ngayHienTai);
                    console.log(fillterDate)
                    fillterDate.forEach(function(time) {
                        console.log(time)
                        var nam = ngayHienTai.getFullYear();
                        var thang = (ngayHienTai.getMonth() + 1).toString().padStart(2, '0'); // Tháng bắt đầu từ 0, cần cộng thêm 1 và đảm bảo có 2 chữ số
                        var ngay = ngayHienTai.getDate().toString().padStart(2, '0');
                        var dateNow = `${nam}-${thang}-${ngay}`;
                        // var ngayChieu = new Date((time.ThoiGianChieu)).split("T")[0];
                        console.log(dateNow);
                        console.log(time >= dateNow);


                        if (time >= dateNow) {
                            var ngay = new Date(time).getDate().toString().padStart(2, '0'); // Đảm bảo có 2 chữ số cho ngày
                            var thang = (new Date(time).getMonth() + 1).toString().padStart(2, '0'); // Đảm bảo có 2 chữ số cho tháng

                            var ngayThang = ngay + '/' + thang;
                            html += '<li> ';
                            // Lấy phần ngày từ thời gian chiếu
                            html += '<h6 class="date_point not_pick">' + dayAbbreviations[new Date(time).getDay()] + '</h6> ';
                            html += '<h6 class="date_point">' + ngayThang + '</h6>'; //  '/' + (ngayChieu.getMonth() + 1) +
                            html += '</li> ';
                            // Add click event listener to the showtime item
                            // date.value = (time.ThoiGianChieu).split("T")[1];
                        }

                    });
                    html += ' </div> ';
                    html += ' </div> ';
                    $('#list-date').html(html);

                }

                function updateRoom() {
                    let listStringSeats;
                    let listSeats;
                    let displayedRoomNames = <?php echo json_encode($displayedRoomNames) ?>;
                    let listCinemaRooms = <?php echo json_encode($list_room);  ?>;
                    let priceSeat = <?php echo json_encode($ghe->getAllChairs()); ?>;
                    if (listCinemaRooms.length > 0) {
                        for (let id in displayedRoomNames) {
                            for (let i = 0; i < listCinemaRooms.length; i++) {
                                let phongchieu = listCinemaRooms[i];
                                if (phongchieu.hasOwnProperty('DSGhe') && phongchieu.id == id) {
                                    listStringSeats = phongchieu.DSGhe;
                                    listSeats = listStringSeats.split(',').map(item => item.split(':'));
                                    break;
                                } else {
                                    console.log(`Phòng chiếu ${phongchieu.TenPhong}`);
                                }
                            }
                        }
                    } else {
                        console.log("Không có dữ liệu về phòng chiếu.");
                    }

                    let rows = [];
                    if (listSeats.length > 0) {
                        for (let i = 0; i < listSeats.length; i++) {
                            rows.push(String.fromCharCode(65 + i));
                        }

                        let columns = Array.from(Array(listSeats[0].length).keys());
                        let indexR = 0;
                        html = "";
                        rows.forEach(row => {
                            html += `<div class="row">`;
                            columns.forEach(column => {
                                let seat = row + column;
                                let check = listSeats[indexR][column];
                                if (check == 0) {
                                    // Do nothing for unavailable seats
                                } else {
                                    let seatClass = '';
                                    let price = 0;
                                    if (check == 1) {
                                        seatClass = 'normal';
                                        price = priceSeat[1]['GiaGhe'];
                                    } else if (check == 2) {
                                        seatClass = 'vip';
                                        price = priceSeat[2]['GiaGhe'];
                                    } else if (check == 3) {
                                        seatClass = 'couple';
                                        price = priceSeat[3]['GiaGhe'];
                                    }
                                    html += `<li class="seat ${seatClass} red${seat}" data-seat="${seat}" data-price="${price}">${seat}</li>`;
                                }
                            });
                            html += '</div>';
                            indexR++;
                        });
                    } else {
                        alert("Hiện tại chưa suất chiếu");
                        window.history.back();
                    }
                    $("#chair").html(html);

                }
                // Function to update showtimes in the DOM
                function updateShowtimes(showtimes) {
                    // Clear existing showtimes
                    showtimesContainer.innerHTML = "";
                    var defaultRoomId = document.getElementById("id_room");
                    var ngayDaXuatHien = {};
                    // Mảng chứa các ngày không trùng nhau
                    // var fillterDate = [];

                    showtimes.forEach(function(time) {
                        // Lấy phần ngày từ thời gian chiếu và chuyển đổi thành chuỗi
                        var ngay = (new Date(time.ThoiGianChieu)).toISOString().split("T")[1];

                        // Kiểm tra xem ngày đã xuất hiện trong hash map chưa
                        if (!ngayDaXuatHien[ngay]) {
                            // Nếu chưa xuất hiện, thêm vào danh sách kết quả và đánh dấu đã xuất hiện trong hash map
                            // fillterDate.push(ngay);
                            ngayDaXuatHien[ngay] = true;
                        }
                    });
                    var fillterDate = Object.keys(ngayDaXuatHien);
                    console.log(fillterDate);
                    fillterDate.forEach(function(time) {
                        console.log(time);

                        var gioPhut = parseInt(time.split(':')[0]) + 7 + ':' + time.split(':')[1];
                        var listItem = document.createElement("li");
                        listItem.innerHTML = '<h6 class="time_point">' + gioPhut + '</h6>'; // (time.ThoiGianChieu).split("T")[1]
                        // Add click event listener to the showtime item
                        listItem.addEventListener("click", function() {
                            // Update the hours input field with the selected showtime
                            hoursInput.value = gioPhut;
                            // Remove the 'selected' class from all showtime items
                            var allShowtimeItems = showtimesContainer.querySelectorAll("li");
                            allShowtimeItems.forEach(function(item) {
                                item.classList.remove("selected");
                            });
                            // Add the 'selected' class to the clicked showtime item
                            listItem.classList.add("selected");
                            valueReturn = gioPhut;
                        });
                        showtimesContainer.appendChild(listItem);
                    });
                }
                // Initial update based on the default selected room
                var defaultRoomId = document.getElementById("id_room");
                console.log(`defaul : ${defaultRoomId.value}`);
                var defaultShowtimes = allShowtimes.filter(function(showtime) {
                    return showtime.id_room == defaultRoomId.value;
                });
                // if(!isEmpty(dayInput.value)){ 
                console.log([]);
                updateShowtimes(defaultShowtimes);
                // }

            });
        </script>
</body>
<style>
    .fade-out {
        opacity: 0;
        transition: opacity 1.5s ease-out;
        /* Thời gian và kiểu chuyển tiếp có thể được điều chỉnh */
    }

    .not_pick {
        pointer-events: none;
    }

    /* CSS */
    .my-element {
        background-color: blue;
        /* Màu mặc định */
        transition: background-color 0.3s ease;
        /* Hiệu ứng chuyển đổi */
    }

    .my-element:hover {
        background-color: green;
        /* Màu khi hover */
    }

    .date_point {
        margin: 0 30px;
    }

    .required-div:empty::before {
        content: "Vui lòng chọn phòng và xuất chiếu mong muốn . . .";
        color: red;
    }
</style>