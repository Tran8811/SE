<?php
header("Content-Type: application/json"); // Đảm bảo response là JSON
session_start();

// Kết nối MySQL
$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703"; // Mật khẩu MySQL (nếu có)
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Kiểm tra yêu cầu POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Kiểm tra trường nhập liệu
  if (empty($username) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]);
    exit();
  }

  // Kiểm tra username đã tồn tại chưa
  $checkQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
  if ($stmt = $conn->prepare($checkQuery)) {
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      echo json_encode(["status" => "error", "message" => "Username hoặc Email đã tồn tại!"]);
      exit();
    }
    $stmt->close();
  }

  // Tạo unique_id ngẫu nhiên (số 10 chữ số)
  $unique_id = rand(1000000, 99999999);

  $email = trim($_POST["email"]);
  // Lưu vào database
  $insertQuery = "INSERT INTO users (unique_id, username, email, password, mbti) VALUES (?, ?, ?, ?, NULL)";
  if ($stmt = $conn->prepare($insertQuery)) {
    $stmt->bind_param("ssss", $unique_id, $username, $email, $password);
    if ($stmt->execute()) {
      $_SESSION["username"] = $username;
      $_SESSION["password"] = $password;
      $_SESSION["user_id"] = $stmt->insert_id;
      $_SESSION["unique_id"] = $unique_id;
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
