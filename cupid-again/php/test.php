<?php
// Cấu hình kết nối
$servername = "localhost"; // Thay thế bằng tên máy chủ hoặc địa chỉ IP của bạn
$username = "root";    // Thay thế bằng tên người dùng của bạn
$password = "Thu ha123";    // Thay thế bằng mật khẩu của bạn
$dbname = "cupid_db"; // Thay thế bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
} else {
  echo "Kết nối thành công!";
}

// Đóng kết nối
$conn->close();
?>
