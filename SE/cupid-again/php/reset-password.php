<?php
require_once 'get_connection.php'; // Import config.php để lấy biến $conn

// Kiểm tra kết nối database
if (!isset($conn)) {
  die("Lỗi kết nối database!");
}

// Kiểm tra token từ URL
if (!isset($_GET["token"])) {
  die("Token không hợp lệ!");
}

$token = $_GET["token"];
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

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <title>Đặt lại mật khẩu</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

<h1>Đặt lại mật khẩu</h1>

<form method="post" action="process-reset-password.php">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

  <label for="password">Mật khẩu mới</label>
  <input type="password" id="password" name="password" required>

  <label for="password_confirmation">Nhập lại mật khẩu</label>
  <input type="password" id="password_confirmation" name="password_confirmation" required>

  <button>Gửi</button>
</form>

</body>
</html>
