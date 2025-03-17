<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Load PHPMailer

$mysqli = new mysqli("localhost", "root", "chipchip1703", "cupid_db");

// Lấy email từ form (giả sử người dùng nhập vào)
$email = $_POST["email"];

// Kiểm tra email có tồn tại trong CSDL không
$sql = "SELECT email FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Email không tồn tại!";
  exit;
}

// Nếu email tồn tại, tạo token đặt lại mật khẩu
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Lưu token vào CSDL
$sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

// Nếu cập nhật thành công, gửi email
if ($stmt->affected_rows) {
  require '../php/mailer.php';  // Load thư viện PHPMailer

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tranpp1703@gmail.com';
    $mail->Password = 'clsp tsvx ikwt pjbi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('tranpp1703@gmail.com', 'Your Website');
    $mail->addAddress($email);  // Email lấy từ CSDL

    $mail->Subject = "Password Reset";
    $mail->Body = "Click <a href='http://localhost:8080/cupid-again/php/reset-password.php?token=$token'>here</a> to reset your password.";
    $mail->isHTML(true);
    $mail->AltBody = "Copy and paste this link into your browser: http://localhost:8080/cupid-again/php/reset-password.php?token=$token=$token";

    $mail->send();
    echo "Email đặt lại mật khẩu đã được gửi!";
  } catch (Exception $e) {
    echo "Không thể gửi email: {$mail->ErrorInfo}";
  }
} else {
  echo "Lỗi khi cập nhật token.";
}
