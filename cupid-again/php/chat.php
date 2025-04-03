<?php

session_start();

$unique_id = $_SESSION['unique_id'];


$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703";  // Mật khẩu MySQL
$dbname = "cupid_db";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['unique_id'])) {
  header("location: sign_in.php");
  exit();
}

$unique_id = $_SESSION['unique_id'];  // Lấy ID người dùng đang đăng nhập
$users = mysqli_query($conn, "SELECT * FROM users WHERE unique_id != '$unique_id'"); // Lấy danh sách người dùng (trừ chính mình)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat Box</title>
  <link rel="stylesheet" href="../css/chat.css">
</head>
<body>
<div class="container">
  <h2>Chat với Cupid</h2>
  <h2>Username: <span id="user-name"></span></h2>
  <label for="chatWith">Chọn người để chat:</label>
  <select id="chatWith">
    <option value="">Chọn người...</option>
    <?php while ($row = mysqli_fetch_assoc($users)) { ?>
      <option value="<?php echo $row['unique_id']; ?>"><?php echo $row['username']; ?></option>
    <?php } ?>
  </select>

  <div class="chat-box" id="chatBox"></div>

  <div class="chat-input">
    <input type="hidden" id="currentUser" value="<?php echo $unique_id; ?>">
    <input type="text" id="message" placeholder="Nhập tin nhắn...">
    <label for="imageInput" class="upload-icon">
      📷
    </label>
    <input type="file" id="imageInput" accept="image/*" style="display: none;">
    <button id="sendBtn">Gửi</button>
  </div>

</div>
<div id="overlay" class="overlay">
  <div class="overlay-content">
    <img id="largeImage" src="" alt="Ảnh phóng to">
  </div>
</div>

<script src="../js/chat.js"></script>

</body>
</html>
