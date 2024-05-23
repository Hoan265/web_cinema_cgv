<?php
session_start();
require_once './mvc/Controllers/Account.php';
$accountController = new Account();
$email = $_SESSION['email_client'];
$email_admin = $_SESSION['email_admin'];
$user = $accountController->getUserByEmail($email);

$isLoggedIn = isset($_SESSION['email_client']); // Kiểm tra xem người dùng đã đăng nhập hay chưa

if ($isLoggedIn) {
    $name = $user->getName(); // Nếu đã đăng nhập, lấy tên từ đối tượng $user
} else {
    $name = "Đăng Nhập"; // Nếu chưa đăng nhập, đặt tên là "Đăng Nhập"
}

?>
<section class="movie-details-area" data-background="assets/img/bg/movie_details_bg.jpg">
    <div class="container">
        <div class="row align-items-center position-relative">
            <div class="col-xl-3 col-lg-4">
                <div class="movie-details-img">
                    <div class="movie-poster text-center equal-height">
                        <img id="HinhAnh" src="" alt="">
                    </div>
                    <a id="trailer" href="" class="popup-video"><img src="assets/img/images/play_icon.png" alt=""></a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-8">
                <div class="movie-details-content">
                    <!-- <h5>New Episodes</h5> -->
                    <h2><span id="TenPhim"><?php
                                            // echo $movie['name_movie']; 
                                            ?></span>
                    </h2>
                    <div class="banner-meta">
                        <ul>
                            <li class="quality">
                                <span id="DoTuoi"><?php
                                                    // echo $movie['age_limit']; 
                                                    ?>
                                </span>
                                <span>hd</span>
                            </li>


                            <li class="category">
                                <a class="TheLoai" href="#"><?php
                                                            //  echo $movie['id_category']; 
                                                            ?></a>
                            </li>
                            <li class="category">
                                <a href="#" id="DaoDien"><?php
                                                            // echo $movie['author']; 
                                                            ?></a>
                            </li>
                            <li class="category">
                                <a href="#" id="DV"><?php
                                                    // echo $movie['performer']; 
                                                    ?></a>
                            </li>
                            <li class="release-time">
                                <span><i class="far fa-calendar-alt"></i>
                                    <span id="NamSX"></span>

                                    <?php
                                    // echo $movie['year']; 
                                    ?></span>
                                <span><i class="far fa-clock"></i>
                                    <span id="ThoiLuong"></span>
                                    <?php
                                    // echo $movie['time']; 
                                    ?></span>
                            </li>
                        </ul>
                    </div>
                    <p class="NoiDung"><?php
                                        // echo $movie['content']; 
                                        ?></p>
                    <div class="movie-details-prime">
                        <ul>
                            <li class="share"><a href="#"><i class="fas fa-share-alt"></i>Chia sẻ</a></li>
                            <li class="streaming">

                                <!-- <h6>Prime Video</h6>
                                    <span>Streaming Channels</span> -->
                            </li>

                            <!-- <li class="watch"><a href="./booking" class="btn"><i class="fas fa-play"></i>ĐẶT VÉ NGAY</a></li> -->
                            <?php
                            if ($name == "Đăng Nhập") {
                                echo '<li class="watch"><a href="./Login" class="btn"><i class="fas fa-play"></i>ĐẶT VÉ NGAY</a></li>';
                            } else {
                                echo '<li class="watch"><a href="./booking" class="btn"><i class="fas fa-play"></i>ĐẶT VÉ NGAY</a></li>';
                            }
                            ?>
                            <?php
                            // if (isset($_SESSION['user'])) {
                            //     echo '  <li class="watch"><a href="./booking" class="btn"><i class="fas fa-play"></i>ĐẶT VÉ NGAY</a></li>';
                            // } else {
                            //     echo '  <li class="watch"><a href="./Login" class="btn"><i class="fas fa-play"></i>ĐẶT VÉ NGAY</a></li>';
                            // }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="movie-history-wrap">
                    <h3 class="title">TÓM TẮT <span>PHIM</span></h3>
                    <p class="NoiDung"><?php
                                        //  echo $movie['content']; 
                                        ?></p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="blog-comment mb-10">
                <div class="contact-form-wrap">
                    <div class="widget-title mb-50 flex">
                        <h5 class="title">Bình luận : </h5>

                    </div>
                    <style>
                        .flex {
                            display: flex;
                            justify-content: space-between;
                        }
                    </style>
                    <div class="contact-form">
                        <form action="<?php
                                        // echo $movie['id_movie'] 
                                        ?>" method="post">
                            <input hidden type="text" name="name_user" value="<?php
                                                                                // echo $name_clinet 
                                                                                ?>">
                            <input hidden type="text" name="id_movie" value="<?php
                                                                                // echo $movie['id_movie'] 
                                                                                ?>">
                            <textarea name="content" required placeholder="Nội dung bình luận . . ."></textarea>
                            <input class="btn" name="submit" type="submit" value="Gủi">
                            <!-- <button name="submit" class="btn">Gủi</button> -->
                        </form>
                    </div>
                </div>
                <div class="widget-title mb-45">
                    <h5 class="title">Bình luận mới : </h5>
                </div>
                <?php
                // foreach ($list_comment as $comment) : 
                ?>
                <ul>
                    <li>
                        <div class="single-comment">
                            <div class="comment-avatar-img">
                                <img src="assets/img/avatarUser/user.png" alt="img">
                            </div>
                            <div class="comment-text">
                                <div class="comment-avatar-info">
                                    <h5> <span class="comment-date">
                                        </span></h5>
                                </div>
                                <span></span>
                            </div>
                        </div>
                    </li>
                    <br>
                </ul>

            </div>

        </div>
    </div>
