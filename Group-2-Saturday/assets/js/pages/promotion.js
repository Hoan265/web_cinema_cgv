Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker']);
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
Dashmix.onLoad((() => class {
  static initValidation() {
    Dashmix.helpers("jq-validation");
    jQuery.validator.addMethod("endDateAfterStartDate", function (value, element, params) {
        var startDate = jQuery(params).val();
        return Date.parse(startDate) < Date.parse(value);
    }, "Ngày kết thúc phải sau ngày bắt đầu.");
    jQuery.validator.addMethod("anh", function (value, element, params) {
      let file_anh = $("#file_anh").val();
    if(file_anh==""){
      Dashmix.helpers('jq-notify', { type: 'danger', icon: 'fa fa-times me-1', message: `Vui lòng chọn hình ảnh!` });
    } 
    },`Vui lòng chọn hình ảnh!`);
    
    jQuery(".form-add-promotion").validate({
      rules: {
        "makhuyenmai": {
          required: !0,
          digits: true
        },
        
        "tenkhuyenmai": {
          required: !0,
        },
        // "file_anh": {
        //   required: !0,
        //   anh:  !0,          
        // },
        "ngaybd": {
          required: !0,
        },
        "ngaykt": {
          required: !0,
          endDateAfterStartDate: "#ngaybd"
        },
        "dongia": {
          required: !0,
        },
        "giatri": {
          required: !0,          
        },
      },
      messages: {
        "makhuyenmai": {
          required: "Vui lòng nhập mã sinh viên của bạn"
        },
        // "voucher": {
        //   required: "Tích chọn 1 trong 3",
        // },
        "tenkhuyenmai": {
          required: "Cung cấp đầy đủ tên khuyến mãi",
        },
        // "file_anh": {
        //   required: "Vui lòng chọn hình ảnh",
        // },        
        "ngaybd": {
          required: "Vui lòng chọn ngày bắt đầu",
        },
        "ngaykt": {
          required: "Vui lòng chọn ngày kết thúc",
        },
        "dongia": {
          required: "Vui lòng chọn 1 trong 2",
        },
        "giatri": {
          required: "Nhập giá trị",
        },
      },
      
    })
    
  }

  static init() {
    this.initValidation()
  }
}.init()));
// { <td class="fs-sm d-flex align-items-center">
//                               <img class="img-avatar img-avatar48 me-3" src="./assets/img/promotion/${promo.image == null ? `avatar2.jpg`: promo.image}" alt="">
//                               <div class="d-flex flex-column">
//                                   <strong class="text-primary">${
//                                     promo.namepromotion
//                                   }</strong>
//                                   <span class="fw-normal fs-sm text-muted">${
//                                     promo.email
//                                   }</span>
//                               </div>
//                           </td> }

