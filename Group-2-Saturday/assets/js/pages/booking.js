
var comboData = new Array();
var seatSelected =new Array();

$(document).ready(function() {
    // Add change event listener to id_room select
    $("#id_room").change(function() {
        // Get the selected room name
        var selectedRoomName = $("#id_room option:selected").text();
        // Set the value of the "ROOM" input field to the selected room name
        $("input[name='room']").val(selectedRoomName);

    });
});
// const showData = function (foods) {
//     let html = "";
//     foods.forEach((food) => {
//         html += `
//         <tr>
//         <td>${food.Name}</td>
//         <td>        
//         <img src="./assets/img/Foods/${food.img}" height='80px'>
//         </td>
//         <td>${food.Price}</td>
//         <td>${food.Ten}</td>
//         <td class='edit-delete-btn'>
//          <button>-</button>
//         <input type="text" name="quantity" value="0" min="0" max="1000">
//          <button>+</button>
//         </td>
//         </tr>
//         `;
//     });
//     $("#list-foods").html(html);
//   };
// // Pagination
// const mainPagePagination = new Pagination();
// mainPagePagination.option.controller = "booking";
// mainPagePagination.option.model = "FoodsModel";
// mainPagePagination.option.limit = 5;
// mainPagePagination.option.filter = {};
// mainPagePagination.getPagination(mainPagePagination.option, mainPagePagination.valuePage.curPage);