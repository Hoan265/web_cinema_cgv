<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        table {
            margin: 50px auto;
            width: 90%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            color: black;
            font-size: 15px;
        }

        th {
            background-color: #f2f2f2;
        }

        .ttrr {
            background-color: #f9f9f9;
        }

        .title-info {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 20px;
        }

        .title-info h3 {
            margin: 0;
        }

        .title-info a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .title-info a:hover {
            text-decoration: underline;
        }
        #list-myticket td.text-center {
            color: black;
        }
        body {
            background-image: url('./assets/img/bg/movie_details_bg.jpg');
        }
    </style>
</head>

<body>
    <div class="title-info">
        <h3>Lịch sử đặt vé</h3>
    </div>
    <form action="#" id="search-form" onsubmit="return false;">
                <div class="row mb-4">
                    <div class="input-group">
                        <div class="col-md-6 d-flex gap-3">                           
                            <input type="text" class="form-control form-control-alt" id="search-input" name="search-input" placeholder="Tìm kiếm...">
                        </div>
                    </div>
                </div>
            </form>

    <table class="table table-vcenter">
        <thead>
            <tr>
                <th class="text-center" style="width: 100px;">ID</th>
                <th class="text-center">Tên phim</th>
                <th class="text-center">Phòng chiếu</th>
                <th class="text-center">Ghế</th>
                <th class="text-center">Ngày chiếu</th>
                <th class="text-center">Giờ chiếu</th>
                <th class="text-center">Số Ghế</th>
                <th class="text-center">Tổng tiền</th>
                <th class="text-center">Tình Trạng</th>
            </tr>
        </thead>
        <tbody id="list-myticket">

        </tbody>
    </table>
    </div>
    <?php if (isset($data["Plugin"]["pagination"]))
        require "./mvc/Views/inc/admin/pagination.php" ?>
    </div>
</body>

</html>