<?php
session_start();
require_once 'session.php';
$servername = "localhost";
$username = "root";
$password = "chipchip1703";
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die(json_encode(["error" => "Lỗi kết nối Database!"]));
}

if (!isset($_SESSION['unique_id'])) {
  die(json_encode(["error" => "Bạn chưa đăng nhập!"]));
}

$outgoing_id = $_SESSION['unique_id'];
$incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : null;
$message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : null;

if (!$incoming_id || !$message) {
  die(json_encode(["error" => "Thiếu dữ liệu gửi tin nhắn!"]));
}

$sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
        VALUES ('$incoming_id', '$outgoing_id', '$message')";

if (mysqli_query($conn, $sql)) {
  echo json_encode(["success" => "Tin nhắn đã gửi!"]);
} else {
  echo json_encode(["error" => "Lỗi khi gửi tin nhắn: " . mysqli_error($conn)]);
}
?>
