// // Tạo mảng chứa các tháng
// const months = Object.keys(monthlyTotalPrice).sort((a, b) => parseInt(a) - parseInt(b));

// // Tạo mảng chứa các giá trị tương ứng với từng tháng
// const totalPriceValues = months.map(month => monthlyTotalPrice[month]);

// // Vẽ biểu đồ cột
// const ctx = document.getElementById('myChart').getContext('2d');
// const myChart = new Chart(ctx, {
//     type: 'bar',
//     data: {
//         labels: months,
//         datasets: [{
//             label: 'Doanh thu theo tháng',
//             data: totalPriceValues,
//             backgroundColor: 'rgba(54, 162, 235, 0.2)',
//             borderColor: 'rgba(54, 162, 235, 1)',
//             borderWidth: 1
//         }]
//     },
//     options: {
//         scales: {
//             y: {
//                 beginAtZero: true,
//                 ticks: {
//                     callback: function(value, index, values) {
//                         return value.toLocaleString() + ' vnđ'; // Thêm đơn vị tiền tệ vào nhãn
//                     }
//                 }
//             }
//         }
//     }
// });
