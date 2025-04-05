<?php
// send-messages.php
session_start();
header("Content-Type: application/json");
require_once 'get_connection.php';

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION['unique_id'])) {
  echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
  exit();
}

$outgoing_id = $_SESSION['unique_id'];
$incoming_id = $_POST['incoming_id'] ?? null;
$message = $_POST['message'] ?? '';
$imagePath = "";

if (!$incoming_id || (!$message && empty($_FILES["image"]))) {
  echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu gửi tin nhắn!"]);
  exit();
}

// Xử lý ảnh nếu có
if (!empty($_FILES["image"]["name"])) {
  $imageName = time() . "_" . basename($_FILES["image"]["name"]);
  $targetDir = "../uploads/";
  $targetFile = $targetDir . $imageName;

  if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    echo json_encode(["status" => "error", "message" => "Lỗi khi tải ảnh lên!"]);
    exit();
  }

  $imagePath = htmlspecialchars($targetFile);  // Sử dụng htmlspecialchars để bảo mật
}

// Chuẩn bị câu lệnh SQL
$stmt = $conn->prepare("INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $incoming_id, $outgoing_id, $message, $imagePath);

if ($stmt->execute()) {
  echo json_encode([
    "status" => "success",
    "message" => "Tin nhắn đã gửi!",
    "data" => [
      "message" => htmlspecialchars($message),  // Sử dụng htmlspecialchars cho tin nhắn
      "image" => $imagePath
    ]
  ]);
} else {
  echo json_encode(["status" => "error", "message" => "Lỗi khi gửi tin nhắn: " . $stmt->error]);
}

$stmt->close();
$conn->close();
