<?php
/** @noinspection ALL */
session_start();
require_once "get_connection.php"; // Import kết nối đến database

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION["user_id"])) {
  echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
  exit();
}

$user_id = $_SESSION["user_id"];
$username = $_POST["username"] ?? "";
$age = isset($_POST["age"]) ? (int)$_POST["age"] : 0;
$gender = $_POST["gender"] ?? "";
$interests = $_POST["interests"] ?? "";

// Kiểm tra dữ liệu hợp lệ
if (empty($username) || empty($gender) || empty($interests)) {
  echo json_encode(["status" => "error", "message" => "Vui lòng điền đầy đủ thông tin!"]);
  exit();
}
if ($age < 15 || $age > 100) {
  echo json_encode(["status" => "error", "message" => "Tuổi phải từ 15 đến 100!"]);
  exit();
}

// Kiểm tra user có hồ sơ chưa
$sql = "SELECT id, avatar_url FROM users WHERE id = ?";
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}
$stmt = $conn->prepare($sql);
if (!$stmt) {
  die(json_encode(["status" => "error", "message" => "Lỗi SQL: " . $conn->error]));
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_exists = ($user !== null);
$avatar_url = $user["avatar_url"] ?? "../img/logo.png";

// Xử lý upload avatar từ dữ liệu Base64
if (!empty($_POST["avatar"])) {
  $avatar_data = $_POST["avatar"]; // Dữ liệu Base64 từ Cropper.js
  $avatar_data = str_replace('data:image/png;base64,', '', $avatar_data);
  $avatar_data = str_replace(' ', '+', $avatar_data);
  $avatar_decoded = base64_decode($avatar_data);

  // Lưu ảnh vào thư mục user_avatars/
  $avatar_filename = "avatar_" . $user_id . "_" . time() . ".png";
  $avatar_path = "../user_avatars/" . $avatar_filename;

  if (file_put_contents($avatar_path, $avatar_decoded)) {
    $avatar_url = $avatar_path;
  } else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi lưu avatar!"]);
    exit();
  }
}

// Cập nhật hoặc tạo mới hồ sơ
if ($user_exists) {
  $sql = "UPDATE users SET username = ?, age = ?, gender = ?, interests = ?, avatar_url = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    die(json_encode(["status" => "error", "message" => "Lỗi SQL: " . $conn->error]));
  }
  $stmt->bind_param("sisssi", $username, $age, $gender, $interests, $avatar_url, $user_id);
  $action = "Cập nhật hồ sơ";
} else {
  $sql = "INSERT INTO users (id, username, age, gender, interests, avatar_url) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    die(json_encode(["status" => "error", "message" => "Lỗi SQL: " . $conn->error]));
  }
  $stmt->bind_param("isisss", $user_id, $username, $age, $gender, $interests, $avatar_url);
  $action = "Tạo hồ sơ mới";
}

if ($stmt->execute()) {
  echo json_encode(["status" => "success", "message" => "$action thành công!", "avatar_url" => $avatar_url]);
} else {
  echo json_encode(["status" => "error", "message" => "Lỗi khi $action!"]);
}

$stmt->close();
$conn->close();
