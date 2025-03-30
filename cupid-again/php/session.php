
<?php

// Nếu chưa có session_id thì tạo mới
if (!isset($_SESSION['unique_id']) && isset($_COOKIE['unique_id'])) {
  $_SESSION['unique_id'] = $_COOKIE['unique_id'];
}

// Nếu chưa có unique_id thì yêu cầu đăng nhập lại
if (!isset($_SESSION['unique_id'])) {
  header("Location: sign_in.php");
  exit();
}
?>
