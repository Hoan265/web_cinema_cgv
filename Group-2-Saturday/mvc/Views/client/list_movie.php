<?php
// function getActive() {
//     $directoryURI = $_SERVER['REQUEST_URI'];
//     $path = parse_url($directoryURI, PHP_URL_PATH);
//     $components = explode('?',$path);
//     return $components[1];
// }
// function getActive()
// {
//     $directoryURI = $_SERVER['REQUEST_URI'];
//     // $path = parse_url($directoryURI, PHP_URL_PATH);
//     $components = explode('?', $directoryURI);
//     if (count($components) > 1) {
//         return $components[1];
//     } else {
//         return ""; // hoặc bất kỳ giá trị mặc định nào bạn muốn trả về khi không có tham số truy vấn
//     }
// }
function getActiveMovie() {
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_QUERY);
    $components = explode('?',$path);
    return $components[0];
};
$current_movie = getActiveMovie();
?>
<section class="breadcrumb-area breadcrumb-bg" data-background="assets/img/bg/breadcrumb_bg.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h3 class="title">Danh Sách Phim</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item <?php echo $current_movie == "" ? " active" : ""; ?>">
                                <h7 href="">TẤT CẢ PHIM</h7>
                            </li>
                            <li class="breadcrumb-item <?php echo $current_movie == "upcoming_movies" ? " active" : ""; ?>" aria-current="page">
                                <h7 href="">PHIM SẮP CHIẾU</h7>
                            </li>
                            <li class="breadcrumb-item <?php echo $current_movie == "current_movies" ? " active" : ""; ?>" aria-current="page">
                                <h7 href="">PHIM ĐANG CHIẾU</h7>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="movie-area movie-bg" data-background="assets/img/bg/movie_bg.jpg">
    <div class="container">
        <div class="row align-items-end mb-60">
            <!-- <div class="col-lg-6">
                <div class="section-title text-center text-lg-left">
                </div>
            </div> -->
            <div class="col-lg-6">
                <form action="#" id="search-form" onsubmit="return false;" style="width: 220%;">
                    <div class="movie-page-meta">
                        <input type="text" class="form-control form-control-alt" id="search-input" name="search-input" placeholder="Tìm kiếm phim..." style="width: 80rem">
                        <!-- The Loai  -->
                        <ul class="dropdown-menu mt-1" aria-labelledby="dropdown-filter-role">
                            <li><a class="dropdown-item filtered-by-TheLoai" href="javascript:void(0)" data-id="Tất cả">Tất cả</a></li>
                            <?php
                            foreach ($data["TheLoai"] as $theloai) {
                                echo '<li><a class="dropdown-item filtered-by-TheLoai" href="javascript:void(0)" data-id="' . $theloai . '">' . $theloai . '</a></li>';
                            }
                            ?>
                        </ul>
                        <button class="btn btn-alt-secondary dropdown-toggle btn-filtered-by-TheLoai" id="dropdown-filter-TheLoai" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 12rem;margin-right: 1.5rem">Tất cả</button>
                        <label style="font-weight: bold;width: 14rem;font-size: 1rem;margin: 1rem 1rem 1rem 0;">Thể loại </label>
                        <!-- Noi SX -->
                        <div class="movie-page-meta""> 
                        <ul class=" dropdown-menu mt-1" aria-labelledby="dropdown-filter-role">
                            <li><a class="dropdown-item filtered-by-NoiSX" href="javascript:void(0)" data-id="Tất cả">Tất cả</a></li>
                            <?php
                            foreach ($data["NoiSX"] as $noisx) {
                                echo '<li><a class="dropdown-item filtered-by-NoiSX" href="javascript:void(0)" data-id="' . $noisx['NoiSX'] . '">' . $noisx['NoiSX'] . '</a></li>';
                            }
                            ?>
                            </ul>
                            <button class="btn btn-alt-secondary dropdown-toggle btn-filtered-by-NoiSX" id="dropdown-filter-NoiSX" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 10rem;margin-right: 1.5rem">Tất cả</button>
                            <label style="font-weight: bold;width: 5rem;font-size: 1rem;margin: 1rem 1rem 1rem 0;">Quốc gia </label>
                        </div>
                        <!-- Nam SX -->
                        <div class="movie-page-meta""> 
                        <ul class=" dropdown-menu mt-1" aria-labelledby="dropdown-filter-role">
                            <li><a class="dropdown-item filtered-by-NamSX" href="javascript:void(0)" data-id="0">Tất cả</a></li>
                            <?php
                            foreach ($data["NamSX"] as $namsx) {
                                echo '<li><a class="dropdown-item filtered-by-NamSX" href="javascript:void(0)" data-id="' . $namsx['NamSX'] . '">' . $namsx['NamSX'] . '</a></li>';
                            }
                            ?>
                            </ul>
                            <button class="btn btn-alt-secondary dropdown-toggle btn-filtered-by-NamSX" id="dropdown-filter-NamSX" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 7rem;margin-right: 1.5rem">Tất cả</button>
                            <label style="font-weight: bold;width: 7rem;font-size: 1rem;margin: 1rem 1rem 1rem 0;">Năm sản xuất </label>
                        </div>
                        <!-- Do Tuoi  -->
                        <div class="movie-page-meta""> 
                        <ul class=" dropdown-menu mt-1" aria-labelledby="dropdown-filter-role">
                            <li><a class="dropdown-item filtered-by-DoTuoi" href="javascript:void(0)" data-id="0">Tất cả</a></li>
                            <?php
                            foreach ($data["DoTuoi"] as $dotuoi) {
                                echo '<li><a class="dropdown-item filtered-by-DoTuoi" href="javascript:void(0)" data-id="' . $dotuoi['DoTuoi'] . '"> Trên ' . $dotuoi['DoTuoi'] . ' tuổi </a></li>';
                            }
                            ?>
                            </ul>
                            <button class="btn btn-alt-secondary dropdown-toggle btn-filtered-by-DoTuoi" id="dropdown-filter-DoTuoi" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 8rem;margin-right: 1.5rem">Tất cả</button>
                            <label style="font-weight: bold;width: 4rem;font-size: 1rem;margin: 1rem 1rem 1rem 0;">Độ tuổi </label>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="row justify-content-center">

        </div>
        <div class="row align-items-end mb-60 dis_none">
            <div class="col-lg-6">
                <div class="section-title text-center text-lg-left">
                    <span class="sub-title">Hãy đặt ngay</span>
                    <h2 class="title" id="title_movie" <?php if ($current_movie == "") {

                                                            echo 'data-id="0"' . '>TẤT CẢ PHIM';
                                                        } elseif ($current_movie == "current_movies") {
                                                            echo 'data-id="1"' . '>PHIM ĐANG CHIẾU';
                                                        } else {
                                                            echo 'data-id="2"' . '>PHIM SẮP CHIẾU';
                                                        }
                                                        ?> </h2>
                </div>
            </div>
        </div>
        <div class="row dis_none" id="list-movies">
            <!-- add list movie to here  -->

        </div>
        <div class="row">
            <div class="col-12">
                <div class="pagination-wrap mt-30" style="justify-content: flex-end">
                    <nav>
                        <ul>
                            <?php
                            if (isset($data["Plugin"]["pagination"]))
                                require "./mvc/Views/inc/client/pagination.php"
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .equal-height img {
        height: 300px;
        width: auto;
        object-fit: cover;
    }

    .movie-item {
        transition: transform 0.3s ease-in-out;
    }

    .movie-item:hover {
        transform: scale(1.05);
    }
</style>