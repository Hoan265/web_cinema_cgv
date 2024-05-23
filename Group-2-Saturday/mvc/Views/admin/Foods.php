<!-- //////////////////////////////////////////////////////////////////////////// CONNECT SQL /////////////////////////////////////////////////////////////////////////////// -->

<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once './mvc/Controllers/Foods.php';
require_once './mvc/Controllers/DetailFoodController.php';
require_once './mvc/Controllers/SizeFoodController.php';
require_once './mvc/Controllers/CatalogFoodController.php';
require_once './mvc/Controllers/Bill.php';
require_once './mvc/Controllers/DetailBill.php';
require_once './mvc/Models/FoodsModel.php';
$detailsBillController = new DetailBill();


$foodsController = new Foods();
$detailController = new DetailFoodController();
$sizeController = new SizeFoodController();
$catalogController = new CatalogFoodController();
$billController = new Bill();

$detailbills = $detailsBillController->getAllDetailBills();
$bills = $billController->getBillsTicket();
// $detailbills = $detailsBillController->getAllDetailBills();
// $bills = $billController->getBillsTicket();


//////////////////      KIỂM TRA SỰ KIỆN /////////////////////////
$current_url = $_SERVER['REQUEST_URI'];
$url_parts = parse_url($current_url);
if (isset($url_parts['query'])) {
    parse_str($url_parts['query'], $query_params);
    if (isset($query_params['id'])) {
        $id = $query_params['id'];
        $foodDetails = $detailController->getDetailFoods($id);
        $nameF = $foodsController->setNameFood($id);
    } else {
    }
    if (isset($query_params['sizeId'])) {
        $sizeId = $query_params['sizeId'];
        $food = $detailController->getDetailSizeFoods($sizeId);
    } else {
    }
    if (isset($query_params['page'])) {
        $recordsPerPage = 7;
        $page = isset($query_params['page']) ? $query_params['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $foods = $foodsController->getPaginatedFoods($recordsPerPage, $offset);
        $totalRecords = $foodsController->countAllFoods();
        $totalPages = ceil($totalRecords / $recordsPerPage);
    }

    if (isset($query_params['search'])) {
        $search_text = isset($_POST['search_txt']) ? $_POST['search_txt'] : (isset($query_params['search']) ? $query_params['search'] : '');
        if (!empty($search_text)) {
            $foods = $foodsController->searchFoods($search_text);
            $searched = true;
            $totalRecords = count($foods);
            $recordsPerPage = 7;
            $totalPages = ceil($totalRecords / $recordsPerPage);
            $page = isset($query_params['page']) ? $query_params['page'] : 1;
            $offset = ($page - 1) * $recordsPerPage;
            $foods = array_slice($foods, $offset, $recordsPerPage);
        }
    } else {
        $recordsPerPage = 7;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $foods = $foodsController->getPaginatedFoods($recordsPerPage, $offset);
        $totalRecords = $foodsController->countAllFoods();
        $totalPages = ceil($totalRecords / $recordsPerPage);
    }
}
//////////////////      PHÂN TRANG.  /////////////////////////


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['AddFood'])) {
        $id = $foodsController->generateFoodId($catalogController->setIdCatalog($_POST['foodType']));
        $name = $_POST['foodName'];
        $status = $_POST['foodStatus'];
        $idCatalog = $catalogController->setIdCatalog($_POST['foodType']);
        $imgData = null;

        if (isset($_FILES['foodImage']) && $_FILES['foodImage']['error'] === UPLOAD_ERR_OK) {
            $img_temp = $_FILES['foodImage']['tmp_name'];
            $imgData = file_get_contents($img_temp);
        }
        if ($foodsController->addFoods($id, $name, $status, $idCatalog, $imgData)) {
            $success_message = "Thêm món ăn thành công !!!!";
        } else {
            echo "Cập nhật món ăn thất bại!";
        }
        echo '<script>';
        echo 'setTimeout(function() {';
        echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '&page=' . $page . '&search=";'; // Truyền số trang hiện tại vào URL
        echo '}, 1000);'; // Thời gian chờ là 1000ms (tức là 1 giây)
        echo '</script>';
    }
    if (isset($_POST['deletefood'])) {
        $idFromForm = $id;
        $hasUnprocessedBill = false;
        foreach ($bills as $bill) {
            foreach ($detailbills as $detailbill) {
                if ($bill->Status == 'Chưa xử lý' && $detailbill->IDDetailBill == $bill->IDBill && $detailbill->IDFood == $id) {
                    $hasUnprocessedBill = true;
                    break 2; // Thoát khỏi cả hai vòng lặp
                }
            }
        }
        if ($hasUnprocessedBill) {
            $foodsController->updateFoodStatus($idFromForm, 'Ngưng bán');
            foreach ($foodDetails as $detail) {
                if ($detail->getIDFood() == $idFromForm) {
                    $detailController->updateStatusByFoodsId($idFromForm, 'Ngưng bán');
                } else {
                    $foodsController->deleteFood($idFromForm);
                    foreach ($foodDetails as $detail) {
                        if ($detail->getIDFood() == $idFromForm) {
                            $detailController->deleteDetailByFoodsId($idFromForm);
                        }
                    }
                }
                // $relatedBills = $detailsBillController->getRelatedBillIDs($idFromForm);
                // $existingFoodId = $detailsBillController->checkIDFoodExists($idFromForm);

                // if ($existingFoodId === $idFromForm) {
                //     $allConfirmed = true;
                //     foreach ($relatedBills as $relatedBill) {
                //         $status = $relatedBill['Status'];
                //         if ($status !== 'Hoàn thành' && $status !== 'Đã xử lý') {
                //             $allConfirmed = false;
                //             break; 
                //         }
                //     }
                //     if ($allConfirmed) {
                //         $foodsController->deleteFood($idFromForm);
                //         foreach ($foodDetails as $detail) {
                //             if ($detail->getIDFood() == $idFromForm) {
                //                 $detailController->deleteDetailByFoodsId($idFromForm);
                //             }
                //         }
                //     } else {
                //         $foodsController->updateFoodStatus($idFromForm, 'Ngưng bán');
                //         foreach ($foodDetails as $detail) {
                //             if ($detail->getIDFood() == $idFromForm) {
                //                 $detailController->updateStatusByFoodsId($idFromForm, 'Ngưng bán');
                //             }
                //         }
                //     }
                //     echo '<script>';
                //     echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '";';
                //     echo '</script>';
                // } else {
                //     $foodsController->deleteFood($idFromForm);
                //     foreach ($foodDetails as $detail) {
                //         if ($detail->getIDFood() == $idFromForm) {
                //             $detailController->deleteDetailByFoodsId($idFromForm);
                //         }
                //     }
                //     echo '<script>';
                //     echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '";';
                //     echo '</script>';
                // }
            }
        }
        if (isset($_POST['updateFood'])) {
            $page = isset($query_params['page']) ? $query_params['page'] : 1;
            $idFromForm = $id;
            $currentFood = $foodsController->getF($idFromForm);
            $name = $_POST['updateFoodName'];
            $status = $_POST['updateFoodStatus'];
            $namecatalog = $_POST['updateFoodType'];
            $idCa = $catalogController->setIdCatalog($namecatalog);
            $imgData = null;
            if (isset($_FILES['updateFoodImage']) && $_FILES['updateFoodImage']['error'] === UPLOAD_ERR_OK) {
                $img_temp = $_FILES['updateFoodImage']['tmp_name'];
                $imgData = file_get_contents($img_temp);
            } else {
                $imgData = $currentFood->getImg();
            }

            if ($foodsController->updateFoods($idFromForm, $name, $status, $idCa, $imgData)) {
                $success_message = "Cập nhật món ăn thành công !!!!";
            } else {
                echo "Cập nhật món ăn thất bại!";
            }
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '&page=' . $page . '&search=";'; // Truyền số trang hiện tại vào URL
            echo '}, 2000);'; // Thời gian chờ là 1000ms (tức là 1 giây)
            echo '</script>';
        }
        if (isset($_POST['saveSize'])) {
            $idD = $detailController->createIDetailFoods();
            $price = $_POST['price'];
            $status = $_POST['status'];
            $idF = $id;
            $nameS = $_POST['size'];
            $idS = $sizeController->setIdSize($nameS);
            $detailController->addSize($idD, $price, $status, $idF, $idS);
            $success_message = "Thêm kích cỡ thành công";
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '&page=' . $page . '&search=";'; // Truyền số trang hiện tại vào URL
            echo '}, 2000);'; // Thời gian chờ là 1000ms (tức là 1 giây)
            echo '</script>';
        }
        if (isset($_POST['updateSize'])) {
            $idS  = $sizeId;
            $price = $_POST['updateSizePrice'];
            $status = $_POST['updateSizeStatus'];
            $detailController->updateSizeFoods($idS, $price, $status);
            $success_message = "Cập nhật thành công kích cỡ có id là $sizeId ";
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '&page=' . $page . '&search=";'; // Truyền số trang hiện tại vào URL
            echo '}, 2000);'; // Thời gian chờ là 1000ms (tức là 1 giây)
            echo '</script>';
            // echo '<script>';
            // echo 'window.location.href = "/Group-2-Saturday/Foods?id=' . $id . '&sizeId=' . $sizeId . '";'; // Chuyển hướng đến trang OTP
            // echo '</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đồ ăn</title>
    <link rel="stylesheet" href="./assets/css/managefood.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-Cueo4u5zQxWx2e6r7SmM1dXMBMbRkPFDLZSyD+fl/zMXsPTxtMheRP8dGKe5zC4x" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="food-details">
            <h2>Chi tiết </h2>
            <div class="size-buttons">
                <!-- ////////////////////////////////////////                     ADD SIZE                       /////////////////////////////////////////////// -->
                <div class="modal" id="addSizeModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Thêm kích cỡ</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="foodName">Tên đồ ăn:</label>
                                        <input type="text" class="form-control" id="foodName" name="foodName" value="<?php echo $nameF; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Kích cỡ:</label>
                                        <select class="form-control" id="size" name="size" required>
                                            <?php
                                            $sizes = $sizeController->showSize($id);
                                            foreach ($sizes as $size) {
                                                echo "<option value='$size'>$size</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Giá bán:</label>
                                        <input type="number" class="form-control" id="price" name="price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Trạng thái:</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="Đang bán">Đang được bán</option>
                                            <option value="Ngưng bán">Ngưng bán</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="saveSize">Lưu</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <button name="add_size" data-toggle="modal" data-target="#addSizeModal">Thêm kích cỡ</button>

                <!-- ////////////////////////////////////////                     UPDATE SIZE                       /////////////////////////////////////////////// -->
                <button name="update_size" data-toggle="modal" data-target="#updateSizeModal">Cập nhật kích cỡ</button>
                <div class="modal" id="updateSizeModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Cập nhật kích cỡ <?php echo $sizeId ?></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <?php
                                    if (isset($sizeId)) {
                                        $idSize = $food->getIdSize();
                                        $nameSize = $sizeController->setNameSize($idSize);
                                        $foodPrice = $food->getPrice();
                                        $foodStatus = $food->getStatus();
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="updateSizeFoodName">Tên đồ ăn:</label>
                                        <input type="text" class="form-control" id="updateSizeFoodName" name="updateSizeFoodName" value="<?php echo isset($nameF) ? $nameF : ''; ?>" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateSize">Kích cỡ:</label>
                                        <input type="text" class="form-control" id="updateSize" name="updateSize" value="<?php echo $nameSize ?>" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateSizePrice">Giá bán:</label>
                                        <input type="number" class="form-control" id="updateSizePrice" name="updateSizePrice" value="<?php echo isset($foodPrice) ?  $foodPrice  : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateSizeStatus">Trạng thái:</label>
                                        <select class="form-control" id="updateSizeStatus" name="updateSizeStatus" required>
                                            <option value="Đang được bán" <?php echo isset($foodStatus) && $foodStatus == 'Đang được bán' ? 'selected' : ''; ?>>Đang được bán</option>
                                            <option value="Ngưng bán" <?php echo isset($foodStatus) && $foodStatus == 'Ngưng bán' ? 'selected' : ''; ?>>Ngưng bán</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="updateSize">Cập nhật</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ////////////////////////////////////////                    TÌM KIẾM                       /////////////////////////////////////////////// -->


            Tìm kiếm:
            <form id="search_form" onsubmit="updatePage(1); return false;">
                <input type="text" id="search_txt" placeholder="Nhập mã hoặc tên món ăn cần tìm kiếm..." style="width: 300px;">
                <button type="submit" class="small-button">Tìm kiếm</button>
            </form>
            <!-- ////////////////////////////////////////                     BẢNG SIZE                       /////////////////////////////////////////////// -->
            <table class="foods-table">
                <thead>
                    <tr>
                        <th>Kích cỡ</th>
                        <th>Đơn giá</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody id="detail-food-table">
                    <?php
                    if (!empty($foodDetails)) {
                        foreach ($foodDetails as $food) {
                            $name = $sizeController->setNameSize($food->getIdSize());
                    ?>
                            <tr class='food-row' data-id='<?php echo $food->getIdFood(); ?>' data-id-size='<?php echo $food->getId(); ?>' onclick='myFunction(this); myFunctionSize(this)'>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $food->getPrice(); ?></td>
                                <td><?php echo $food->getStatus(); ?></td>
                            </tr>
                    <?php
                        }
                    } elseif (empty($id)) {
                        // echo "Không tìm thấy thông tin cho món ăn có ID: " . $id;
                    }

                    ?>
                </tbody>
            </table>
        </div>
        <!-- /////////////////////////////////////////////////////ADD FOODS //////////////////////////////////////////////////// -->
        <div class="foods-table">
            <h2>Danh sách đồ ăn</h2>
            <?php
            if (isset($success_message)) {
                echo '<div class="success-msg">' . $success_message . '</div>';
            }
            ?>
            <?php if (isset($error_message)) {
                echo '<div class="error-msg">' . $error_message . '</div>';
            } ?>
            <div class="button-group">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFoodModal">
                    Thêm món ăn
                </button>
                <div class="modal" id="addFoodModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Thêm món ăn</h4>
                                <button type="submit" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="foodName">Tên món ăn:</label>
                                        <input type="text" class="form-control" id="foodName" name="foodName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="foodType">Loại món ăn:</label>
                                        <select class="form-control" id="foodType" name="foodType" required>
                                            <option value="Bánh kẹo">Bánh kẹo</option>
                                            <option value="Đồ ăn nhanh">Đồ ăn nhanh</option>
                                            <option value="Nước uống">Nước uống</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="foodStatus">Trạng thái:</label>
                                        <select class="form-control" id="foodStatus" name="foodStatus" required>
                                            <option value="Đang được bán">Đang được bán</option>
                                            <option value="Ngưng bán">Ngưng bán</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="foodImage">Hình ảnh:</label>
                                        <input type="file" class="form-control-file" id="foodImage" name="foodImage" accept="image/*" onchange="previewImage(event)" required>
                                    </div>
                                    <img id="preview" src="/assets/img/Foods/" alt="Ảnh món ăn" style="max-width: 100%; max-height: 200px; display: none;">
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="AddFood">Lưu</button>
                                        <button type="submit" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- //////////////////////////////////// FUNTION UPDATE FOODS /////////////////////////// -->
                <div class="modal" id="updateFoodModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Cập nhật món ăn <?php echo $id ?></h4>
                                <?php
                                $food = $foodsController->getF($id);
                                $idCatalog = $food->getIdCatalog();
                                $name = $food->getName();
                                $status = $food->getStatus();
                                $img = $food->getImg();
                                $base64Image = base64_encode($img);
                                ?>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="updateFoodName">Tên món ăn:</label>
                                        <input type="text" class="form-control" id="updateFoodName" name="updateFoodName" value="<?php echo isset($name) ? $name : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateFoodType">Loại món ăn:</label>
                                        <?php
                                        $nameCatalog = $catalogController->setCatalog($idCatalog);
                                        ?>
                                        <select class="form-control" id="updateFoodType" name="updateFoodType" required>
                                            <option value="Bánh kẹo" <?php echo isset($nameCatalog) && $nameCatalog == 'Bánh kẹo' ? 'selected' : ''; ?>>Bánh kẹo</option>
                                            <option value="Đồ ăn nhanh" <?php echo isset($nameCatalog) && $nameCatalog == 'Đồ ăn nhanh' ? 'selected' : ''; ?>>Đồ ăn nhanh</option>
                                            <option value="Nước uống" <?php echo isset($nameCatalog) && $nameCatalog == 'Nước uống' ? 'selected' : ''; ?>>Nước uống</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateFoodStatus">Trạng thái:</label>
                                        <select class="form-control" id="updateFoodStatus" name="updateFoodStatus" required>
                                            <option value="Đang được bán" <?php echo isset($status) && $status  == 'Đang được bán' ? 'selected' : ''; ?>>Đang được bán</option>
                                            <option value="Ngưng bán" <?php echo isset($status) && $status  == 'Ngưng bán' ? 'selected' : ''; ?>>Ngưng bán</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="updateFoodImage">Hình ảnh:</label>
                                        <input type="file" class="form-control-file" id="updateFoodImage" name="updateFoodImage" onchange="previewImageU(event)">
                                    </div>
                                    <div class="form-group" id="updatePreview">
                                        <label for="updatePreview">Xem trước:</label>
                                        <img id="updatePreviewImage" src="<?php echo isset($base64Image) ? 'data:image/png;base64,' . $base64Image : ''; ?>" alt="Hình ảnh" style="max-width: 100%; max-height: 200px;">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="updateFood">Cập nhật</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateFoodModal">
                    Cập nhật món ăn
                </button>
                </form>
                <!-- //////////////////// //////////////////// ////////////////////            XOÁ MÓN ĂN            //////////////////// //////////////////// -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deleteFoodModal">
                    Xóa đồ ăn
                </button>
                <div class="modal fade" id="deleteFoodModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Xác nhận xóa đồ ăn</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <?php
                                $idFromForm = $id;
                                $hasUnprocessedBill = false;
                                
                                foreach ($bills as $bill) {
                                    foreach ($detailbills as $detailbill) {
                                        if ($bill->Status == 'Chưa xử lý' && $detailbill->IDDetailBill == $bill->IDBill && $detailbill->IDFood == $id) {
                                            $hasUnprocessedBill = true;
                                            break 2; // Exit both loops
                                        }
                                    }
                                }
                                
                                if ($hasUnprocessedBill) {
                                    $foodsController->updateFoodStatus($idFromForm, 'Ngưng bán');
                                    $foodFound = false;
                                    foreach ($foodDetails as $detail) {
                                        if ($detail->getIDFood() == $idFromForm) {
                                            $foodFound = true;
                                            break;
                                        }
                                    }
                                    if ($foodFound) {
                                        echo "Món ăn này vẫn đang ở trong các hoá đơn của khách hàng và chưa được xử lý. Bạn không thể xoá món ăn này. Món ăn này sẽ được cập nhật trạng thái thành 'Ngưng bán'. Bạn có chắc chắn muốn tiếp tục?";
                                    } else {
                                        echo "Bạn có chắc chắn muốn xóa món ăn có IDFood là $id?";
                                    }
                                } else {
                                    echo "Bạn có chắc chắn muốn xóa món ăn có IDFood là $id?";
                                }
                                


                                ?>
                            </div>

                            <div class="modal-footer">
                                <form id="deleteFoodForm" action="" method="post">
                                    <input type="hidden" name="foodId" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                                    <button type="submit" class="btn btn-danger" name="deletefood">Xóa</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <form>
                    <button type="submit" class="btn btn-primary" name="reload">
                        <i class="fas fa-sync-alt"></i> Reload
                    </button>
            </div>
            <!-- //////////////////// //////////////////// ////////////////////            BẢNG MÓN ĂN            //////////////////// //////////////////// -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="container">
                    <div class="foods-table-wrapper">
                        <table class="foods-table" id="foods-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã đồ ăn</th>
                                    <th>Tên đồ ăn</th>
                                    <th>Trạng thái</th>
                                    <th>Loại đồ ăn</th>
                                    <th>Hình ảnh</th>
                                </tr>
                            </thead>
                            <tbody id="list-foods">
                                <?php
                                $stt = $offset + 1;
                                foreach ($foods as  $food) {
                                    $nameCatalog = $catalogController->setCatalog($food->getIdCatalog());
                                ?>
                                    <tr class='food-row <?php echo ($food->getId() == $id) ? "selected" : ""; ?>' data-id='<?php echo $food->getId(); ?>' onclick='myFunction(this)'>
                                        <td><?php echo $stt; ?></td>
                                        <td><?php echo $food->getId(); ?></td>
                                        <td><?php echo $food->getName(); ?></td>
                                        <td><?php echo $food->getStatus(); ?></td>
                                        <td><?php echo $nameCatalog ?></td>
                                        <td>
                                            <?php
                                            $imgData = $food->getImg();
                                            $base64Image = base64_encode($imgData);
                                            if (!empty($base64Image)) {
                                                echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Hình ảnh" style="width: 50px; height: auto;">';
                                            } else {
                                                echo 'Không có ảnh';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                    $stt++;
                                }
                                ?>
                            </tbody>
                        </table>
            </form>
            <div class="pagination">
                <ul>
                    <?php if ($page > 1) { ?>
                        <li><a href="?page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search_text); ?>">Previous</a></li>
                    <?php } ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                        <?php if ($i == $page) { ?>
                            <li><a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_text); ?>" style="background-color: rgb(108, 186, 255);"><?php echo $i; ?></a></li>
                        <?php } else { ?>
                            <li><a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_text); ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($page < $totalPages) { ?>
                        <li><a href="?page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search_text); ?>">Next</a></li>
                    <?php } ?>
                </ul>
            </div>


        </div>
    </div>
</body>

</html>

<script>
    function myFunction(row) {
        var selectedFoodId = row.getAttribute('data-id');
        var currentPage = <?php echo $page; ?>;
        console.log("ID của món ăn được chọn: " + selectedFoodId);
        console.log("Trang hiện tại: " + currentPage);
        var url = '/Group-2-Saturday/Foods?id=' + selectedFoodId + '&page=' + currentPage;
        var searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('search')) {
            url += '&search=' + searchParams.get('search');
        }

        window.location.href = url;
    }

    function myFunctionSize(row) {
        var selectedSizeId = row.getAttribute('data-id-size');
        var selectedFoodId = row.getAttribute('data-id');
        var currentPage = <?php echo $page; ?>;
        console.log("ID của kích cỡ được chọn: " + selectedSizeId);
        console.log("ID của món ăn được chọn: " + selectedFoodId);
        console.log("Trang hiện tại: " + currentPage);
        var url = '/Group-2-Saturday/Foods?id=' + selectedFoodId + '&sizeId=' + selectedSizeId + '&page=' + currentPage;
        var searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('search')) {
            url += '&search=' + searchParams.get('search');
        }

        window.location.href = url;
    }

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    function updatePage(pageNumber) {
        var searchTxt = document.getElementById('search_txt').value.trim();
        var url = "?page=" + pageNumber + "&search=" + encodeURIComponent(searchTxt);
        window.location.href = url;
    }
</script>
<script>
    function previewImageU(event) {
        // var input = event.target;
        // var reader = new FileReader();
        // reader.onload = function(){
        //     var dataURL = reader.result;
        //     var imagePreview = document.getElementById('updatePreviewImage');
        //     imagePreview.src = dataURL;
        // };

        // reader.readAsDataURL(input.files[0]);
        var input = event.target;
        var file = input.files[0];

        // Kiểm tra kích thước tệp ảnh (giới hạn kích thước 1MB)
        var maxSize = 1024 * 1024; // 1MB
        if (file.size > maxSize) {
            alert("Kích thước ảnh không được vượt quá 1MB.");
            input.value = ''; // Xóa tệp đã chọn để người dùng chọn lại
            return;
        }

        var reader = new FileReader();
        reader.onload = function() {
            var dataURL = reader.result;
            var imagePreview = document.getElementById('updatePreviewImage');
            imagePreview.src = dataURL;
        };

        reader.readAsDataURL(file);
    }
</script>

</script>
<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 10px;
        /* Khoảng cách từ phần pagination lên trên */
        margin-bottom: 10px;/
    }

    .pagination ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .pagination li {
        margin: 0 5px;
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

    .small-button {
        padding: 5px 10px;
        font-size: 14px;
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
        max-width: 500px;
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

    .foods-table-wrapper {
        max-width: 800px;
        /* Đặt kích thước tối đa cho table-wrapper */
        margin: 0 auto;
        /* Canh giữa table-wrapper */
        padding: 20px;
        max-height: 800px;
        /* Tạo khoảng cách giữa table-wrapper và các phần tử bên ngoài */
    }

    .food-row.selected {
        background-color: rgb(108, 186, 255);
    }
</style>
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