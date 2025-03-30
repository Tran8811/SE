<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "chipchip1703";
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

session_start();
header('Content-Type: application/json');

// Debug xem session có tồn tại không
if (!isset($_SESSION["user_id"])) {
  echo json_encode([
    "status" => "error",
    "message" => "Bạn chưa đăng nhập!",
    "session" => $_SESSION // Debug session
  ]);
  exit();
}


$user_id = $_SESSION["user_id"];
$sql = "SELECT username, age, gender, interests, avatar_url, mbti FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
  echo json_encode(["status" => "success", "users" => $user]);
} else {
  echo json_encode(["status" => "error", "message" => "Không tìm thấy người dùng!"]);
}

$stmt->close();
$conn->close();
?>
