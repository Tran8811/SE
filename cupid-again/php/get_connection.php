<?php
$servername = "localhost";
$username = "root";  // Thay bằng user MySQL của bạn
$password = "0730AyUu";  // Thay bằng password của bạn
$dbname = "cupid_db";  // Tên database

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

