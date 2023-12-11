// Clock
function updateClock() {
    var now = new Date();
    var dayOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
    var day = now.getDate();
    var month = now.getMonth() + 1; // Tháng bắt đầu từ 0, nên cộng 1 để hiển thị đúng
    var year = now.getFullYear();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
  
    var timeString = dayOfWeek[now.getDay()] + ', ' +
                     day.toString().padStart(2, '0') + '/' +
                     month.toString().padStart(2, '0') + '/' +
                     year.toString() + ' - ' +
                     hours.toString().padStart(2, '0') + ' giờ ' +
                     minutes.toString().padStart(2, '0') + ' phút ' +
                     seconds.toString().padStart(2, '0') + ' giây';
  
    document.getElementById('clock').textContent = timeString;
}
setInterval(updateClock, 1000);