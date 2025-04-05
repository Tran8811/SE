<?php
header("Content-Type: application/json");
session_start();
require_once "get_connection.php";

if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
  $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
  $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

  if (empty($username) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]);
    exit();
  }

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

  $unique_id = rand(1000000, 99999999);
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  $insertQuery = "INSERT INTO users (unique_id, username, email, password, mbti) VALUES (?, ?, ?, ?, NULL)";
  if ($stmt = $conn->prepare($insertQuery)) {
    $stmt->bind_param("ssss", $unique_id, $username, $email, $hashed_password);
    if ($stmt->execute()) {
      $_SESSION["username"] = $username;
      $_SESSION["user_id"] = $stmt->insert_id;
      $_SESSION["unique_id"] = $unique_id;
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
