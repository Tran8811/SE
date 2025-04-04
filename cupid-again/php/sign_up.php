<?php
/** @noinspection ALL */
header("Content-Type: application/json"); // Đảm bảo response là JSON
session_start();

// Import file config.php để dùng biến $conn
require_once 'get_connection.php';

// Kiểm tra biến $conn có tồn tại không
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]);
    exit();
  }

  // Kiểm tra username đã tồn tại chưa
  $checkQuery = "SELECT id FROM users WHERE username = ?";
  if ($stmt = $conn->prepare($checkQuery)) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      echo json_encode(["status" => "error", "message" => "Username đã tồn tại!"]);
      exit();
    }
    $stmt->close();
  }

  // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  // Lưu vào database
  $insertQuery = "INSERT INTO users (users.username, users.password) VALUES (?, ?)";
  if ($stmt = $conn->prepare($insertQuery)) {
    $stmt->bind_param("ss", $username, $hashedPassword);
    if ($stmt->execute()) {
      $_SESSION["username"] = $username;
      $_SESSION["user_id"] = $stmt->insert_id;
      session_write_close();
      echo json_encode(["status" => "success", "message" => "Đăng ký thành công!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Lỗi đăng ký, thử lại!"]);
    }
    $stmt->close();
  }

  $conn->close();
} else {
  echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ!"]);
}

