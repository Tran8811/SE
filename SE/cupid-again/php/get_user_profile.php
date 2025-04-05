<?php
session_start();
require_once "get_connection.php"; // Kết nối database
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION["user_id"])) {
  echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
  exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT username, age, gender, interests, avatar_url, mbti FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
  echo json_encode(["status" => "success", "user" => $user]);
} else {
  echo json_encode(["status" => "error", "message" => "Không tìm thấy người dùng!"]);
}

$stmt->close();
$conn->close();
