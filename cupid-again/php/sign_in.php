<?php
header("Content-Type: application/json"); // Đảm bảo response là JSON
// Bật hiển thị lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Đảm bảo rằng nội dung trả về là JSON
//header('Content-Type: application/json');

session_start();
$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703";      // Mật khẩu MySQL (nếu có)
$dbname = "cupid_db";  // Tên database của bạn

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối MySQL
if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Debug lỗi nếu kết nối thất bại
if (mysqli_connect_errno()) {
  die(json_encode(["status" => "error", "message" => "Connection failed: " . mysqli_connect_error()]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Kiểm tra tài khoản trong database
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die(json_encode(["status" => "error", "message" => "Error preparing statement: " . $conn->error]));
  }
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $stmt->insert_id;
    session_write_close();
    echo json_encode(["status" => "success", "message" => "Login successful"]);
    exit();  // Dừng chương trình sau khi trả về JSON
  } else {
    // Sai tài khoản hoặc mật khẩu
    echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
    exit();  // Dừng chương trình sau khi trả về JSON
  }

  $stmt->close();
  $conn->close();
}
?>
