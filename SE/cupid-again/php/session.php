<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo json_encode(["status" => "error", "message" => "Chỉ chấp nhận POST request"]);
  exit();
}

require_once "get_connection.php";
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION["unique_id"])) {
  echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? '';
$sender_id = $_SESSION["unique_id"];
$receiver_id = $data['receiver_id'] ?? null;
$message = $data['message'] ?? '';

switch ($type) {
  case "chat_request":
    handleChatRequest($conn, $sender_id, $receiver_id);
    break;
  case "message":
    handleMessage($conn, $sender_id, $receiver_id, $message);
    break;
  case "accept_chat":
    handleAcceptChat($conn, $sender_id, $receiver_id);
    break;
  default:
    echo json_encode(["status" => "error", "message" => "Loại yêu cầu không hợp lệ"]);
    break;
}

$conn->close();

function handleChatRequest($conn, $sender_id, $receiver_id): void
{
  $check = $conn->prepare("SELECT 1 FROM chat_requests WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'");
  $check->bind_param("ii", $sender_id, $receiver_id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Bạn đã gửi lời mời trước đó!"]);
  } else {
    $stmt = $conn->prepare("INSERT INTO chat_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();

    echo json_encode(["status" => "success", "message" => "Lời mời đã gửi!"]);
  }
}

function handleMessage($conn, $sender_id, $receiver_id, $message): void
{
  if (!$receiver_id || !$message) {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu gửi tin nhắn!"]);
    return;
  }

  $check = $conn->prepare("SELECT 1 FROM chat_requests WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) AND status = 'accepted'");
  $check->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Bạn chưa được chấp nhận chat!"]);
    return;
  }

  // Lưu tin nhắn vào bảng messages
  $stmt = $conn->prepare("INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, image) VALUES (?, ?, ?, NULL)");
  $stmt->bind_param("iis", $receiver_id, $sender_id, $message);
  $stmt->execute();

  echo json_encode([
    "status" => "success",
    "message" => "Tin nhắn đã gửi!",
    "data" => [
      "msg_id" => $stmt->insert_id,
      "incoming_msg_id" => $receiver_id,
      "outgoing_msg_id" => $sender_id,
      "msg" => $message,
      "image" => null
    ]
  ]);
}

function handleAcceptChat($conn, $sender_id, $receiver_id): void
{
  $stmt = $conn->prepare("UPDATE chat_requests SET status = 'accepted' WHERE sender_id = ? AND receiver_id = ?");
  $stmt->bind_param("ii", $receiver_id, $sender_id);
  $stmt->execute();

  echo json_encode(["status" => "success", "message" => "Chấp nhận chat thành công!"]);
}
