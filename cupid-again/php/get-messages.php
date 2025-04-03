<?php
session_start();

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

if (!$incoming_id) {
  die(json_encode(["error" => "Thiếu dữ liệu nhận tin nhắn!"]));
}

// Truy vấn lấy tin nhắn, bao gồm cả ảnh
$sql = "SELECT * FROM messages
        LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
        WHERE (outgoing_msg_id = '$outgoing_id' AND incoming_msg_id = '$incoming_id')
           OR (outgoing_msg_id = '$incoming_id' AND incoming_msg_id = '$outgoing_id')
        ORDER BY msg_id";

$query = mysqli_query($conn, $sql);
if (!$query) {
  die(json_encode(["error" => "Lỗi truy vấn dữ liệu: " . mysqli_error($conn)]));
}

$output = "";
if (mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {
    $msgText = htmlspecialchars($row['msg']);
    $msgImage = !empty($row['image']) ? 'uploads/' . htmlspecialchars(basename($row['image'])) : null;

    $chatClass = ($row['outgoing_msg_id'] == $outgoing_id) ? 'outgoing' : 'incoming';

    $output .= '<div class="chat ' . $chatClass . '">
                  <div class="details">';

    // Nếu có văn bản, hiển thị tin nhắn văn bản
    if (!empty($msgText)) {
      $output .= '<p>' . $msgText . '</p>';
    }

    // Nếu có ảnh, hiển thị ảnh với class để phóng to
    if ($msgImage) {
      if ($msgImage) {
        $output .= '<img src="' . $msgImage . '" class="chat-image">';
      }
    }

    $output .= '</div></div>';
  }
} else {
  $output .= '<div class="text">Không có tin nhắn nào.</div>';
}

echo $output;
?>
