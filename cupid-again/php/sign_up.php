<?php
session_start();
// Kết nối MySQL
$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703";      // Mật khẩu MySQL (nếu có)
$dbname = "cupid_db";
$conn = new mysqli($servername, $username, $password, $dbname);

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


  // Lưu vào database
  $insertQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
  if ($stmt = $conn->prepare($insertQuery)) {
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
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
?>
