<?php
session_start();
$email = $_SESSION["email"] ?? null;

if (!$email) {
  header("Location: ../html/sign_up.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xác minh OTP</title>
  <link rel="stylesheet" href="../css/otp.css">
</head>
<body>
<div class="otp-container">
  <h2>Xác minh Email</h2>
  <p>Mã xác thực đã được gửi đến: <strong><?= htmlspecialchars($email) ?></strong></p>
  <form method="POST" action="verify_otp.php">
    <input type="text" name="otp" placeholder="Nhập mã OTP" required>
    <button type="submit">Xác nhận</button>
  </form>
  <?php if (isset($_SESSION["otp_error"])): ?>
    <p class="error"><?= $_SESSION["otp_error"] ?></p>
    <?php unset($_SESSION["otp_error"]); ?>
  <?php endif; ?>
</div>
</body>
</html>
