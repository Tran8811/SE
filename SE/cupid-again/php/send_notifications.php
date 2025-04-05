<?php
/** @noinspection ALL */
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo json_encode(["status" => "error", "message" => "Chỉ chấp nhận POST request"]);
  exit();
}

require_once "get_connection.php";

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION["user_id"])) {
  die(json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]));
}

$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? null;
$sender_id = intval($data['sender_id'] ?? 0);
$receiver_id = intval($_SESSION["user_id"]);

if (!$type || !$sender_id || !$receiver_id) {
  echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
  exit();
}

// ====================== ✅ CHẤP NHẬN CHAT ========================
if ($type === "accept_chat") {
  $conn->begin_transaction();
  try {
    // 1. Cập nhật trạng thái lời mời từ sender đến receiver
    $update_query = "UPDATE chat_requests SET status = 'accepted' WHERE sender_id = ? AND receiver_id = ?";
    $stmt1 = $conn->prepare($update_query);
    $stmt1->bind_param("ii", $sender_id, $receiver_id);
    $stmt1->execute();

    // 2. Xoá tất cả lời mời khác liên quan
    $delete_query = "DELETE FROM chat_requests
                     WHERE (sender_id = ? OR receiver_id = ?)
                        OR (sender_id = ? OR receiver_id = ?)";
    $stmt2 = $conn->prepare($delete_query);
    $stmt2->bind_param("iiii", $sender_id, $sender_id, $receiver_id, $receiver_id);
    $stmt2->execute();

    // 3. Thêm vào bảng matches (kiểm tra trùng)
    $check_match = "SELECT id FROM matches WHERE
                    (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
    $stmt3 = $conn->prepare($check_match);
    $stmt3->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt3->execute();
    $match_result = $stmt3->get_result();

    if ($match_result->num_rows === 0) {
      $insert_match = "INSERT INTO matches (user1_id, user2_id) VALUES (?, ?)";
      $stmt4 = $conn->prepare($insert_match);
      $stmt4->bind_param("ii", $sender_id, $receiver_id);
      $stmt4->execute();
      $stmt4->close();
    }
    $stmt3->close();

    // 4. Gửi thông báo cho cả 2
    $noti_query = "INSERT INTO notifications (user_id, type, content) VALUES
                  (?, 'chat_accepted', 'Bạn và một người dùng đã kết nối thành công!'),
                  (?, 'chat_accepted', 'Bạn và một người dùng đã kết nối thành công!')";
    $stmt5 = $conn->prepare($noti_query);
    $stmt5->bind_param("ii", $sender_id, $receiver_id);
    $stmt5->execute();
    $stmt5->close();

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Đã chấp nhận lời mời chat!"]);
  } catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => "Lỗi xử lý: " . $e->getMessage()]);
  }
  exit();
}

// ====================== ✅ GỬI LỜI MỜI CHAT ========================
if ($type === "chat_request") {
  // Kiểm tra đã có lời mời trước đó chưa
  $check_query = "SELECT * FROM chat_requests
                  WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'";
  $check_stmt = $conn->prepare($check_query);
  $check_stmt->bind_param("ii", $sender_id, $receiver_id);
  $check_stmt->execute();
  $result = $check_stmt->get_result();

  if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Bạn đã gửi lời mời trước đó!"]);
    exit();
  }

  // Kiểm tra nếu đã là match rồi thì không gửi nữa
  $check_match = "SELECT id FROM matches WHERE
                  (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
  $stmt_match = $conn->prepare($check_match);
  $stmt_match->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
  $stmt_match->execute();
  $match_result = $stmt_match->get_result();

  if ($match_result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Hai người đã kết nối!"]);
    exit();
  }

  // Gửi lời mời
  $query = "INSERT INTO chat_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $sender_id, $receiver_id);

  if ($stmt->execute()) {
    $noti_query = "INSERT INTO notifications (user_id, type, content) VALUES (?, 'chat_request', 'Bạn có một lời mời chat!')";
    $noti_stmt = $conn->prepare($noti_query);
    $noti_stmt->bind_param("i", $receiver_id);
    $noti_stmt->execute();
    $noti_stmt->close();

    echo json_encode(["status" => "success", "message" => "Lời mời đã gửi!"]);
  } else {
    echo json_encode(["status" => "error", "message" => "Lỗi khi gửi lời mời"]);
  }

  $stmt->close();
  $check_stmt->close();
  $stmt_match->close();
  $conn->close();
  exit();
}

echo json_encode(["status" => "error", "message" => "Loại yêu cầu không hợp lệ"]);
$conn->close();
?>
