<?php
require_once 'get_connection.php';
if (!isset($conn)) {
  die(json_encode(["status" => "error", "message" => "Lỗi kết nối database!"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
  $user_id = $_POST["user_id"];  // ID user từ form
  $target_dir = "../user_avatars/";
  $file_name = "user_" . $user_id . "_" . time() . "." . pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
  $target_file = $target_dir . $file_name;

  // Kiểm tra định dạng file
  $allowed_types = ["jpg", "jpeg", "png", "gif"];
  $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  if (!in_array($file_type, $allowed_types)) {
    die("Chỉ chấp nhận file JPG, JPEG, PNG, GIF.");
  }

  // Kiểm tra và di chuyển file
  if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
    // Cập nhật đường dẫn vào database
    $sql = "UPDATE users SET avatar='$target_file' WHERE id=$user_id";
    if ($conn->query($sql) === TRUE) {
      echo "Upload thành công!";
    } else {
      echo "Lỗi cập nhật database: " . $conn->error;
    }
  } else {
    echo "Lỗi khi upload file.";
  }
}
?>
