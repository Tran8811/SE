
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';

function sendVerificationEmail($toEmail, $subject, $body){
  $mail = new PHPMailer(true);
  try {
    $mail->SMTPDebug = 0; // ✅ Tắt in debug
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tranpp1703@gmail.com';
    $mail->Password = 'clsp tsvx ikwt pjbi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('tranpp1703@gmail.com', 'Cupid');
    $mail->addAddress($toEmail);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    return $mail->send();
  } catch (Exception $e) {
    // ✅ Không echo gì ra ngoài
    return false;
  }
}
?>
