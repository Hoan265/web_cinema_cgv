

<section class="movie-area movie-bg" data-background="assets/img/bg/movie_bg.jpg">
    <div class="container">
        <div class="row align-items-end mb-60">
            <div class="col-lg-6">
                <div class="section-title text-center text-lg-left">
                    <span class="sub-title">Hãy xem ngay nào</span>
                    <h2 class="title">Phim mới phát hành</h2>
                </div>
            </div>
        </div>
        <div class="row tr-movie-active">


        </div>
    </div>
</section>
<style>
    /* Adjust the height as needed */
    .equal-height img {
        height: 300px;
        /* Set your desired height */
        width: auto;
        object-fit: cover;
        /* This property ensures that the image covers the entire box even if it has to be cropped */
    }

    /* Optional: Add some styling to the movie items */
    .movie-item {
        transition: transform 0.3s ease-in-out;
    }

    .movie-item:hover {
        transform: scale(1.05);
    }
</style>
<section class="services-area services-bg-two" data-background="assets/img/bg/services_bg02.jpg" style="background-image: url(&quot;assets/img/bg/services_bg02.jpg&quot;);">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 order-0 order-lg-2">
                <div class="services-img-wrap">
                    <img src="assets/img/images/services_img02.jpg" alt="">
                </div>
            </div>
            <div class="col-xl-5 col-lg-6">
                <div class="services-content-wrap">
                    <div class="section-title mb-40">
                        <span class="sub-title">Ứng dụng</span>
                        <h2 class="title">TẢI XUỐNG ỨNG DỤNG ĐỂ CÓ THỂ ĐẶT VÉ Ở MỌI NƠI NGAY TRÊN ĐIỆN THOẠI CỦA BẠN</h2>
                    </div>
                    <div class="services-list">
                        <ul>
                            <li>
                                <div class="icon">
                                    <i class="flaticon-television"></i>
                                </div>
                                <div class="content">
                                    <h5>Cập nhật tin tức phim mới nhất</h5>
                                    <p>
                                        Lorem ipsum dolor sit amet, consecetur adipiscing
                                        elit, sed do eiusmod tempor.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="flaticon-video-camera"></i>
                                </div>
                                <div class="content">
                                    <h5>Đặt vé ở mọi nơi</h5>
                                    <p>
                                        Lorem ipsum dolor sit amet, consecetur adipiscing
                                        elit, sed do eiusmod tempor.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- services-area-end -->

<!-- top-rated-movie -->
<section class="top-rated-movie tr-movie-bg2" data-background="assets/img/bg/tr_movies_bg.jpg" style="background-image: url(&quot;assets/img/bg/tr_movies_bg.jpg&quot;);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title title-style-three text-center mb-70">
                    <span class="sub-title">Tại sao không thử xem</span>
                    <h2 class="title">Danh sách phim hay</h2>
                </div>
                <div class="row movie-item-row" id="list-movie">
                    <!-- Danh sach Phim add o day day -->

                </div>
            </div>
        </div>
        <div class="row movie-item-row">

        </div>
        <div class="row">
            <div class="col-12">
                <div class="pagination-wrap mt-30">
                    <!-- <nav>
                        <ul>
                            
                        </ul>
                    </nav> -->
                    <?php if (isset($data["Plugin"]["pagination"]))
                        require "./mvc/Views/inc/client/pagination.php" ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- top-rated-movie-end -->