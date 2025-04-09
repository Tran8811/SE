<?php
$host = 'localhost';
$dbname = 'cupid_db';
$username = 'root';
$password = '0966732929';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Kết nối database thất bại: " . $e->getMessage());
}

// ✅ Thêm status vào SELECT
$query = "
    SELECT
        u.username, u.email, u.age, u.gender, u.status,
        (SELECT GROUP_CONCAT(IF(m.user1 = u.username, m.user2, m.user1)) FROM matches m WHERE m.user1 = u.username OR m.user2 = u.username) AS matched_users,
        (SELECT COUNT(*) FROM reports r WHERE r.reported = u.username) AS report_count
    FROM users u
    ORDER BY u.username ASC
";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Người dùng</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Roboto', sans-serif; }
    body { background-color: #f0f2f5; display: flex; height: 100vh; justify-content: center; align-items: center; }
    .container { width: 90%; max-width: 1200px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden; display: flex; }
    .sidebar { width: 250px; background: #283e4a; color: white; padding: 20px; display: flex; flex-direction: column; }
    .sidebar h2 { text-align: center; }
    .sidebar a { color: white; padding: 15px; text-decoration: none; display: block; transition: 0.3s; }
    .sidebar a:hover { background: #4a6982; }
    .main-content { flex: 1; padding: 20px; }
    h1 { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid black; padding: 10px; text-align: center; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">Dashboard</a>
  </div>
  <div class="main-content">
    <h1>Danh sách Người dùng</h1>
    <table>
      <tr>
        <th>Tên</th>
        <th>Email</th>
        <th>Trạng thái</th>
        <th>Đã ghép đôi với</th>
        <th>Bị báo cáo</th>
      </tr>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?php echo htmlspecialchars($user['username']); ?></td>
          <td><?php echo htmlspecialchars($user['email']); ?></td>
          <td><?php echo ucfirst($user['status'] ?? 'Không xác định'); ?></td>  <!-- ✅ Xử lý lỗi NULL -->
          <td><?php echo $user['matched_users'] ? htmlspecialchars($user['matched_users']) : 'Chưa ghép đôi'; ?></td>
          <td><?php echo $user['report_count']; ?> lần</td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
</body>
</html>