</section>



<section class="tv-series-area tv-series-bg" data-background="assets/img/bg/tv_series_bg02.jpg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center mb-50">
                    <span class="sub-title">những phim có cùng</span>
                    <h2 class="title">THỂ LOẠI <span class="TheLoai" style=" text-transform: uppercase;  color: white;"></span></h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" id="list-movie">

        </div>
        <div class="col-12">
            <div class="pagination-wrap mt-30">
                <?php if (isset($data["Plugin"]["pagination"]))
                    require "./mvc/Views/inc/client/pagination.php" ?>
            </div>
        </div>
    </div>
</section>
<style>
    /* Adjust the height as needed */
    .equal-height img {
        height: 350px;
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

    .blog-comment {
        width: 100%;
        /* max-width: 73%; */
        margin: 0 auto;
        padding: 20px;
    }

    .title {
        /* color: #333; */
        display: inline-block;
        /* Đảm bảo span không bị "đẩy" ra khỏi dòng */
    }

    .title span {
        color: #e44d26;
        /* Màu của từ 'PHIM' */
    }

    .movie-history-wrap {
        margin-bottom: 30px;
    }

    .movie-history-wrap p {
        line-height: 1.6;
        color: #666;
    }

    .blog-comment {
        margin-bottom: 80px;
    }

    .widget-title {
        margin-bottom: 45px;
    }

    .title.comment-date {
        color: #888;
    }

    .single-comment {
        display: flex;
        margin-bottom: 30px;
    }

    .comment-avatar-img img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
    }

    .comment-text {
        flex-grow: 1;
    }

    .comment-avatar-info h5 {
        margin-bottom: 5px;
    }

    .contact-form-wrap {
        flex-grow: 1;
    }

    .contact-form textarea {
        width: calc(100% - 30px);
        /* Thêm giảm kích thước padding để tránh làm rộng textarea quá mức */
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        resize: vertical;
    }

    .contact-form input {
        background-color: #e44d26;
        color: #fff;
        padding: 15px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>