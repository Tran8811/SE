<?php
session_start(); // LẤY IDD
header("Content-Type: application/json");

// Check nếu user chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "Chưa đăng nhập"]);
  exit;
}

$userId = $_SESSION['user_id'];

// Gọi API backend để lấy filter của user hiện tại
$backendUrl = "http://localhost:8081/users/$userId";
/////EEEEEEEEEEEEEEEEEEE
//$backendUrl = "http://localhost:8081/users/1";
$response = file_get_contents($backendUrl);
if ($response === false) {
  echo json_encode(["error" => "Lỗi khi gọi API backend"]);
  exit;
}

$userData = json_decode($response, true);

// Trả về luôn preferredMinAge, preferredMaxAge, location
echo json_encode([
  "userId" => $userId,
  "preferredMinAge" => $userData['preferredMinAge'] ?? null,
  "preferredMaxAge" => $userData['preferredMaxAge'] ?? null,
  "preferredLocation" => $userData['preferredLocation'] ?? null
]);
