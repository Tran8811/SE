<?php


$servername = "localhost";
$username = "root";  // Tài khoản MySQL của bạn
$password = "chipchip1703";      // Mật khẩu MySQL (nếu có)
$dbname = "cupid_db";  // Tên database của bạn

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Kiểm tra tài khoản trong database
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Đăng nhập thành công
    $_SESSION["username"] = $username;
    echo json_encode(["status" => "success", "message" => "Login successful"]);
  } else {
    // Sai tài khoản hoặc mật khẩu
    echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
  }

  $stmt->close();
  $conn->close();
}

?>
