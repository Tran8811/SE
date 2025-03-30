<?php
session_start();
$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703";  // Mật khẩu MySQL
$dbname = "cupid_db";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => "Kết nối database thất bại!"]));
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION["username"])) {
  echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập!"]);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $mbti = $_POST["mbti"];
  $username = $_SESSION["username"];

  // Cập nhật MBTI vào database
  $sql = "UPDATE users SET mbti = ? WHERE username = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $mbti, $username);
    if ($stmt->execute()) {
      echo json_encode(["status" => "success", "message" => "Lưu MBTI thành công!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Lưu MBTI thất bại!"]);
    }
    $stmt->close();
  }
}

$conn->close();
?>

