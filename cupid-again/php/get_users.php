<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "chipchip1703";
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_id"])) {
  echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
  exit();
}

$current_user_id = $_SESSION["user_id"];
$sql = "SELECT id, username FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
  $users[] = $row;
}

echo json_encode(["status" => "success", "users" => $users]);
$stmt->close();
$conn->close();
?>
