<?php
header("Content-Type: application/json"); // Đảm bảo response là JSON
ob_clean(); // Xóa dữ liệu thừa để tránh lỗi headers

// Hiển thị lỗi PHP để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Không cần hủy session nếu dùng LocalStorage

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "chipchip1703";
$dbname = "cupid_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối MySQL
if ($conn->connect_error) {
  echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
  exit();
}

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Kiểm tra tài khoản trong database
  $sql = "SELECT unique_id, username FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $conn->error]);
    exit();
  }

  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['unique_id'] = $user["unique_id"];
    $_SESSION['username'] = $user["username"];
    // Trả về JSON chứa `unique_id`
    echo json_encode([
      "status" => "success",
      "message" => "Login successful",
      "unique_id" => $user["unique_id"],
      "username" => $user["username"]
    ]);
  } else {
    echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
  }

  $stmt->close();
  $conn->close();
}
?>
