<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// if (session_status() == PHP_SESSION_NONE) {
//     session_start(); 
// }
require_once './mvc/Controllers/StaffController.php';
require_once './mvc/Controllers//RoleStaffController.php';
require_once './mvc/Controllers/Account.php';
$staffController = new StaffController();
$accountController = new Account();
$roleController = new RoleStaffController();
$roles = $roleController->getAllRoleStaff();
$users = $accountController->getUsers();
$staffS = $staffController->getStaffs();
$current_url = $_SERVER['REQUEST_URI'];
$url_parts = parse_url($current_url);
if (isset($url_parts['query'])) {
    parse_str($url_parts['query'], $query_params);
    if (isset($query_params['id'])) {
        $id = $query_params['id'];
    }
    if (isset($query_params['page'])) {
        $recordsPerPage = 10;
        $page = isset($query_params['page']) ? $query_params['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $staffs = $staffController->getPaginatedStaff($recordsPerPage, $offset);
        $totalRecords = $staffController->countAllStaff();
        $totalPages = ceil($totalRecords / $recordsPerPage);
    }
    if (isset($query_params['search'])) {
        $search_text = isset($_POST['search_txt']) ? $_POST['search_txt'] : (isset($query_params['search']) ? $query_params['search'] : '');
        if (!empty($search_text)) {
            $userSersch = $accountController->searchAccounts($search_text);
            $matchedClients = array();
            // $staffs = $staffController->searchStaff($search_text);
            foreach ($userSersch as $searchedUser) {
                foreach ($staffS as $i) {
                    if ($searchedUser->getId() == $i->getIdStaff()) {
                        $matchedClients[] = $i;
                    }
                }
            }
            $staffs = $matchedClients;
            $searched = true;
            $totalRecords = count($staffs);
            $recordsPerPage = 10;
            $totalPages = ceil($totalRecords / $recordsPerPage);
            $page = isset($query_params['page']) ? $query_params['page'] : 1;
            $offset = ($page - 1) * $recordsPerPage;
            $staffs = array_slice($staffs, $offset, $recordsPerPage);
        }
    } else {
        $recordsPerPage = 10;
        $page = isset($query_params['page']) ? $query_params['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $staffs = $staffController->getPaginatedStaff($recordsPerPage, $offset);
        $totalRecords = $staffController->countAllStaff();
        $totalPages = ceil($totalRecords / $recordsPerPage);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateUser'])) {
        $name = $_POST['update_name'];
        $phone = $_POST['update_phone'];
        $sex = $_POST['update_gender'];
        $birthday = $_POST['update_birthday'];
        $role = $_POST['update_role'];
        $idRole = $roleController->findIdByName($role);
        $block = isset($_POST['update_block']) ? 'On' : 'Off';
        $status = $_POST['update_status'];
        $accountController->updateAccountByEmail($id, $name, $phone, $sex, $birthday);
        $accountController->updateBlockStatus($id, $block);
        $staffController->updateStaff($id, $idRole, $status);
        $success_message = "Thông tin ngừoi dùng với id là $id được cập nhật thành công";
        echo '<script>';
        echo 'setTimeout(function() {';
        echo 'window.location.href = "/Group-2-Saturday/Account?id=' . $id . '&page=' . $page . '";';  // Chuyển hướng đến trang OTP
        echo '}, 3000);';
        echo '</script>';
    } elseif (isset($_POST['deleteUser'])) {
        $idD = $id;
        $accountController->deleteAccountById($idD);
        $staffController->deleteStaffById($idD); ?>
        <span style="color: green;"><b>Người dùng với id là <?php echo $id ?> đã được xoá thành công!!!!!</b></span>
<?php
        echo '<script>';
        echo 'setTimeout(function() {';
        echo 'window.location.href = "/Group-2-Saturday/Account?page=' . $page . '";';  // Chuyển hướng đến trang OTP
        echo '}, 2000);';
        echo '</script>';
    } elseif (isset($_POST['addStaff'])) {
        $id = $staffController->getNextStaffID();
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $sex = $_POST['gender'];
        $birthday = $_POST['birthday'];
        $role = $_POST['role'];
        $idrole = $roleController->findIdByName($role);
        $function = '111';
        $block = 'Off';
        $status = "Đang làm";
        $defaultAvatarLink = '/Applications/XAMPP/xamppfiles/htdocs/Group-2-Saturday/assets/img/avatarUser/user.png';
        
        if (!empty($defaultAvatarLink) && is_readable($defaultAvatarLink)) {
            $avatarData = file_get_contents($defaultAvatarLink);
            if ($avatarData === false) {
                echo '<p class="error" style="color: #e71a0f;">Không thể đọc nội dung file ảnh mặc định!</p>';
                //$defaultAvatarLink = 'https://cdn-icons-png.flaticon.com/512/1077/1077114.png';
                $avatarData = file_get_contents($defaultAvatarLink);
                if ($avatarData === false) {
                    echo '<p class="error" style="color: #e71a0f;">Không thể đọc được file ảnh mặc định thứ hai!</p>';
                    $avatarData = '';
                }
            }
        } else {
            echo '<p class="error" style="color: #e71a0f;">Đường dẫn file ảnh mặc định không hợp lệ hoặc không thể truy cập được!</p>';
            $avatarData = '';
        }
        
        if ($accountController->checkEmailExists($email)) {
            echo '<span style="color: red;"><b>Email đã tồn tại trong cơ sở dữ liệu!</b></span>';
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "/Group-2-Saturday/Account?page=' . $page . '";';
            echo '}, 2000);';
            echo '</script>';
            return; // Add this return statement to prevent further code execution
        } else {
            $accountController->addAccount($id, $email, $phone, $password, $name, $sex, $avatarData, $birthday, 'admin', $block);
            $staffController->addStaff($id, $idrole, $function, $status);
            $success_message = "Người dùng với id là " . $id . " đã được thêm thành công!!!!!";
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "/Group-2-Saturday/Account?page=' . $page . '";';
            echo '}, 3000);';
            echo '</script>';
        }
    }
    
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <h2>Danh sách người dùng</h2>
    <?php
    if (isset($success_message)) {
        echo '<div class="success-msg">' . $success_message . '</div>';
    }
    ?>
    <?php if (isset($error_message)) {
        echo '<div class="error-msg">' . $error_message . '</div>';
    } ?>
    <div class="button-group">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- //////////////////////GROUP BUTTON ////////////////////////////// -->
            <div class="button-group">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStaffModal">
                    Thêm người dùng
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateUserModal">
                    Cập nhật người dùng
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deleteUserModal">
                    Xóa người dùng
                </button>
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
    <!-- /////////////////////////////////////////////////////// THÊM/////////////////////////////////////////////////////// -->
    <div class="modal fade" id="addStaffModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm người dùng</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Tên:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Giới tính:</label>
                            <select class="form-control" id="gender" name="gender">
                                <option>Nam</option>
                                <option>Nữ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="birthday">Ngày sinh:</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" min="1944-01-01" max="2008-12-31" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Vai trò:</label>
                            <select class="form-control" id="role" name="role">
                                <?php foreach ($roles as $role) { ?>
                                    <option><?php echo $role->ten; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                </div>

                <!-- Modal Footer -->

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="addStaff">Thêm</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- //////////////////////CẬP NHẬT THÔNG TIN ////////////////////////////// -->
    <div class="modal fade" id="updateUserModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cập Nhật Người Dùng</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="IdStaff" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                        <?php
                        if (isset($id)) {
                            $user = $accountController->findbyId($id);
                            $staff = $staffController->findbyId($id);
                            $ro = $roleController->getIdRole($staff->getIdRole());
                            $ten = $user->getName();
                            $email = $user->getEmail();
                            $phone = $user->getPhone();;
                            $sex = $user->getSex();
                            $birthday = $user->getBirthday();;
                            $pass = $user->getPassword();
                            $status = $staff->getStatus();
                            $block = $user->getBlock();
                        }
                        ?>
                        <div class="form-group">
                            <label for="update_name">Tên:</label>
                            <input type="text" class="form-control" id="update_name" name="update_name" value="<?php echo $ten; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_email">Email:</label>
                            <input type="email" class="form-control" id="update_email" name="update_email" readonly value="<?php echo $email; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_phone">Số điện thoại:</label>
                            <input type="text" class="form-control" id="update_phone" name="update_phone" readonly value="<?php echo $phone; ?>" required pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số">
                        </div>
                        <div class="form-group">
                            <label for="update_gender">Giới tính:</label>
                            <select class="form-control" id="update_gender" name="update_gender">
                                <option value="Nam" <?php echo isset($sex) && $sex == 'Nam' ? ' selected' : ''; ?>>Nam</option>
                                <option value="Nữ" <?php echo isset($sex) && $sex == 'Nữ' ? ' selected' : ''; ?>>Nữ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="update_birthday">Ngày sinh:</label>
                            <input type="date" class="form-control" id="update_birthday" name="update_birthday" value="<?php echo $birthday; ?>" required min="1944-01-01" max="2008-12-31">
                        </div>
                        <div class="form-group">
                            <label for="update_password">Mật khẩu:</label>
                            <input type="password" class="form-control" id="update_password" name="update_password" readonly value="<?php echo  $pass; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_status">Trạng thái:</label>
                            <select class="form-control" id="update_status" name="update_status">
                                <option value="Đang làm" <?php echo isset($status) && $status == 'Đang làm' ? ' selected' : ''; ?>>Đang làm</option>
                                <option value="Đã nghỉ việc" <?php echo isset($status) && $status == 'Đã nghỉ việc' ? ' selected' : ''; ?>>Đã nghỉ việc</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="update_role">Vai trò:</label>
                            <select class="form-control" id="update_role" name="update_role">
                                <?php
                                foreach ($roles as $role) { ?>
                                    <option value="<?php echo $role->ten; ?>" <?php echo isset($ro) && $ro == $role->ten ? 'selected' : ''; ?>>
                                        <?php echo $role->ten; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="update_block" name="update_block" <?php echo ($block == 'On') ? ' checked' : ''; ?>>
                            <label class="form-check-label" for="update_block">Khoá tài khoản ?</label>
                        </div>


                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="updateUser">Cập nhật</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </form>
    <!-- ////////////////////////////////////////////////////////////////////////////////////////////XOÁ NGỪOI DÙNG ////////////////////////////////////////////////////////////////////////////////////////////-->

    <div class="button-group">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa người dùng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa người dùng có id là <?php echo $id ?>?
                        </div>
                        <div class="modal-footer">
                            <form id="deleteUserForm" action="delete_user.php" method="post">
                                <!-- Pass user ID as hidden input -->
                                <button type="submit" class="btn btn-danger" name="deleteUser">Xóa</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ////////////////////////////////////////////////////////////////////////////////////////////BẢNG THÔNG TIN STAFFF ////////////////////////////////////////////////////////////////////////////////////////////-->
    </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã người dùng</th>
                <th>Họ tên</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Vai trò</th>
                <th>Block?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = $offset + 1;
            foreach ($staffs as $staff) {
                $user = $accountController->findbyId($staff->getIdStaff());
                $role = $roleController->getIdRole($staff->getIdRole());
            ?>
                <tr class='food-row <?php echo ($id == $staff->getIdStaff()) ? "selected-row" : ""; ?>' data-id='<?php echo $staff->getIdStaff(); ?>' onclick='myFunction(this)'>
                    <td><?php echo $stt; ?></td>
                    <td><?php echo $staff->getIdStaff(); ?></td>
                    <td><?php echo $user->getName(); ?></td>
                    <td><?php echo $user->getPhone(); ?></td>
                    <td><?php echo $user->getEmail(); ?></td>
                    <td><?php echo $user->getSex(); ?></td>
                    <td><?php echo $user->getBirthday(); ?></td>
                    <td><?php echo $role; ?></td>
                    <td><?php echo $user->getBlock(); ?></td>
                    <!-- Thêm các cột dữ liệu khác tương ứng với các thuộc tính của đối tượng StaffEntity -->
                </tr>
            <?php
                $stt++; // Tăng giá trị của STT cho mỗi hóa đơn
            }
            ?>
        </tbody>
    </table>
    <div class="custom-pagination">
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

</body>

</html>
<script>
    function myFunction(row) {
        var selectedFoodId = row.getAttribute('data-id');
        var currentPage = <?php echo $page; ?>;
        console.log("ID của món ăn được chọn: " + selectedFoodId);
        // window.location.href = '?id=' + selectedFoodId;
        var url = '/Group-2-Saturday/Account?id=' + selectedFoodId + '&page=' + currentPage;
        var searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('search')) {
            url += '&search=' + searchParams.get('search');
        }

        window.location.href = url;
    }

    function updatePage(pageNumber) {
        var searchTxt = document.getElementById('search_txt').value.trim();
        var url = "?page=" + pageNumber + "&search=" + encodeURIComponent(searchTxt);
        window.location.href = url;
    }

    function reloadPage() {
        window.location.reload();
    }

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
<style>
    .table {
        width: 90%;
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

    .button-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        margin-top: 10px;
        max-height: 40px;
    }

    .button-group button {
        margin-right: 50px;
    }

    .button-group button:hover {
        font-weight: bold;
    }

    .button-group button:last-child {
        margin-right: 0;
    }

    h2 {
        text-align: center;
        color: #007bff;
        margin-top: 30px;
        padding: 10px 0;
    }

    .custom-pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .custom-pagination ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .custom-pagination li {
        margin: 0 5px;
    }

    .custom-pagination li a {
        text-decoration: none;
        padding: 5px 10px;
        border: 1px solid #ccc;
        color: #333;
    }

    .custom-pagination li a:hover {
        background-color: #f0f0f0;
    }

    .small-button {
        padding: 5px 10px;
        /* Điều chỉnh kích thước padding theo nhu cầu */
        font-size: 14px;
        background-color: #007bff;
        color: #fff;
        font-size: 14px;
        /* Điều chỉnh kích thước chữ theo nhu cầu */
    }

    .container_search {

        margin-bottom: -20px;
        margin-right: -100px;
    }

    .reload {
        background-color: #007bff;
        position: relative;
        color: #fff;
        top: 10px;
        /* Dịch xuống 10px */
        right: 90px;
        /* Dịch sang phải 10px */
        padding: 5px 10px;
        /* Điều chỉnh kích thước padding theo nhu cầu */
        font-size: 14px;
    }

    .selected-row {
        background-color: #ccc;
        /* Màu nền của hàng được chọn */
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