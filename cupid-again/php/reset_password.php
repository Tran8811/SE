<?php

$conn = new mysqli("localhost", "root", "chipchip1703", "cupd_db");

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$newPassword = password_hash($data->newPassword, PASSWORD_BCRYPT);

$conn->query("UPDATE users SET password='$newPassword', otp=NULL WHERE email='$email'");
echo json_encode(["status" => "success"]);

?>
