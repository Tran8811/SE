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
$imagePath = "";

if (isset($_FILES["image"])) {
  $imageName = time() . "_" . basename($_FILES["image"]["name"]); // Đổi tên file tránh trùng
  $targetDir = "uploads/";
  $targetFile = $targetDir . $imageName;

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    $imagePath = $targetFile;
  } else {
    die(json_encode(["error" => "Lỗi khi tải ảnh lên!"]));
  }
}

if (!$incoming_id || (!$message && !$imagePath)) {
  die(json_encode(["error" => "Thiếu dữ liệu gửi tin nhắn!"]));
}

$sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, image)
        VALUES ('$incoming_id', '$outgoing_id', '$message', '$imagePath')";

if (mysqli_query($conn, $sql)) {
  echo json_encode(["success" => "Tin nhắn đã gửi!", "image" => $imagePath]);
} else {
  echo json_encode(["error" => "Lỗi khi gửi tin nhắn: " . mysqli_error($conn)]);
}

?>
