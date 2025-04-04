<?php
header("Content-Type: application/json");

// Lấy ID người dùng từ request
$userId = isset($_GET['id']) ? $_GET['id'] : 1;
$minAge = isset($_GET['minAge']) ? (int)$_GET['minAge'] : null;
$maxAge = isset($_GET['maxAge']) ? (int)$_GET['maxAge'] : null;
$location = isset($_GET['location']) ? urlencode($_GET['location']) : null;
// Gọi API từ Spring Boot
$springBootAPI = "http://localhost:8081/users/findMatches?id=" . $userId;

/// Thêm các tham số lọc nếu có
if ($minAge !== null) {
  $springBootAPI .= "&minAge=$minAge";
}
if ($maxAge !== null) {
  $springBootAPI .= "&maxAge=$maxAge";
}
if ($location !== null && $location !== "null") {
  $springBootAPI .= "&location=$location";
}
$response = file_get_contents($springBootAPI);
// Kiểm tra API có phản hồi không
if ($response === FALSE) {
  echo json_encode(["error" => "Không thể lấy dữ liệu từ API"]);
  exit;
}

// Chuyển tiếp dữ liệu từ Spring Boot về cho JavaScript
echo $response;
?>
<?php
//// match_api.php
//session_start();
//// Thêm các header CORS để cho phép truy cập từ tất cả các domain (hoặc chỉ từ một domain cụ thể)
//header("Access-Control-Allow-Origin: http://localhost:8080");  // Hoặc "*" để cho phép tất cả các domain
//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
//header("Access-Control-Allow-Headers: Content-Type, Authorization");
//// Kết nối MySQL trực tiếp trong file này
//$servername = "localhost";
//$username = "root";
//$password = "Thu ha123";
//$dbname = "cupid_db";
//
//// Tạo kết nối
//$conn = mysqli_connect($servername, $username, $password, $dbname);
//
//// Kiểm tra kết nối
//if (!$conn) {
//  die("Kết nối thất bại: " . mysqli_connect_error());
//}
//
//if (isset($_GET['id'])) {
//  $userId = (int)$_GET['id'];  // Chuyển đổi ID thành số nguyên để tránh SQL Injection
//
//  // Giả sử bạn có một bảng 'users' trong database
//  $query = "SELECT * FROM users WHERE id != $userId"; // Lấy tất cả người dùng trừ chính mình
//  $result = mysqli_query($conn, $query);
//
//  // Lấy dữ liệu và đưa vào mảng
//  $users = [];
//  while ($row = mysqli_fetch_assoc($result)) {
//    $users[] = $row;
//  }
//
//  // Trả về dữ liệu dưới dạng JSON
//  echo json_encode($users);
//}
//
//// Đóng kết nối cơ sở dữ liệu khi hoàn tất
//mysqli_close($conn);
//?>
