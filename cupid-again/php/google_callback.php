<?php

session_start();
require_once '../vendor/autoload.php'; // Cần thư viện Google API Client
require_once 'get_connection.php'; // Thêm kết nối cơ sở dữ liệu nếu cần
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}
$clientID = '846625336484-7k4abkaere2r3fnthre3q2hkcaeut0fg.apps.googleusercontent.com';  // Thay bằng Client ID của bạn
$clientSecret = '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';
$redirectUri = 'http://localhost:8080/cupid-again/php/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);

  // Lưu token vào session để giữ đăng nhập
  $_SESSION['access_token'] = $token;

  $oauth2 = new Google_Service_Oauth2($client);
  $userInfo = $oauth2->userinfo->get();

  $email = $userInfo->email;
  $name = $userInfo->name;

  // Kiểm tra xem người dùng đã tồn tại chưa trong cơ sở dữ liệu
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 0) {
    // Nếu chưa có, thêm vào database
    $password = '';  // Mật khẩu rỗng ban đầu
    $unique_id = rand(9999999,99999999);  // Tạo mã duy nhất
    $stmt = $conn->prepare("INSERT INTO users (username, password, unique_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $password, $unique_id);
    $stmt->execute();
  }

  // Lưu thông tin người dùng vào session
  $_SESSION['user_email'] = $email;
  $_SESSION['user_name'] = $name;

  // Chuyển hướng đến trang profile
  header("Location: http://localhost:8080/cupid-again/html/sign_up_2.html");
  exit();
} else {
  // Nếu không có mã code trong URL, chuyển hướng về trang đăng nhập
  header("Location: http://localhost:8080/cupid-again/html/sign_up_2.html");
  exit();
}

