<?php
$host = 'localhost'; // Hoặc IP của máy chủ database
$dbname = 'cupid_db'; // Tên database của chị
$username = 'root'; // Username của MySQL
$password = '0966732929'; // Password của MySQL (để trống nếu không có)

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Kết nối database thất bại: " . $e->getMessage());
}

$query = "SELECT id, reporter, reported, reason, status, created_at FROM reports ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Báo cáo</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Roboto', sans-serif; }
    body { background-color: #f0f2f5; display: flex; height: 100vh; justify-content: center; align-items: center; }
    .container { width: 90%; max-width: 1200px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden; display: flex; }
    .sidebar { width: 250px; background: #283e4a; color: white; padding: 20px; display: flex; flex-direction: column; }
    .sidebar h2 { text-align: center; }
    .sidebar a { color: white; padding: 15px; text-decoration: none; display: block; transition: 0.3s; }
    .sidebar a:hover { background: #4a6982; }
    .main-content { flex: 1; padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid black; padding: 10px; text-align: center; }
    th { background-color: #f2f2f2; }
    button { padding: 5px 10px; cursor: pointer; border: none; border-radius: 5px; }
    .warn-btn { background-color: orange; color: white; }
    .ban-btn { background-color: red; color: white; }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">Dashboard</a>
  </div>
  <div class="main-content">
    <h1>Danh sách Báo cáo</h1>
    <table>
      <tr>
        <th>ID</th>
        <th>Người báo cáo</th>
        <th>Người bị báo cáo</th>
        <th>Lý do</th>
        <th>Trạng thái</th>
        <th>Thời gian</th>
        <th>Hành động</th>
      </tr>
      <?php foreach ($reports as $report): ?>
        <tr>
          <td><?php echo $report['id']; ?></td>
          <td><?php echo htmlspecialchars($report['reporter']); ?></td>
          <td><?php echo htmlspecialchars($report['reported']); ?></td>
          <td><?php echo htmlspecialchars($report['reason']); ?></td>
          <td><?php echo ucfirst($report['status']); ?></td>
          <td><?php echo $report['created_at']; ?></td>
          <td>
            <form method="POST">
              <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
              <button type="submit" name="action" value="warn" class="warn-btn">Cảnh cáo</button>
              <button type="submit" name="action" value="ban" class="ban-btn">Chặn</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id']) && isset($_POST['action'])) {
  $reportId = $_POST['report_id'];
  $newStatus = ($_POST['action'] === 'warn') ? 'warned' : 'banned';
  $pdo->prepare("UPDATE reports SET status = ? WHERE id = ?")->execute([$newStatus, $reportId]);
  echo "<script>alert('Cập nhật trạng thái thành công!'); window.location='reports.php';</script>";
}
?>
</body>
</html>
