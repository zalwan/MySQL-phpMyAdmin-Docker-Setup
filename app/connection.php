<?php

$host = 'mysql';
$username = 'root';
$password = 'root';
$database = 'db_mahasiswa_067';


$conn = mysqli_connect($host, $username, $password, $database);


if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Database Connection Status</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: radial-gradient(circle at top, #0f172a, #020617);
      color: #e2e8f0;
    }
    .card {
      text-align: center;
      padding: 3rem 4rem;
      border-radius: 1.5rem;
      background: rgba(15, 23, 42, 0.9);
      box-shadow: 0 20px 60px rgba(15, 23, 42, 0.65);
      border: 1px solid rgba(148, 163, 184, 0.2);
    }
    .icon {
      font-size: 4rem;
      margin-bottom: 1rem;
    }
    .headline {
      font-size: 2.25rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    .details {
      font-size: 1rem;
      color: #94a3b8;
      margin-top: 1rem;
      line-height: 1.7;
    }
    code {
      background: rgba(148, 163, 184, 0.15);
      padding: 0.15rem 0.45rem;
      border-radius: 0.35rem;
      font-size: 0.95rem;
      color: #f8fafc;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">✅</div>
    <div class="headline">Connection Established</div>
    <p>PHP berhasil terhubung ke database.</p>
    <div class="details">
      Host: <code>' . htmlspecialchars($host) . '</code><br>
      Database: <code>' . htmlspecialchars($database) . '</code><br>
      User: <code>' . htmlspecialchars($username) . '</code>
    </div>
  </div>
</body>
</html>
';
?>
