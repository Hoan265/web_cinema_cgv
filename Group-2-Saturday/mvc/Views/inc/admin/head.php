<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo $data["Title"] ?></title>
    <meta name="description" content="Rạp chiếu phim CGV lớn nhất Việt Nam">
    <meta name="author" content="pixelcave">
    <!-- Open Graph Meta -->

    <meta property="og:title" content="Rạp chiếu phim CGV">
    <meta property="og:site_name" content="Rạp chiếu phim CGV">
    <meta property="og:description" content="Rạp chiếu phim CGV lớn nhất Việt Nam">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <link rel="shortcut icon" type="./assets/image/x-icon" href="./assets/img/favicon.png">


    <?php
    if (isset($data["Plugin"]["datepicker"]) && $data["Plugin"]["datepicker"] == 1) {
        echo '<link rel="stylesheet" href="./assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">';
    }
    if (isset($data["Plugin"]["flatpickr"]) && $data["Plugin"]["flatpickr"] == 1) {
        echo '<link rel="stylesheet" href="./assets/js/plugins/flatpickr/flatpickr.min.css">';
    }
    if (isset($data["Plugin"]["select"]) && $data["Plugin"]["select"] == 1) {
        echo '<link rel="stylesheet" href="./assets/js/plugins/select2/css/select2.min.css">';
    }
    if (isset($data["Plugin"]["slick"]) && $data["Plugin"]["slick"] == 1) {
        echo '<link rel="stylesheet" href="./assets/js/plugins/slick-carousel/slick.css">';
        echo '<link rel="stylesheet" href="./assets/js/plugins/slick-carousel/slick-theme.css">';
    }
    if (isset($data["Plugin"]["sweetalert2"]) && $data["Plugin"]["sweetalert2"] == 1) {
        echo "<link rel=\"stylesheet\" href=\"./assets/js/plugins/sweetalert2/sweetalert2.min.css\">\n";
    }
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" id="css-main" href="./assets/css/dashmix.css">
    <link rel="stylesheet" id="css-main" href="./assets/css/custom.css">
    <script src="./assets/js/lib/jquery.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="./assets/js/plugins/dashmix.app.min.js"></script> -->
</head>