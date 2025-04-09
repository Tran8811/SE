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

// Lấy số lượng cặp đôi
$pairsQuery = "SELECT COUNT(*) FROM matches";
$pairsCount = $pdo->query($pairsQuery)->fetchColumn();

// Lấy số lượng người dùng
$usersQuery = "SELECT COUNT(*) FROM users";
$usersCount = $pdo->query($usersQuery)->fetchColumn();

// Lấy số lượng báo cáo
$reportsQuery = "SELECT COUNT(*) FROM reports";
$reportsCount = $pdo->query($reportsQuery)->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Roboto', sans-serif;
    }
    body {
      background-color: #f0f2f5;
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
    }
    .container {
      width: 90%;
      max-width: 1200px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background: #283e4a;
      color: white;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }
    .sidebar h2 {
      margin-bottom: 20px;
      font-size: 24px;
      text-align: center;
    }
    .sidebar a {
      color: white;
      padding: 15px;
      text-decoration: none;
      border-radius: 4px;
      transition: background 0.3s;
      margin-bottom: 10px;
      display: block;
    }
    .sidebar a:hover {
      background: #4a6982;
    }
    .main-content {
      flex: 1;
      padding: 20px;
    }
    .main-content h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }
    .cards {
      display: flex;
      gap: 20px;
    }
    .card {
      background: #fefefe;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      flex: 1;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="admin_get_users.php">Users</a>
    <a href="setting_admin.php">Settings</a>
    <a href="reports.php">Reports</a>
  </div>

  <div class="main-content">
    <h1>Welcome to the Dashboard</h1>
    <div class="cards">
      <div class="card">Số cặp ghép đôi: <?php echo $pairsCount; ?></div>
      <div class="card">Số người dùng: <?php echo $usersCount; ?></div>
      <div class="card">Số báo cáo: <?php echo $reportsCount; ?></div>
    </div>
  </div>
</div>
</body>
</html>

