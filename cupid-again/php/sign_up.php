<?php
header("Content-Type: application/json"); // Đảm bảo response là JSON
session_start();

// Kết nối MySQL
$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "Thu ha123";      // Mật khẩu MySQL (nếu có)
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => "Kết nối tới cơ sở dữ liệu thất bại: " . $conn->connect_error]));
}

// Kiểm tra yêu cầu POST
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
  $insertQuery = "INSERT INTO users (username, password, mbti) VALUES (?, ?, NULL)";
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
?>
