<?php
session_start();
require_once "get_connection.php";

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if (!isset($_SESSION['unique_id'])) {
  header("location: sign_in.php");
  exit();
}

$unique_id = $_SESSION['unique_id'];
$user_query = mysqli_query($conn, "SELECT username, id FROM users WHERE unique_id = '$unique_id'");
$user_row = mysqli_fetch_assoc($user_query);
$username = $user_row['username'] ?? 'Không rõ';
$current_id = $user_row['id'];

// Kiểm tra xem có ghép đôi hay không
$match_query = mysqli_query($conn, "
  SELECT u.id, u.username, u.unique_id FROM matches m
  JOIN users u ON (u.id = IF(m.user1_id = $current_id, m.user2_id, m.user1_id))
  WHERE m.user1_id = $current_id OR m.user2_id = $current_id
");

$matched_user = mysqli_fetch_assoc($match_query);
$matched_id = $matched_user['id'] ?? null;

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chat Box</title>
  <link href="../css/chat.css" rel="stylesheet" />
</head>
<body>
<div class="container centered">
  <div class="puppy-logo">
    <button class="logo-btn" id="logo-btn">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/TEMP/e68fd6c3f2ab1ff46421e4a7ac072d069f3385fab2f814fe93158c1166e4120a"
        class="puppy-logo-image"
        alt="Chat Logo"
      />
    </button>
    <p class="puppy-logo-text">CHAT WITH CUPID</p>
  </div>

  <?php if ($matched_user): ?>
    <input type="hidden" id="chatWith" data-id="<?= $matched_user['unique_id'] ?>" value="<?= $matched_user['unique_id'] ?>">
    <input type="hidden" id="currentUser" value="<?= $unique_id ?>">
    <p class="puppy-logo-text">Đang chat với: <?= htmlspecialchars($matched_user['username']) ?></p>
  <?php else: ?>
    <div class="no-match">Bạn chưa có ai ghép đôi.</div>
  <?php endif; ?>

  <div class="chat-box" id="chatBox">
    <?php
    if ($matched_user) {
      // Truy vấn tin nhắn giữa hai người dùng
      $message_query = mysqli_query($conn, "
  SELECT * FROM messages
  WHERE (incoming_msg_id = $current_id AND outgoing_msg_id = $matched_id)
     OR (incoming_msg_id = $matched_id AND outgoing_msg_id = $current_id)
  ORDER BY msg_id ASC");

      while ($message = mysqli_fetch_assoc($message_query)) {
        $message_class = ($message['outgoing_msg_id'] == $current_id) ? 'outgoing' : 'incoming';
        ?>
        <div class="chat <?= $message_class ?>">
          <div class="details">
            <?php if ($message['image']) : ?>
              <img class="chat-image" src="../uploads/<?= htmlspecialchars($message['image']) ?>" alt="Image Message" />
            <?php endif; ?>
            <p><?= htmlspecialchars($message['msg']) ?></p>
          </div>
        </div>
        <?php
      }
    }
    ?>
  </div>

  <div class="chat-input">
    <!-- Vùng preview ảnh -->
    <div id="imagePreviewContainer" style="display: none;">
      <div class="image-preview-wrapper">
        <img id="imagePreview" class="image-preview" src="#" alt="preview">
        <button class="cancel-image-btn" onclick="cancelImagePreview()">×</button>
      </div>
    </div>

    <!-- Vùng nhập và nút gửi -->
    <div class="input-row">
      <input type="text" id="messageInput" placeholder="Nhập tin nhắn..." autocomplete="off">

      <!-- Nút upload ảnh (máy ảnh) -->
      <label for="imageInput" class="image-upload-btn" title="Gửi ảnh">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
          <circle cx="12" cy="13" r="4"/>
        </svg>
      </label>
      <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="previewImage(this)" />

      <!-- Nút chatbot -->
      <button id="chatbotBtn" title="Chatbot">
        <img src="../img/chatbot_icon.png" alt="Chatbot" class="chatbot-icon" />
      </button>

      <!-- Nút gửi -->
      <button id="sendBtn" title="Gửi tin nhắn">
        <svg class="send-icon" width="24" height="24" viewBox="0 0 48 48" fill="none"
             xmlns="http://www.w3.org/2000/svg">
          <path d="M44 4L22 26M44 4L30 44L22 26M44 4L4 18L22 26"
                stroke="#1E1E1E"
                stroke-width="4"
                stroke-linecap="round"
                stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>
  </div>
</div>

<div id="overlay" class="overlay">
  <div class="overlay-content">
    <img id="largeImage" src="" alt="Ảnh phóng to">
  </div>
</div>
<script src="https://cdn.socket.io/4.3.2/socket.io.min.js"></script>
<script src="../js/chat.js"></script>
<script>
  document.getElementById("chatbotBtn").addEventListener("click", function () {
    window.location.href = "../html/chat_with_chatbot.html";
  });
  document.getElementById("logo-btn").addEventListener("click", function () {
    window.location.href = "../html/profile.html";
  });
</script>
</body>
</html>
