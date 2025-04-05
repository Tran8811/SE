<?php
session_start();

require_once "get_connection.php";

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION['unique_id'])) {
  die(json_encode(["error" => "Bạn chưa đăng nhập!"]));
}

$outgoing_id = $_SESSION['unique_id'];
$incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : null;

if (!$incoming_id) {
  die(json_encode(["error" => "Thiếu dữ liệu nhận tin nhắn!"]));
}

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

    // Phân biệt tin nhắn của mình và của người khác
    $chatClass = ($row['outgoing_msg_id'] == $outgoing_id) ? 'outgoing' : 'incoming';

    $output .= '<div class="chat ' . $chatClass . '"><div class="details">';

    // Hiển thị văn bản nếu có
    if (!empty($msgText)) {
      $output .= '<p>' . $msgText . '</p>';
    }

    // Hiển thị ảnh nếu có
    if ($msgImage) {
      $output .= '<img src="' . $msgImage . '" class="chat-image">';
    }

    $output .= '</div></div>';
  }
} else {
  $output = '<div class="text">Không có tin nhắn nào.</div>';
}

echo $output;
