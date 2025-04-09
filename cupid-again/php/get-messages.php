<?php
session_start();
require_once "get_connection.php";

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION['unique_id'])) {
  die(json_encode(["error" => "Bạn chưa đăng nhập!"]));
}

$unique_id = $_SESSION['unique_id'];
$user_query = mysqli_query($conn, "SELECT id FROM users WHERE unique_id = '$unique_id'");
$user_row = mysqli_fetch_assoc($user_query);
$outgoing_id = $user_row['id'];

$incoming_unique_id = $_POST['incoming_id'] ?? null;
if (!$incoming_unique_id) {
  die(json_encode(["error" => "Thiếu dữ liệu nhận tin nhắn!"]));
}

$incoming_query = mysqli_query($conn, "SELECT id FROM users WHERE unique_id = '$incoming_unique_id'");
$incoming_row = mysqli_fetch_assoc($incoming_query);
$incoming_id = $incoming_row['id'];

$sql = "SELECT * FROM messages
        WHERE (outgoing_msg_id = '$outgoing_id' AND incoming_msg_id = '$incoming_id')
           OR (outgoing_msg_id = '$incoming_id' AND incoming_msg_id = '$outgoing_id')
        ORDER BY msg_id ASC";

$query = mysqli_query($conn, $sql);
$output = "";

if (mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {
    $msgText = htmlspecialchars($row['msg']);
    $msgImage = !empty($row['image']) ? htmlspecialchars($row['image']) : null;
    $chatClass = ($row['outgoing_msg_id'] == $outgoing_id) ? 'outgoing' : 'incoming';

    $output .= '<div class="chat ' . $chatClass . '"><div class="details">';
    if (!empty($msgText)) {
      $output .= '<p>' . $msgText . '</p>';
    }
    if ($msgImage) {
      $output .= '<img src="../uploads/' . $msgImage . '" class="chat-image">';
    }
    $output .= '</div></div>';
  }
} else {
  $output = '<div class="text">Không có tin nhắn nào.</div>';
}

echo $output;
