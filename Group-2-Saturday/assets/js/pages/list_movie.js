// Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker']);
const showData = function (movies) {
     let html = "";
     movies.forEach((movie) => {
       html += `
       <div class="col-xl-3 col-lg-4 col-sm-6 grid-item grid-sizer cat-two">';
       <div class="movie-item movie-item-three mb-50">';
       <div class="movie-poster text-center equal-height">';
       <img src="./assets/img/poster/${movie.HinhAnh}" alt="">
       <ul class="overlay-btn">
       <li class="rating">
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       </li>
       <li><a href=" ${movie.url_film} " class="popup-video btn">XEM TRAILER</a></li>
       <li><a href="./details_movie" class="btn show-details-movie"  data-id="${movie.id}" data-bs-toggle="tooltip">XEM CHI TIẾT</a></li>
       </ul>
       </div>
       <div class="movie-content">
       <div class="top">
       <h5 class="title">
       <a href="./details_movie" data-id="${movie.id}" data-bs-toggle="tooltip">${movie.TenPhim}</a>
       </h5>
       <span class="date"> ${movie.TheLoai} </span>
       </div>
       <div class="bottom">
       <ul>
       <li><span class="quality">hd</span></li>
       <li>
       <span class="duration"><i class="far fa-clock"></i> ${movie.ThoiLuong}</span>
       <span class="rating"><i class="fas fa-thumbs-up"></i>9.3</span>
       </li>
       </ul>
       </div>
       </div>
       </div>
       </div>
         `;
     });
     $("#list-movies").html(html);
    //  $('[data-bs-toggle="tooltip"]').tooltip();
   };
   $(document).on("click", ".show-details-movie", function () {
    let id = $(this).data("id");
    $("#save-id-movie").data("id", id);
    console.log($("#save-id-movie").data("id"));
    $.ajax({
      type: "POST",
      url: "./list_movie/saveId", // Đường dẫn tới tập tin PHP xử lý lưu session
      data: { id: id },
      success: function (response) {
          console.log("Data ID saved to session "+ response);
      }
    });
  });
  $(".filtered-by-TheLoai").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-TheLoai").text($(this).text());
    let TheLoai = $(this).data('id');
    if (TheLoai == "Tất cả") {
      delete mainPagePagination.option.filter.TheLoai;
    } else {
      mainPagePagination.option.filter.TheLoai = TheLoai;
    }
    mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
  });
  $(".filtered-by-NoiSX").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-NoiSX").text($(this).text());
    let NoiSX = $(this).data('id');
    if (NoiSX == "Tất cả") {
      delete mainPagePagination.option.filter.NoiSX;
    } else {
      mainPagePagination.option.filter.NoiSX = NoiSX;
    }
    mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
  });
  $(".filtered-by-NamSX").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-NamSX").text($(this).text());
    let NamSX = +$(this).data('id');
    if (NamSX == 0) {
      delete mainPagePagination.option.filter.NamSX;
    } else {
      mainPagePagination.option.filter.NamSX = NamSX;
    }
    mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
  });
  $(".filtered-by-DoTuoi").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-DoTuoi").text($(this).text());
    let DoTuoi = +$(this).data('id');
    if (DoTuoi == 0) {
      delete mainPagePagination.option.filter.DoTuoi;
    } else {
      mainPagePagination.option.filter.DoTuoi = DoTuoi;
    }
    mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
  });
let title_movie = $("#title_movie").data('id');
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "list_movie";
mainPagePagination.option.model = "FilmModel";
mainPagePagination.option.title = title_movie;
mainPagePagination.option.limit = 4;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);