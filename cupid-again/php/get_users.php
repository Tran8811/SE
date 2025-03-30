<?php
session_start();
require_once 'get_connection.php'; // Import config.php để lấy biến $conn

// Kiểm tra kết nối database
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION["user_id"])) {
  echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
  exit();
}

$current_user_id = $_SESSION["user_id"];

// Lấy danh sách người dùng (trừ chính người đang đăng nhập)
$sql = "SELECT id, username FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  die(json_encode(["status" => "error", "message" => "Lỗi chuẩn bị truy vấn: " . $conn->error]));
}

$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
  $users[] = $row;
}

echo json_encode(["status" => "success", "users" => $users]);

$stmt->close();
$conn->close();
