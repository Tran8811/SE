<?php

$conn = new mysqli("localhost", "root", "", "database_name");

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$otp = $data->otp;

$result = $conn->query("SELECT * FROM users WHERE email='$email' AND otp='$otp'");
if ($result->num_rows > 0) {
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "error"]);
}

?>
