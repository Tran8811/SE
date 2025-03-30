<?php
session_start();
require_once 'get_connection.php'; // Kết nối CSDL
// Kiểm tra biến $conn có tồn tại không
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Kiểm tra username trong database
  $sql = "SELECT id, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $hashed_password = $user["password"];

    // **So sánh mật khẩu đã nhập với mật khẩu đã mã hóa**
    if (password_verify($password, $hashed_password)) {
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["username"] = $username;
      echo json_encode(["status" => "success", "message" => "Đăng nhập thành công!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Mật khẩu không đúng!"]);
    }
  } else {
    echo json_encode(["status" => "error", "message" => "Tên người dùng không tồn tại!"]);
  }

  $stmt->close();
  $conn->close();
}
