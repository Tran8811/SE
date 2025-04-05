<?php /** @noinspection ALL */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Import file config.php để lấy kết nối database
require_once 'get_connection.php';

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

// Kiểm tra nếu có email được gửi từ form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
  $email = trim($_POST["email"]);

  // Kiểm tra email có tồn tại trong CSDL không
  $sql = "SELECT email FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Email không tồn tại!"]);
    exit;
  }

  // Nếu email tồn tại, tạo token đặt lại mật khẩu
  $token = bin2hex(random_bytes(16));
  $token_hash = hash("sha256", $token);
  $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

  // Lưu token vào CSDL
  $sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $token_hash, $expiry, $email);
  $stmt->execute();

  if ($stmt->affected_rows) {
    // Load thư viện PHPMailer
    require '../php/mailer.php';

    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'tranpp1703@gmail.com';
      $mail->Password = 'clsp tsvx ikwt pjbi';  // CẢNH BÁO: Không nên lưu mật khẩu email trực tiếp trong code
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom('tranpp1703@gmail.com', 'Your Website');
      $mail->addAddress($email);  // Email lấy từ CSDL

      $mail->Subject = "Password Reset";
      $mail->isHTML(true);
      $mail->Body = "Click <a href='http://localhost:8080/cupid-again/php/reset-password.php?token=$token'>here</a> to reset your password.";
      $mail->AltBody = "Copy and paste this link into your browser: http://localhost:8080/cupid-again/php/reset-password.php?token=$token";

      $mail->send();
      echo json_encode(["status" => "success", "message" => "Email đặt lại mật khẩu đã được gửi!"]);
    } catch (Exception $e) {
      echo json_encode(["status" => "error", "message" => "Không thể gửi email: {$mail->ErrorInfo}"]);
    }
  } else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật token."]);
  }
} else {
  echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ!"]);
}

?>
