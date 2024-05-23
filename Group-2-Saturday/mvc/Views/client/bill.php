<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<style>
		.invoice-box {
			max-width: 800px;
			margin: auto;
			padding: 30px;
			border: 1px solid #eee;
			box-shadow: 0 0 10px rgba(0, 0, 0, .15);
			font-size: 16px;
			line-height: 24px;
			font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			color: #555;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
		}

		.invoice-box table td {
			padding: 5px;
			vertical-align: top;
		}

		.invoice-box table tr td:nth-child(2) {
			text-align: right;
		}

		.invoice-box table tr.top table td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.top table td.title {
			font-size: 45px;
			line-height: 45px;
			color: #333;
		}

		.invoice-box table tr.information table td {
			padding-bottom: 40px;
		}

		.invoice-box table tr.heading td {
			background: #eee;
			border-bottom: 1px solid #ddd;
			font-weight: bold;
		}

		.invoice-box table tr.details td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.item td {
			border-bottom: 1px solid #eee;
		}

		.invoice-box table tr.item.last td {
			border-bottom: none;
		}

		.invoice-box table tr.total td:nth-child(2) {
			border-top: 2px solid #eee;
			font-weight: bold;
		}

		@media only screen and (max-width: 600px) {
			.invoice-box table tr.top table td {
				width: 100%;
				display: block;
				text-align: center;
			}

			.invoice-box table tr.information table td {
				width: 100%;
				display: block;
				text-align: center;
			}
		}

		/** RTL **/
		.rtl {
			direction: rtl;
			font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
		}

		.rtl table {
			text-align: right;
		}

		.rtl table tr td:nth-child(2) {
			text-align: left;
		}

		.footer-brand {
			overflow: hidden;
		}

		.footer-brand:before {
			content: "";
			display: block;
			position: relative;
			background: #aa964c;
			box-shadow: 0px 8px 0px rgba(0, 0, 0, 0.1);
		}

		.footer-brand .footer-heading {
			position: relative;
			/* top: 0vmax;
			left: 1vmax; */
			padding: 0;
			margin: 0;
			color: #fff;
			font-size: 1em;
			font-family: "Lobster", cursive;
			text-shadow: 2px 2px 0px #6e5a11, 4px 4px 0px #836d24, 6px 6px 0px #a88616,
				8px 8px 0px #b08909, 10px 10px 0px #ab995e;

		}
	</style>
	<!-- <script src="_.js "></script> -->
</head>

