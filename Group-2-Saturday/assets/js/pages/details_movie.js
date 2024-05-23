let movieChooser;
$.ajax({
  type: "GET",
  url: "details_movie/getId", // Đường dẫn tới tập tin PHP xử lý lấy giá trị từ session
  success: function (response) {
      // Nếu có giá trị trong session, sử dụng nó
      if(response) {
          let id = response;
          console.log("Data ID retrieved from session: " + id);
          $.ajax({
            type: "post",
            url: "./details_movie/getDetail",
            data: {
              idDetail: id,
            },
            dataType: "json",
            success: function (response) {
              movieChooser = response;
              console.log(response);
              $("#HinhAnh").attr("src","./assets/img/poster/"+ response.HinhAnh);
              $("#trailer").attr("href", response.url_film);
              $("#TenPhim").text(response.TenPhim);
              $("#DoTuoi").text(response.DoTuoi);
              $(".TheLoai").text(response.TheLoai);
              $("#DaoDien").text(response.DaoDien);
              $("#DV").text(response.DV);
              $("#NamSX").text(response.NamSX);
              $("#ThoiLuong").text(response.ThoiLuong);
              $(".NoiDung").text(response.NoiDung);
              },
            });
          // Sau khi sử dụng, bạn có thể xóa nó khỏi session nếu cần thiết
      } else {
          console.log("No data ID found in session");
      }
  }
});
const showData = function (movies) {
  let html = "";
  movies.forEach((movie) => {
      html += `
            <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="movie-item movie-item-two mb-50" style="height: 550px">
            <div class="movie-poster text-center equal-height">
            <a href=""><img src="./assets/img/poster/${movie.HinhAnh}" alt=""></a>
            </div>
            <div class="movie-content">
            <div class="top">
            <h5 class="title"><a href="">${movie.TenPhim}</a></h5>
            <span class="date">${movie.NamSX}</span>
            </div>
            <div class="bottom">
            <ul>
            <li><span class="quality">hd</span></li>
            <li>
            <span class="duration"><i class="far fa-clock"></i>${movie.ThoiLuong}</span>
            <span class="rating"><i class="fas fa-thumbs-up"></i>Review</span>
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
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "details_movie";
mainPagePagination.option.model = "FilmModel";
mainPagePagination.option.limit = 4;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
