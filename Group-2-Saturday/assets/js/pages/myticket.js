function formatDate(chuoiDatetime) {
     // Sử dụng phương thức split để tách chuỗi dựa trên khoảng trắng
     const parts = chuoiDatetime.split('T');
     
     // Lấy phần ngày và phần giờ từ mảng parts
     const ngay = parts[0];
     const gio = parts[1];
 
     // Trả về kết quả
     return {
         ngay: ngay,
         gio: gio
     };
}

const showData = function (bills) {
     let html = "";
     bills.forEach((item) => {
       html += `<tr st>
                             <td class="text-center">
                                 <strong>${item.IDBill}</strong>
                             </td>
                             <td class="text-center">
                                 <strong>${item.TenPhim}</strong>
                             </td>
                             <td class="text-center">
                                 <strong>${item.TenPhong}</strong>
                             </td>
                             <td class="text-center">${item.CustomSeats}</td>
                             <td class="text-center">${formatDate(item.ThoiGianChieu).ngay}</td>
                             
                             <td class="text-center">${formatDate(item.ThoiGianChieu).gio}</td>
                             <td class="text-center">${item.CustomSeats}</td>                             
                             <td class="text-center">${item.TotalPrice}</td>


                             <td class="text-center">
                                 <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill ${
                                   item.Status != 'Chưa xử lý'
                                     ? "bg-success-light text-success"
                                     : "bg-danger-light text-danger"
                                 } bg-success-light text-success">${
                                   item.Status != 'Chưa xử lý' ? "Hoàn thành" : "Chưa xử lý"
       }</span>
                             </td>
                             
                         </tr>
         `;
     });
     $("#list-myticket").html(html);
     $('[data-bs-toggle="tooltip"]').tooltip();
   };


const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "Bill";
mainPagePagination.option.model = "BillModel";
mainPagePagination.option.limit = 10;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
