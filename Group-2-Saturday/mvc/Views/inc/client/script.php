<script src="./assets/js/dashmix.app.min.js"></script>
<script src="./assets/js/lib/jquery.min.js"></script>
<?php
if(isset($data["Plugin"]["datepicker"]) && $data["Plugin"]["datepicker"] == 1) {
    echo '<script src="./assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>';
}
if(isset($data["Plugin"]["flatpickr"]) && $data["Plugin"]["flatpickr"] == 1) {
    echo '<script src="./assets/js/plugins/flatpickr/flatpickr.min.js"></script>';
}
if(isset($data["Plugin"]["select"]) && $data["Plugin"]["select"] == 1) {
    echo '<script src="./assets/js/plugins/select2/js/select2.full.min.js"></script>';
}
if(isset($data["Plugin"]["ckeditor"]) && $data["Plugin"]["ckeditor"] == 1) {
    echo '<script src="./assets/js/plugins/ckeditor/ckeditor.js"></script>';
}
if(isset($data["Plugin"]["notify"]) && $data["Plugin"]["notify"] == 1) {
    echo '<script src="./assets/js/plugins/bootstrap-notify/bootstrap-notify.js"></script>';
}
if(isset($data["Plugin"]["chart"]) && $data["Plugin"]["chart"] == 1) {
    echo '<script src="./assets/js/plugins/chart.js/chart.min.js"></script>';
}
if(isset($data["Plugin"]["ckeditor-5"]) && $data["Plugin"]["ckeditor-5"] == 1) {
    echo '<script src="./assets/js/plugins/ckeditor5-classic/build/ckeditor.js"></script>';
}
if(isset($data["Plugin"]["sweetalert2"]) && $data["Plugin"]["sweetalert2"] == 1) {
    echo '<script src="./assets/js/plugins/sweetalert2/sweetalert2.min.js"></script>';
}
if(isset($data["Plugin"]["jquery-validate"]) && $data["Plugin"]["jquery-validate"] == 1) {
    echo '<script src="./assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>';
}

if(isset($data["Plugin"]["slick"]) && $data["Plugin"]["slick"] == 1) {
    echo '<script src="./assets/js/plugins/slick-carousel/slick.min.js"></script>';
}
if(isset($data["Plugin"]["jq-appear"]) && $data["Plugin"]["jq-appear"] == 1) {
    echo '<script src="./asseassetssts/js/plugins/jquery-appear/jquery.appear.min.js"></script>';
}
// --> day
if(isset($data["Plugin"]["pagination"])) {
    echo '<script src="./assets/js/pagination.js"></script>';
}
// if(isset($data["Plugin"]["jquery-validate"]) && $data["Plugin"]["jquery-validate"] == 1) {
//     echo '<script src="./assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>';
// }
if(isset($data["Script"])) {
     echo '<script src="./assets/js/pages/'.$data["Script"].'.js"></script>';
 }
?>