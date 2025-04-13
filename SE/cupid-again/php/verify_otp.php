<?php
session_start();
require_once "get_connection.php";
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $enteredOtp = $_POST["otp"] ?? "";
  $sessionOtp = $_SESSION["code"] ?? "";
  $email = $_SESSION["email"] ?? "";

  if (!$email || !$sessionOtp || $enteredOtp !== (string)$sessionOtp) {
    $_SESSION["otp_error"] = "Mã xác minh không chính xác!";
    header("Location: user-otp.php");
    exit();
  }

  $stmt = $conn->prepare("UPDATE users SET code = 0, status='verified' WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->close();

  // Lấy lại thông tin user để lưu session mới
  $stmt = $conn->prepare("SELECT id, unique_id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  // Xoá session cũ và đăng nhập lại
  session_unset();
  session_destroy();
  session_start();
  $_SESSION["user_id"] = $user["id"];
  $_SESSION["unique_id"] = $user["unique_id"];

  header("Location: ../html/create_profile.html");
  exit();
}
