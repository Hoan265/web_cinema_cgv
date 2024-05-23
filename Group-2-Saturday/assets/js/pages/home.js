Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker']);
const showData = function (movies) {
     let html = "";
     movies.forEach((movie) => {
       html += `
          <div class="custom-col">
          <div class="movie-item movie-item-two mb-50">
          <div class="movie-poster text-center equal-height">
          <img src="./assets/img/poster/${movie.HinhAnh}" alt="" style="">
          <ul class="overlay-btn">
          <li>
          <a href=" ${movie.url_film} " class="popup-video btn">XEM TRAILER</a>
          </li>
          <li>
          <li><a href="./details_movie" class="btn show-details-movie"  data-id="${movie.id}" data-bs-toggle="tooltip">XEM CHI TIẾT</a></li>
          </li>
          </ul>
          </div>
          <div class="movie-content">
          <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          </div>
          <h5 class="title">
          <a href="./details_movie" data-id="${movie.id}" data-bs-toggle="tooltip">${movie.TenPhim}</a>
          </h5>
          <span class="rel"> ${movie.TheLoai} </span>
          <div class="movie-content-bottom">
          <ul>
          <li class="tag">
          <a href="#">HD</a>
          <a href="#">${movie.NoiSX}</a>
          </li>
          <li>
          <span class="like"><i class="fas fa-thumbs-up"></i>9.3</span>
          </li>
          </ul>
          </div>
          </div>
          </div>
          </div>
         `;
     });
     $("#list-movie").html(html);
     $('[data-bs-toggle="tooltip"]').tooltip();
   };

   $(document).on("click", ".show-details-movie", function () {
    let id = $(this).data("id");
    $("#save-id-movie").data("id", id);
    console.log($("#save-id-movie").data("id"));
    $.ajax({
      type: "POST",
      url: "./home/saveId", // Đường dẫn tới tập tin PHP xử lý lưu session
      data: { id: id },
      success: function (response) {
          console.log("Data ID saved to session "+ response);
      }
  });
  });

const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "home";
mainPagePagination.option.model = "FilmModel";
mainPagePagination.option.limit = 9;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);