<?php
require_once "get_connection.php";
session_start();
header('Content-Type: application/json');

if (!isset($conn)) {
  echo json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]);
  exit;
}

$user_id = $_SESSION["user_id"] ?? null;
if (!$user_id) {
  echo json_encode(['status' => 'error', 'message' => 'Thiếu user_id']);
  exit;
}

// Kiểm tra xem user có match nào đang pending không
// ...
$sql = "SELECT m.*, u.username FROM matches m
        JOIN users u ON (u.id = IF(m.user1_id = ?, m.user2_id, m.user1_id))
        WHERE (m.user1_id = ? OR m.user2_id = ?)
        AND m.status = 'pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode([
    'hasPending' => true,
    'matchedUserId' => ($row['user1_id'] == $user_id) ? $row['user2_id'] : $row['user1_id'],
    'username' => $row['username']
  ]);
} else {
  echo json_encode(['hasPending' => false]);
}
