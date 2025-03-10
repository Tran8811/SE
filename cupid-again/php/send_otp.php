<?php

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "database_name");

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

// Kiểm tra email có tồn tại không
$result = $conn->query("SELECT * FROM users WHERE email='$email'");
if ($result->num_rows > 0) {
  $otp = rand(100000, 999999); // Tạo mã OTP 6 chữ số
  $conn->query("UPDATE users SET otp='$otp' WHERE email='$email'");

  // Gửi email OTP
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';
    $mail->Password = 'your_password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'Hỗ trợ');
    $mail->addAddress($email);
    $mail->Subject = 'Mã xác nhận đặt lại mật khẩu';
    $mail->Body = "Mã OTP của bạn là: $otp";

    $mail->send();
    echo json_encode(["status" => "success"]);
  } catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $mail->ErrorInfo]);
  }
} else {
  echo json_encode(["status" => "error"]);
}

?>
