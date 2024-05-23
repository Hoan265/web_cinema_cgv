<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> <?php echo $data['Title'] ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="./assets/image/x-icon" href="./assets/img/favicon.png">
    <!-- Place favicon.ico in the root directory -->
    <!-- CSS here -->
    <!-- <link id="bootstrap-css" rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/css/custom.css">
        <link rel="stylesheet" href="./assets/css/animate.min.css">
        <link rel="stylesheet" href="./assets/css/magnific-popup.css">
        <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="./assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="./assets/css/flaticon.css">
        <link id="dashmix-css" rel="stylesheet" href="./assets/css/dashmix.css">
        <link rel="stylesheet" href="./assets/css/odometer.css">
        <link rel="stylesheet" href="./assets/css/aos.css">
        <link rel="stylesheet" href="./assets/css/slick.css">
        <link rel="stylesheet" href="./assets/css/default.css">
        <link id="style-css" rel="stylesheet" href="./assets/css/style.css">
        <link rel="stylesheet" href="./assets/css/responsive.css"> -->
    <?php
    if (isset($data["CSS"])) {
        echo '<link rel="stylesheet" href="assets/css/' . $data["CSS"] . '.css">';
    }
    if ($data["Page"] == 'booking') {
        echo '<link rel="stylesheet" href="assets/css/booking.css">';
    }
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
    <script src="./assets/js/lib/jquery.min.js"></script>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/odometer.css">
    <link rel="stylesheet" href="assets/css/aos.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/default.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
</head>