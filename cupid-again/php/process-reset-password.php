<?php
require_once 'get_connection.php'; // Import config.php để lấy biến $conn

// Kiểm tra kết nối database
if (!isset($conn)) {
  die("Lỗi kết nối database!");
}

// Kiểm tra dữ liệu từ form
if (!isset($_POST["token"], $_POST["password"], $_POST["password_confirmation"])) {
  die("Dữ liệu không hợp lệ!");
}

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

// Tìm user có token tương ứng
$sql = "SELECT * FROM users WHERE reset_token_hash = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}

$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kiểm tra token hợp lệ
if (!$user) {
  die("Token không tồn tại!");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
  die("Token đã hết hạn!");
}

// Kiểm tra mật khẩu
$password = $_POST["password"];
$password_confirmation = $_POST["password_confirmation"];

if (!preg_match("/[a-z]/i", $password)) {
  die("Mật khẩu phải chứa ít nhất một chữ cái!");
}

if ($password !== $password_confirmation) {
  die("Mật khẩu nhập lại không khớp!");
}

// Mã hóa mật khẩu trước khi lưu
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Cập nhật mật khẩu và xóa token
$sql = "UPDATE users
        SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL
        WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}

$stmt->bind_param("si", $password_hash, $user["id"]);
$stmt->execute();

echo "Mật khẩu đã được cập nhật. Bạn có thể đăng nhập ngay bây giờ!";

