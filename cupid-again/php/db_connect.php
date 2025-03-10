<?php

$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703";      // Mật khẩu MySQL (nếu có)
$dbname = "cupid_db";  // Tên database của bạn

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}
?>