<body>

	<div>
		<?php
		// include "connection.php";
		// $db = mysqli_select_db($con, "cinema_db");
		// $_GET['id'] = 'cash';
		// $qry = "select * from bookingtable where ORDERID = '" . $_GET['id'] . "'";
		// $qry = "select * from bookingtable where ORDERID = 'cash' ";
		// if ((!$_GET['id'])) {
		// 	echo "<script>alert('You are Not Suppose to come Here Directly');window.location.href='index.php';</script>";
		// }
		// $result = mysqli_query($con, $qry);
		// if (mysqli_num_rows($result) == 0) {
		// 	echo "No rows found, nothing to print so am exiting";
		// 	exit;
		// }
		// while ($row = mysqli_fetch_assoc($result)) {
		// 	$bookingid = $row['bookingID'];
		// 	$movieID = $row['movieID'];
		// 	$bookingFName = $row['bookingFName'];
		// 	$bookingLName = $row['bookingLName'];
		// 	$mobile = $row['bookingPNumber'];
		// 	$email = $row['bookingEmail'];
		// 	$date = $row['bookingDate'];
		// 	$theatre = $row['bookingTheatre'];
		// 	$type = $row['bookingType'];
		// 	$time = $row['bookingTime'];
		// 	$amount = $row['amount'];
		// 	$ORDERID = $row['ORDERID'];
		// 	$date = $row['DATE-TIME'];
		// }
		$hours = $_POST['hours'];
		$month = $_POST['month'];
		$cinema = $_POST['cinema'];
		$id_movie = $_POST['id_movie'];
		$room = $_POST['room'];
		$seats = $_POST['seats'];
		$tickets = $_POST['tickets'];
		$combos = $_POST['combos'];
		$amount = $_POST['amount'];
		$bankCode = $_POST['bankCode'];
		$username = 'Huỳnh Quốc Tiến';
		$phone = '0977054055';
		$email ='quoctien@gmail.com';	
		function formatPrice($price)
		{
			// Lấy giá trị số từ chuỗi
			$so_tien_format = number_format($price, 0, ',', '.'); // Định dạng số tiền
			echo $so_tien_format . " VNĐ";
		}
		?>
	</div>
	<br>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="2">
					<table>
						<tr>
							<td class="title">
								<div class="footer-brand">
									<a href="./home">
										<img src="assets/img/logo/logo.png" alt="Logo" style="width: 30%;">
									</a>
								</div>
							</td>
							<td>
								Invoice #: <?php
											// echo $ORDERID;
											?><br>
								Created: <?php
										// date_default_timezone_set('Asia/Kolkata');
										// echo $date = DATE("d-m-y h:i:s", strtotime($date));

										date_default_timezone_set('Asia/Kolkata');
										$date = date("d-m-y h:i:s"); // Getting the current date and time in IST
										echo $date;

										?><br>
								Due: <?php
									echo "After 24 Hours";
									?>
								<!-- 1 Day = 24*60*60 = 86400 -->
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="information">
				<td colspan="2">
					<table>
						<tr>
							<td>
								Trường Đại học Sài Gòn<br>
								Giảng viên - Thầy Cao Thái Phương Thanh<br>
								Nhóm 2 - Sáng thứ 7 - HKII<br>
							</td>

							<td>
								Họ và tên: <?php
								echo $username
								?><br>
								Số điện thoại: <?php
								echo $phone;
								?><br>
								Email: <?php
								echo $email; 
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="heading">
				<td>
					Payment Status
				</td>

				<td>
					Check #
				</td>
			</tr>

			<tr class="details">
				<td>
					Success
				</td>

				<td>
					<?php
					formatPrice($amount);
					// echo 'RS ' . $amount; 
					?>
				</td>
			</tr>
			<tr class="heading">
				<td>
					Movie Details
				</td>

				<td>
					Info
				</td>
			</tr>

			<tr class="item">
				<td>
					Movie Date
				</td>

				<td>
					<?php
					echo $month . date('/Y') . ' ' . $hours;
					?>
				</td>
			</tr>

			<tr class="item">
				<td>
					Theatre Type
				</td>

				<td>
					<?php
					echo $room;
					?> </td>
			</tr>

			<tr class="item">
				<td>
					Seats Type
				</td>

				<td>
					<?php
					// Sử dụng preg_split để tách chuỗi
					$matches = preg_split('/(?=[A-Z])/', $seats, -1, PREG_SPLIT_NO_EMPTY);

					// Kết hợp các phần tử thành một chuỗi, phân cách bởi dấu phẩy
					$result = implode(",", $matches);

					// In ra kết quả
					echo $result;
					?>
				</td>
			</tr>
			<tr class="item">
				<td>
					Ticket Price
				</td>

				<td>
					<?php
					// formatPrice($tickets);
					echo $tickets;
                         
					?>
				</td>
			</tr>
			<tr class="item">
				<td>
					Food Price
				</td>

				<td>
					<?php
					// formatPrice($combos);
					echo $combos;
					?>
				</td>
			</tr>
			<tr class="item last">
				<td>
					Discount
				</td>

				<td>
					<?php
					formatPrice(0);
					?>
				</td>
			</tr>
			<tr class="total">
				<td></td>

				<td>
					Total: <?php
							formatPrice($amount);
							?>
				</td>
			</tr>

		</table>
		<?php
		// include "phpqrcode/qrlib.php";
		// QRcode::png("Bookingid=$bookingid,
		// MovieID=$movieID,
		// First Name=$bookingFName,
		// Last Name=$bookingLName,
		// Number=$mobile,
		// Email=$email,
		// date=$date,
		// Theatre=$theatre,
		// TYPE=$type,
		// Time=$time,
		// amount=$amount,
		// OrderID=$ORDERID", "verify.png ", "L", 5, 5);
		// echo "<img src='verify.png' width='auto' height='120'>";
		?>

	</div>

	<div style="max-width: 300px; margin:auto; padding: 30px; display: flex">
		<input type="button" class="btn btn-danger" onClick="window.print()" value="In hoá đơn" />
		<a type="button" class="btn btn-success" href='http://localhost/Group-2-Saturday/'>Trang chủ</a>
	</div>


	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>