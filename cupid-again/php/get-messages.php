<?php
session_start();
require_once 'session.php';

$servername = "localhost";
$username = "root";
$password = "chipchip1703";
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['unique_id'])) {
  die("Bạn chưa đăng nhập");
}

$outgoing_id = $_SESSION['unique_id'];
$incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : null;

if (!$incoming_id) {
  die("❌ Lỗi: incoming_id không hợp lệ!");
}

// Truy vấn lấy tin nhắn
$sql = "SELECT * FROM messages
        LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
        WHERE (outgoing_msg_id = '$outgoing_id' AND incoming_msg_id = '$incoming_id')
        OR (outgoing_msg_id = '$incoming_id' AND incoming_msg_id = '$outgoing_id')
        ORDER BY msg_id";

$query = mysqli_query($conn, $sql);
if (!$query) {
  die("❌ Lỗi MySQL: " . mysqli_error($conn));
}

$output = "";
if (mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {
    if ($row['outgoing_msg_id'] == $outgoing_id) {
      // Tin nhắn của chính mình (Bên phải)
      $output .= '<div class="chat outgoing">
                    <div class="details">
                        <p>'. htmlspecialchars($row['msg']) .'</p>
                    </div>
                </div>';
    } else {
      // Tin nhắn của người khác (Bên trái + có avatar)
      $output .= '<div class="chat incoming">
                    <div class="details">
                        <p>'. htmlspecialchars($row['msg']) .'</p>
                    </div>
                </div>';
    }

  }
} else {
  $output .= '<div class="text">Không có tin nhắn nào.</div>';
}

echo $output;
?>