const showData = function (promotions) {
  let html = "";
  promotions.forEach((promo) => {
    html += `<tr>
                          <td class="text-center">
                              <strong>${promo.IDPromotion}</strong>
                          </td>
                          <td class="text-center">
                              <strong>${promo.NamePromotion}</strong>
                          </td>
                          <td class="text-center">
                              <strong><img class="img-avatar img-avatar48 me-3" src="./assets/img/promotion/${promo.image == null ? `avatar2.jpg`: promo.image}" alt=""></strong>
                          </td>
                          <td class="text-center">${formatDate(promo.Start)}</td>
                          <td class="text-center">${formatDate(promo.End)}</td>
                          
                          <td class="text-center">${promo.Percent}</td>
                          <td class="text-center">
                              <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill ${
                                promo.Status == 1
                                  ? "bg-success-light text-success"
                                  : "bg-danger-light text-danger"
                              } bg-success-light text-success">${
                                promo.Status == 1 ? "Hoạt động" : "Khoá"
    }</span>
                          </td> 
                          <td class="text-center col-action">
                              <a  data-action="update" class="btn btn-sm btn-alt-secondary promotion-edit" href="javascript:void(0)"
                              data-bs-toggle="tooltip" aria-label="Chỉnh sửa" data-bs-original-title="Chỉnh sửa" data-id="${promo.IDPromotion}">
                                  <i class="fa fa-fw fa-pencil"></i>
                              </a>
                              <a  data-action="delete" class="btn btn-sm btn-alt-secondary promotion-delete" href="javascript:void(0)" data-bs-toggle="tooltip"
                                  aria-label="Xoá" data-bs-original-title="Xoá" data-id="${
                                    promo.IDPromotion
                                  }">
                                  <i class="fa fa-fw fa-times"></i>
                              </a>
                          </td>
                      </tr>
      `;
  });
  $("#list-promotion").html(html);
  $('[data-bs-toggle="tooltip"]').tooltip();
};
$(document).ready(function () {
  $("#promotion_nhomquyen").select2({
    dropdownParent: $("#modal-add-promotion"),
  });

  $.get(
    "./roles/getAll",
    function (data) {
      let html = `<option></option>`;
      data.forEach((item) => {
        html += `<option value="${item.manhomquyen}">${item.tennhomquyen}</option>`;
      });
      $("#promotion_nhomquyen").html(html);
    },
    "json"
  );

  $("[data-bs-target='#modal-add-promotion']").click(function (e) {
    e.preventDefault();
    clearInputFields();
    $(".add-promotion-element").show();
    $(".update-promotion-element").hide();
    $.ajax({
      type: "post",
      url: "./promotion/getIDMax",
      success: function (response) {
        
          let maKM = parseInt(response) + 1; // Giả sử ID lớn nhất đã được lấy từ controller và gửi về trong response
          $("#makhuyenmai").val(maKM);
          $("#makhuyenmai").prop("disabled",true);
      },
      });
  });

  function checkPromotion(id) {
    let result = true;
    $.ajax({
      type: "post",
      url: "./promotion/checkPromotion",
      data: {
        makhuyenmai: id,
      }, 
      async: false,
      dataType: "json",
      success: function (response) {
        if (response.length !== 0) {
          Dashmix.helpers('jq-notify', { type: 'danger', icon: 'fa fa-times me-1', message: `Khuyến mãi đã tồn tại!` });
          result = false;
        }
      }
    })
    return result;
  }

  function checkPromotionUpdate(id) {
    let result = true;
    $.ajax({
      type: "post",
      url: "./promotion/checkPromotion",
      data: {
        mssv: id,
      }, 
      async: false,
      dataType: "json",
      success: function (response) {
        if (response.length  != 1) {
          Dashmix.helpers('jq-notify', { type: 'danger', icon: 'fa fa-times me-1', message: `Khuyến mãi đã tồn tại!` });
          result = false;
        }
      }
    })
    return result;
  }

  
  $("#btn-add-promotion").on("click", function (e) {
    e.preventDefault();
    let maKM = $("#makhuyenmai").val();
    // Validate promotion
    if ($(".form-add-promotion").valid() && checkPromotion(maKM)) {
      $.ajax({
        type: "post",
        url: "./promotion/add",
        data: {
          id: maKM,
          tenkhuyenmai: $("#tenkhuyenmai").val(),
          file_anh: $('#file_anh').val(),
          ngaybd: $("#ngaybd").val(),
          ngaykt: $("#ngaykt").val(),
          giatri: $("#giatri").val(),
          status: $("#status").prop("checked") ? 1 : 0,
        },
        success: function (response) {
          console.log(response.valid)
          Dashmix.helpers('jq-notify', { type: 'success', icon: 'fa fa-check me-1', message: `Thêm khuyến mãi thành công!` });
          $("#modal-add-promotion").modal("hide");
          mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
        },
      });
    } else {
      Dashmix.helpers('jq-notify', { type: 'danger', icon: 'fa fa-times me-1', message: `Thêm khuyến mãi không thành công!` });
    }
  });

  $(document).on("click", ".promotion-edit", function () {
    let id = $(this).data("id");
    $(".add-promotion-element").hide();
    $(".update-promotion-element").show();
    $("#btn-update-promotion").data("id", id);
    $.ajax({
      type: "post",
      url: "./promotion/getDetail",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        $("#makhuyenmai").val(response.IDPromotion)
        $("#makhuyenmai").prop("disabled",true);
        $("#tenkhuyenmai").val(response.NamePromotion),
        // $("#file_anh").prop("value",response.image),
        // $("#file_anh").val(response.image),
          $("#file_anh_fake").text(response.image); // Hiển thị đường dẫn tệp trong trình giả lập
          $("#ngaybd").val(response.Start);
          $("#ngaykt").val(response.End);
          $("#giatri").val(response.Percent).trigger("change");
          $("#status").prop("checked", response.Status == 1);
          $("#modal-add-promotion").modal("show");
        },
      });
  });
  
  $("#btn-update-promotion").click(function (e) {
    e.preventDefault();
    let maKM = $("#makhuyenmai").val();
    if(checkPromotionUpdate(maKM)) {
      $.ajax({
        type: "post",
        url: "./promotion/update",
        data: {
          id: $(this).data("id"),
          tenkhuyenmai: $("#tenkhuyenmai").val(),
          file_anh: $('#file_anh').val(),
          ngaybd: $("#ngaybd").val(),
          ngaykt: $("#ngaykt").val(),
          giatri: $("#giatri").val(),
          status: $("#status").prop("checked") ? 1 : 0,
        },
        success: function (response) {
          console.log(response)
          mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
          $("#modal-add-promotion").modal("hide");
        },
      });
    }
  });

  $(document).on("click", ".promotion-delete", function () {
    var trid = $(this).data("id");
    let e = Swal.mixin({
      buttonsStyling: !1,
      target: "#page-container",
      customClass: {
        confirmButton: "btn btn-success m-1",
        cancelButton: "btn btn-danger m-1",
        input: "form-control",
      },
    });
    e.fire({
      title: "Are you sure?",
      text: "Bạn có chắc chắn muốn xoá khuyến mãi này?",
      icon: "warning",
      showCancelButton: !0,
      customClass: {
        confirmButton: "btn btn-danger m-1",
        cancelButton: "btn btn-secondary m-1",
      },
      confirmButtonText: "Vâng, tôi chắc chắn!",
      html: !1,
      preConfirm: (e) =>
        new Promise((e) => {
          setTimeout(() => {
            e();
          }, 50);
        }),
    }).then((t) => {
      if (t.value == true) {
        $.ajax({
          type: "post",
          url: "./promotion/deleteData",
          data: {
            id: trid,
          },
          success: function (response) {
            e.fire("Deleted!", "Xóa khuyến mãi thành công!", "success");
            mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
          },
        });
      } else {
        e.fire("Cancelled", "Bạn đã không xóa khuyến mãi :)", "error");
      }
    });
  });

  $("#nhap-file").click(function (e) {
    e.preventDefault();
    let file_anh = $("#file_anh").val();
    if(file_anh==""){
      Dashmix.helpers('jq-notify', { type: 'danger', icon: 'fa fa-times me-1', message: `Vui lòng điền đầy đủ thông tin!` });
    } else {
      var file = $("#file-cau-hoi")[0].files[0];
      var formData = new FormData();
      formData.append("fileToUpload", file);
      $.ajax({
        type: "post",
        url: "./promotion/addExcel",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function () {
          Dashmix.layout("header_loader_on");
        },
        success: function (response) {
          console.log(response)
          addExcel(response,password);
        },
        complete: function () {
          Dashmix.layout("header_loader_off");
        },
      });
    }
  });

  function addExcel(data) {
    $.ajax({
      type: "post",
      url: "./promotion/addFileExcel",
      data: {
        listpromotion: data
      },
      success: function (response) {
        mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
        $("#ps_promotion_group").val("");
        $("#file_anh").val("");
        $("#modal-add-promotion").modal("hide");
        Dashmix.helpers('jq-notify', { type: 'success', icon: 'fa fa-times me-1', message: `Thêm khuyến mãi không thành công!` });
      },
    });
  }

  $("#btn-and").click(function(){
    $("#btabs-static-home-tab").tab("show")
  })

  function clearInputFields() {
    $("#makhuyenmai").val("");
    $("#makhuyenmai").prop("disabled", false);
    $("#tenkhuyenmai").val("");
    $("#file_anh").prop("value",""),
    $("#file_anh_fake").text("");
    $("#ngaybd").val("");
    $("#ngaykt").val("");
    $(`input[name="dongia"]`).prop("checked", false);
    $("#giatri").val("");
    $("#status").prop("checked", 1);
  }

  $(".filtered-by-role").click(function (e) {
    e.preventDefault();
    $(".btn-filtered-by-role").text($(this).text());
    let roleID = +$(this).data('id');
    if (roleID === 0) {
      delete mainPagePagination.option.filter.role;
    } else {
      mainPagePagination.option.filter.role = roleID;
    }
    mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
  });

});

// Pagination
const mainPagePagination = new Pagination();
mainPagePagination.option.controller = "promotion";
mainPagePagination.option.model = "PromotionModel";
mainPagePagination.option.limit = 10;
mainPagePagination.option.filter = {};
mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);
