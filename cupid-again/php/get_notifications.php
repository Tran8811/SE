<?php
/** @noinspection ALL */
session_start();
require_once "get_connection.php";

header("Content-Type: application/json");

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION["user_id"])) {
  die(json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]));
}

$receiver_id = $_SESSION["user_id"];

// Lấy thông báo từ bảng notifications (trừ loại chat_request)
$sql_notifications = "SELECT id, type, content, is_read, created_at FROM notifications WHERE user_id = ? AND type != 'chat_request' ORDER BY created_at DESC";
$stmt_notifications = $conn->prepare($sql_notifications);
$stmt_notifications->bind_param("i", $receiver_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();

$notifications = [];

while ($row = $result_notifications->fetch_assoc()) {
  $notifications[] = [
    "id"         => $row["id"],
    "type"       => $row["type"],
    "content"    => $row["content"],
    "is_read"    => $row["is_read"],
    "created_at" => $row["created_at"]
  ];
}

// Kiểm tra nếu user đã match thì không cần hiển thị lời mời nữa
$check_match = $conn->prepare("SELECT * FROM matches WHERE user1_id = ? OR user2_id = ?");
$check_match->bind_param("ii", $receiver_id, $receiver_id);
$check_match->execute();
$match_result = $check_match->get_result();
$has_match = $match_result->num_rows > 0;
$check_match->close();

// Lấy lời mời chat nếu chưa match
if (!$has_match) {
  $sql_chat_requests = "SELECT cr.id, cr.sender_id, u.username AS sender_name, cr.created_at
                        FROM chat_requests cr
                        JOIN users u ON cr.sender_id = u.id
                        WHERE cr.receiver_id = ? AND cr.status = 'pending'
                        ORDER BY cr.created_at DESC";
  $stmt_chat_requests = $conn->prepare($sql_chat_requests);
  $stmt_chat_requests->bind_param("i", $receiver_id);
  $stmt_chat_requests->execute();
  $result_chat_requests = $stmt_chat_requests->get_result();

  while ($row = $result_chat_requests->fetch_assoc()) {
    $notifications[] = [
      "id"          => $row["id"],
      "type"        => "chat_request",
      "content"     => "Bạn có một lời mời chat từ " . $row["sender_name"],
      "created_at"  => $row["created_at"],
      "sender_id"   => $row["sender_id"],
      "sender_name" => $row["sender_name"]
    ];
  }

  $stmt_chat_requests->close();
}

usort($notifications, function ($a, $b) {
  return strtotime($b['created_at']) - strtotime($a['created_at']);
});

echo json_encode([
  "status" => "success",
  "notifications" => $notifications,
  "receiver_id" => $receiver_id
], JSON_PRETTY_PRINT);

$stmt_notifications->close();
$conn->close();
