<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MySQL Data Viewer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(135deg, #1f2937, #374151);
    }
  </style>
</head>

<body class="min-h-screen flex flex-col items-center py-10 px-4">
  <div class="w-full max-w-4xl bg-white/10 backdrop-blur-md rounded-xl shadow-2xl p-8 border border-white/20">

    <h1 class="text-4xl font-extrabold text-center text-white mb-2">
      🐘 MySQL Data Viewer
    </h1>
    <p class="text-sm text-gray-500 mt-4">
      Tambah data via <a href="http://localhost:8080" target="_blank" class="text-blue-400 underline">phpMyAdmin</a>
    </p>
    <p class="text-center text-gray-300 mb-8">
      Menampilkan data dari tabel <code class="bg-gray-700 px-2 py-1 rounded">users</code>
    </p>

    <?php

    $host = 'mysql';
    $dbname = 'contoh';
    $username = 'root';
    $password = 'root';

    try {
      $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
      $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);

      $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
      $users = $stmt->fetchAll();

      if (count($users) > 0) {
        ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-gray-600">
                <th class="px-4 py-3 text-gray-200 font-semibold">#</th>
                <th class="px-4 py-3 text-gray-200 font-semibold">Nama</th>
                <th class="px-4 py-3 text-gray-200 font-semibold">Email</th>
                <th class="px-4 py-3 text-gray-200 font-semibold">Dibuat</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($users as $user) {
                ?>
                <tr class="border-b border-gray-700 hover:bg-white/5 transition">
                  <td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($user['id']); ?></td>
                  <td class="px-4 py-3 text-white font-medium"><?php echo htmlspecialchars($user['name']); ?></td>
                  <td class="px-4 py-3 text-blue-300"><?php echo htmlspecialchars($user['email']); ?></td>
                  <td class="px-4 py-3 text-gray-400 text-sm"><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
                <?php
              }
              ?>
            </tbody>
          </table>
        </div>

        <div class="mt-6 text-center">
          <span class="inline-block px-4 py-2 bg-green-600/20 text-green-300 rounded-full text-sm font-medium">
            ✅ <?php echo count($users); ?> data ditemukan
          </span>
        </div>

        <?php
      } else {
        ?>
        <div class="text-center py-12">
          <div class="text-6xl mb-4">📭</div>
          <h2 class="text-2xl font-bold text-gray-200 mb-2">Tabel Kosong</h2>
          <p class="text-gray-400">Belum ada data di tabel <code class="bg-gray-700 px-2 py-1 rounded">users</code>.</p>
          <p class="text-sm text-gray-500 mt-4">
            Tambah data via <a href="http://localhost:8080" target="_blank" class="text-blue-400 underline">phpMyAdmin</a>
          </p>
        </div>

        <?php
      }

    } catch (PDOException $e) {
      ?>
      <div class="bg-red-600/20 border border-red-500/30 rounded-lg p-6 text-center">
        <div class="text-4xl mb-3">❌</div>
        <h2 class="text-xl font-bold text-red-300 mb-2">Koneksi Gagal</h2>
        <p class="text-red-200 text-sm mb-4"><?php echo htmlspecialchars($e->getMessage()); ?></p>
        <div class="text-xs text-gray-400 bg-gray-800/50 p-3 rounded mt-2">
          Pastikan: <br>
          - MySQL container berjalan<br>
          - Database <code>sample_db</code> ada<br>
          - Tabel <code>users</code> tersedia
        </div>
      </div>
      <?php
    }
    ?>

  </div>

  <div class="mt-8 text-gray-500 text-sm">
    Dibuat dengan ❤️ & Docker • Nginx + PHP-FPM + MySQL
  </div>
</body>

</html>