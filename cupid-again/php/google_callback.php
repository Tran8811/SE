<?php

session_start();
require_once '../vendor/autoload.php';

$clientID = '846625336484-7k4abkaere2r3fnthre3q2hkcaeut0fg.apps.googleusercontent.com';  // Thay bằng Client ID của bạn
$clientSecret = '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';
$redirectUri = 'http://localhost:8080/cupid-again/php/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);

  $oauth2 = new Google_Service_Oauth2($client);
  $userInfo = $oauth2->userinfo->get();

  $email = $userInfo->email;
  $name = $userInfo->name;

  // Kiểm tra xem người dùng đã tồn tại chưa
  $conn = new mysqli('localhost', 'root', 'chipchip1703', 'cupid_db');
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 0) {
    // Nếu chưa có, thêm vào database
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, '')");
    $stmt->bind_param("s", $email);
    $stmt->execute();
  }

  $_SESSION['user_email'] = $email;
  $_SESSION['user_name'] = $name;

  header("Location: http://localhost:8080/cupid-again/html/sign_up_2.html");
  exit();

}

?>
