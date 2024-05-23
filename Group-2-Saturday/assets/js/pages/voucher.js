function formatDate(dateTimeString) {
    // Tạo một đối tượng Date từ chuỗi thời gian
    var dateTime = new Date(dateTimeString);
  
    // Lấy ngày, tháng và năm
    var day = dateTime.getDate();
    var month = dateTime.getMonth() + 1; // Lưu ý: getMonth() trả về chỉ số của tháng (từ 0 đến 11)
    var year = dateTime.getFullYear();
  
    // Tạo chuỗi ngày/tháng/năm mới
    var formattedDate = day + "/" + month + "/" + year;
  
    return formattedDate;
  }
const showData = function (promotions) {
     let html = "";
     promotions.forEach((promo) => {
       html += `<div class="col-lg-4 col-md-6 col-sm-8">
       <div class="pricing-box-item mb-30">
       <div class="pricing-top">
           <img src="./assets/img/promotion/${promo.image == null ? `avatar2.jpg`: promo.image}" alt="">
       </div>
       <div class="pricing-list">
           <ul>
               <li class="quality"><i class="fas fa-check"></i><span>${promo.NamePromotion}</span></li>
               <li><i class="fas fa-check"></i>${promo.Percent}% trên tổng hoá đơn</li>
               <li><i class="fas fa-check"></i>HSD: ${formatDate(promo.End)}</li>
           </ul>
       </div>
   </div>
</div>
         `;
     });
     $("#list-voucher").html(html);
     // $('[data-bs-toggle="tooltip"]').tooltip();
   };
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "promotion";
mainPagePagination.option.model = "PromotionModel";
mainPagePagination.option.limit = 6;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);