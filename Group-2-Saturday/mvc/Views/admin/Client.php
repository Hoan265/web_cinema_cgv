<?php

session_start();
require_once './mvc/Controllers/Client.php';
require_once './mvc/Controllers/Account.php';

$clientController = new Client();
$accountController = new Account();
$users = $accountController->getUsers();
$clients_TK = $clientController->getAllClient();



//////////////////      KIỂM TRA SỰ KIỆN /////////////////////////
$current_url = $_SERVER['REQUEST_URI'];
$url_parts = parse_url($current_url);
if (isset($url_parts['query'])) {
    parse_str($url_parts['query'], $query_params);
    if (isset($query_params['id'])) {
        $id = $query_params['id'];
        $user = $accountController->findbyId($id);
    }
    if (isset($query_params['page'])) {
        $recordsPerPage = 10;
        $page = isset($query_params['page']) ? $query_params['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $clients  = $clientController->getPaginatedClients($recordsPerPage, $offset);
        $totalRecords =  $clientController->countAllClients();
        $totalPages = ceil($totalRecords / $recordsPerPage);
    }
    if (isset($query_params['search'])) {
    $search_text = isset($_POST['search_txt']) ? $_POST['search_txt'] : (isset($query_params['search']) ? $query_params['search'] : '');
    if (!empty($search_text)) {
        $userSersch = $accountController->searchAccounts($search_text);
        $matchedClients = array();
                foreach($userSersch as $searchedUser) {
            foreach($clients_TK as $i) {
                if($searchedUser->getId() == $i->getIdClient()) {
                    $matchedClients[] = $i;
                }
            }
        }
        $clients = $matchedClients; 
        $searched = true;
        $totalRecords = count($clients);
        $recordsPerPage = 10;
        $totalPages = ceil($totalRecords / $recordsPerPage);
        $page = isset($query_params['page']) ? $query_params['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $clients = array_slice($clients, $offset, $recordsPerPage);
    }

    } else {
        $recordsPerPage = 10;
        $page = isset($query_params['page']) ? $query_params['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        $clients  = $clientController->getPaginatedClients($recordsPerPage, $offset);
        $totalRecords =  $clientController->countAllClients();
        $totalPages = ceil($totalRecords / $recordsPerPage);
    }

}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addClient'])) {
        $totalRecords =  $clientController->countAllClients();
        $totalPages = ceil($totalRecords / $recordsPerPage);
        $id =  $accountController->generateUniqueID();
        $email = $_POST['customerEmail'];
        $phone = $_POST['customerPhone'];
        $password = $_POST['customerPassword'];
        $name = $_POST['customerName'];
        $sex = $_POST['customerGender'];
        $birthday = $_POST['customerDOB'];
        $role = 'client'; // Không chắc chắn rằng người dùng mới đăng ký là admin hay client, bạn có thể thay đổi giá trị này tùy thuộc vào yêu cầu của ứng dụng của mình.
        $block = 'Off';
    
        // Đường dẫn tới thư mục chứa ảnh mặc định
        $defaultAvatarLink = '/Applications/XAMPP/xamppfiles/htdocs/Group-2-Saturday/assets/img/avatarUser/user.png';
    
        // Kiểm tra sự tồn tại và quyền truy cập của file ảnh mặc định
        if (!empty($defaultAvatarLink) && is_readable($defaultAvatarLink)) {
            $avatarData = file_get_contents($defaultAvatarLink);
            if ($avatarData === false) {
                echo '<p class="error" style="color: #e71a0f;">Không thể đọc nội dung file ảnh mặc định!</p>';
                // Sử dụng một giá trị mặc định khác cho avatar
                $defaultAvatarLink = '/Applications/XAMPP/xamppfiles/htdocs/Group-2-Saturday/assets/img/avatarUser/default_avatar.png';
                $avatarData = file_get_contents($defaultAvatarLink);
                if ($avatarData === false) {
                    echo '<p class="error" style="color: #e71a0f;">Không thể đọc được file ảnh mặc định thứ hai!</p>';
                    $avatarData = '';
                }
            }
        } else {
            echo '<p class="error" style="color: #e71a0f;">Đường dẫn file ảnh mặc định không hợp lệ hoặc không thể truy cập được!</p>';
            // Thiết lập giá trị mặc định cho avatar thành chuỗi rỗng
            $avatarData = ''; 
        }
    
        // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
        if ($accountController->checkEmailExists($email)) {
            $error_message = "Email đã tồn tại trong cơ sở dữ liệu! Thêm người dùng thất bại!!!";
            echo '<script>';
            echo 'setTimeout(function() {';
            echo '    window.location.href = "/Group-2-Saturday/Client?page=' . $page . '";';
            echo '}, 2000);';
            echo '</script>';
        } else {
            // Thêm người dùng mới
            $accountController->addAccount($id, $email, $phone, $password, $name, $sex, $avatarData, $birthday, $role, $block);
            // Tạo khách hàng mới
            $accountController->CreateClient($id);
            $success_message = "Thêm thành công người dùng với id là $id";
            echo '<script>';
            echo 'setTimeout(function() {';
            echo '    window.location.href = "/Group-2-Saturday/Client?page=' . $page . '";';
            echo '}, 2000);';
            echo '</script>';
        }
    }
     elseif (isset($_POST['updateClient'])) {
        $name = $_POST['updateCustomerName'];
        $phone = $_POST['updateCustomerPhone'];
        $sex = $_POST['updateCustomerGender'];
        $birthday = $_POST['updateCustomerDOB'];
        $block = isset($_POST['blockCustomer']) ? 'On' : 'Off';
        $accountController->updateBlockStatus($id, $block);
        $accountController->updateAccountByEmail($id, $name, $phone, $sex, $birthday);
        $success_message = "Cập nhật thành công thông tin  người dùng với id là  $id";
            echo '<script>';
            echo 'setTimeout(function() {';
            echo '    window.location.href = "/Group-2-Saturday/Client?id='.$id.'&page='.$page.'";';
            echo '}, 2000);';
            echo '</script>';
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <h2>Danh sách khách hàng</h2>
    <div class="button-group">
        <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#addCustomerModal">
            Thêm khách hàng
        </button>
        <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#updateCustomerModal">
            Cập nhật khách hàng
        </button>
    </div>
    <!-- ///////////////////////////////////////////////TÌM KIẾM /////////////////////////////////////////////// -->
    <form>
        <button type="submit" class="reload" name="reload">
            <i class="fas fa-sync-alt"></i> Reload
        </button>
    </form>
    <?php
    if (isset($success_message)) {
        echo '<div class="success-msg">' . $success_message . '</div>';
    }
    ?>
    <?php if (isset($error_message)) {
        echo '<div class="error-msg">' . $error_message . '</div>';
    } ?>
    <div class="container_search" style="text-align: right;">
        <form id="search_form" onsubmit="updatePage(1); return false;">
            <input type="text" id="search_txt" placeholder="Nhập từ khoá cần tìm kiếm..." style="width: 300px;">
            <button type="submit" class="small-button">Tìm kiếm</button>
        </form>
    </div>
    <!-- ///////////////////////////////////////////////THÊM KHÁCH HÀNG /////////////////////////////////////////////// -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Thêm khách hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="customerName">Tên</label>
                            <input type="text" class="form-control" id="customerName" name="customerName" placeholder="Nhập tên của khách hàng" required>
                        </div>
                        <div class="form-group">
                            <label for="customerPhone">Số điện thoại</label>
                            <input type="text" class="form-control" id="customerPhone" name="customerPhone" placeholder="Nhập số điện thoại" pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số" required>
                        </div>
                        <div class="form-group">
                            <label for="customerEmail">Email</label>
                            <input type="email" class="form-control" id="customerEmail" name="customerEmail" placeholder="Nhập email của khách hàng" required>
                        </div>
                        <div class="form-group">
                            <label for="customerPassword">Mật khẩu</label>
                            <input type="password" class="form-control" id="customerPassword" name="customerPassword" placeholder="Nhập mật khẩu" required>
                        </div>
                        <div class="form-group">
                            <label for="customerDOB">Ngày tháng</label>
                            <input type="date" class="form-control" id="customerDOB" name="customerDOB" min="1944-01-01" max="2008-12-31" title="Vui lòng chọn ngày tháng sinh từ năm 1944 đến năm 2008" required>
                        </div>
                        <div class="form-group">
                            <label for="customerGender">Giới tính</label>
                            <select class="form-control" id="customerGender" name="customerGender">
                                <option>Nam</option>
                                <option>Nữ</option>
                                <option>Khác</option>
                            </select>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="addClient">Lưu</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ///////////////////////////////////////////////CẬP NHẬP THÔNG TIN KHÁCH HÀNG /////////////////////////////////////////////// -->

    <div class="modal fade" id="updateCustomerModal" tabindex="-1" role="dialog" aria-labelledby="updateCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCustomerModalLabel">Cập nhật khách hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <?php
                        if ($user->getRole() == 'admin') {
                            $ten = '';
                            $email = '';
                            $phone = '';
                            $sex = 'Nam';
                            $birthday = '';
                            $pass = '';
                            $block = '';
                        } else {
                            $ten = $user->getName();
                            $email = $user->getEmail();
                            $phone = $user->getPhone();;
                            $sex = $user->getSex();
                            $birthday = $user->getBirthday();;
                            $pass = $user->getPassword();
                            $block = $user->getBlock();
                        }
                        ?>
                        <div class="form-group">
                            <label for="updateCustomerName">Tên</label>
                            <input type="text" class="form-control" id="updateCustomerName" name="updateCustomerName" placeholder="Nhập tên khách hàng" value="<?php echo $ten ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="updateCustomerPhone">Số điện thoại</label>
                            <input type="text" class="form-control" id="updateCustomerPhone" name="updateCustomerPhone" readonly value="<?php echo $phone ?>">
                        </div>
                        <div class="form-group">
                            <label for="updateCustomerEmail">Email</label>
                            <input type="email" class="form-control" id="updateCustomerEmail" name="updateCustomerEmail" readonly value="<?php echo $email ?>">
                        </div>
                        <div class="form-group">
                            <label for="updateCustomerPassword">Mật khẩu</label>
                            <input type="password" class="form-control" id="updateCustomerPassword" name="updateCustomerPassword" readonly value="<?php echo $pass ?>">
                        </div>
                        <div class="form-group">
                            <label for="updateCustomerDOB">Ngày tháng</label>
                            <input type="date" class="form-control" id="updateCustomerDOB" name="updateCustomerDOB" value="<?php echo $birthday ?>" required min="1944-01-01" max="2008-12-31">
                        </div>
                        <div class="form-group">
                            <label for="updateCustomerGender">Giới tính</label>
                            <select class="form-control" id="updateCustomerGender" name="updateCustomerGender">
                                <option value="Nam" <?php echo isset($sex) && $sex == 'Nam' ? ' selected' : ''; ?>>Nam</option>
                                <option value="Nữ" <?php echo isset($sex) && $sex == 'Nữ' ? ' selected' : ''; ?>>Nữ</option>
                                <option value="Khác" <?php echo isset($sex) && $sex == 'Khác' ? ' selected' : ''; ?>>Khác</option>
                            </select>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="blockCustomer" name="blockCustomer" <?php echo ($block == 'On') ? ' checked' : ''; ?>>
                            <label class="form-check-label" for="blockCustomer">Block?</label>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="updateClient">Lưu</button>
                </div>
                </form>
            </div>
        </div>
    </div>



    <!-- /////////////////////////////////////////////// THÔNG TIN KHÁCH HÀNG /////////////////////////////////////////////// -->
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã khách hàng</th>
                <th>Họ và Tên</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Block?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = $offset + 1;
            foreach ($clients as $client) {
                $user = $accountController->findbyId($client->getIdClient());
                //    $point = $clientController->getPOINT($client->getIdClient());
            ?>
                <tr class='food-row <?php echo ($client->getIdClient() == $id) ? "selected" : ""; ?>' data-id='<?php echo $client->getIdClient(); ?>' onclick='myFunction(this)'>
                    <td><?php echo $stt; ?></td>
                    <td><?php echo $client->getIdClient(); ?></td>
                    <td><?php echo $user->getName(); ?></td>
                    <td><?php echo $user->getPhone(); ?></td>
                    <td><?php echo $user->getEmail(); ?></td>
                    <td><?php echo $user->getBirthday(); ?></td>
                    <td><?php echo $user->getSex(); ?></td>
                    <td><?php echo $user->getBlock(); ?></td>
                </tr>
            <?php
                $stt++;
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
</body>

</html>
<script>
    function myFunction(row) {
        var selectedFoodId = row.getAttribute('data-id');
        var currentPage = <?php echo $page; ?>;
        console.log("ID của món ăn được chọn: " + selectedFoodId);
        var url = '/Group-2-Saturday/Client?id=' + selectedFoodId + '&page=' + currentPage;
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
</script>
<style>
    /* Thiết lập font chữ và màu nền cho trang */
    body {
        font-family: Arial, sans-serif;
        background-color: hsl(0, 0%, 85%);
        font-size: 16px;
    }

    /* Phần tiêu đề "Danh sách khách hàng" */
    h2 {
        text-align: center;
        margin-top: 20px;
    }

    /* Bảng hiển thị danh sách khách hàng */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Hiệu ứng bóng đổ */
    }

    thead {
        top: 0;
        /* Đặt vị trí ở trên cùng */
        background-color: darkgrey;
        /* Màu nền xmas */
        color: #000;
        /* Màu chữ đen */
    }

    /* Style cho các thẻ <th> và <td> trong bảng */
    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    /* Style cho thẻ <th> trong bảng */
    th {
        background-color: #f2f2f2;
    }

    /* Hover style cho các hàng của phần <tbody> */
    tbody tr:hover {
        background-color: lightskyblue;
        /* Màu nền xanh khi hover */
        color: #fff;
        /* Màu chữ trắng khi hover */
    }

    /* Style cho button */
    .btn-custom {
        background-color: #007bff;
        color: #fff;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 5px;
        max-width: 200px;
        /* Sử dụng max-width thay vì width: auto; */
    }

    /* Hover style cho button */
    .btn-custom:hover {
        background-color: #0041b9;
        color: aliceblue;
        font-weight: bold;
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

    .button-group {
        display: flex;
        margin-bottom: 10px;
        margin-top: 10px;
        max-height: 60px;
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
        margin-top: -40px;
        margin-right: 50px;
    }

    .reload {
        background-color: #007bff;
        max-width: 150px;
        height: 40px;
        position: relative;
        color: #fff;
        top: -55px;
        /* Dịch xuống 10px */
        left: 480px;
        /* Dịch sang phải 10px */
        padding: 5px 10px;
        /* Điều chỉnh kích thước padding theo nhu cầu */
        font-size: 14px;
        border-radius: 5px;
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
        max-width: 900px;
    }
    .error-msg {
        background-color: hwb(0 66% 0%);
        color: #e72626;
        padding: 1em 1em;
        border-radius: 5px;
        margin: 0 0 1.5em;
        text-align: center;
        font-size: 14px;
        max-width: 900px;
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