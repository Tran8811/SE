<?php
session_start();
require_once 'get_connection.php'; // Import file config.php để lấy biến $conn

// Kiểm tra kết nối database
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION["username"])) {
  echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập!"]);
  exit();
}

// Kiểm tra yêu cầu POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mbti"])) {
  $mbti = trim($_POST["mbti"]);
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
  } else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi chuẩn bị truy vấn!"]);
  }
} else {
  echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ!"]);
}

$conn->close();
?>
