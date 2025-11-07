<?php
$records = [];
$errorMessage = '';

ob_start();
require __DIR__ . '/connection.php';
ob_end_clean();

if (!$conn) {
  $errorMessage = 'Tidak dapat terhubung ke database.';
} else {
  $result = mysqli_query($conn, "SELECT id, nama, nim, prodi FROM mahasiswa ORDER BY id DESC");
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $records[] = $row;
    }
    mysqli_free_result($result);
  } else {
    $errorMessage = 'Gagal mengambil data: ' . htmlspecialchars(mysqli_error($conn));
  }
  mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Mahasiswa</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0f172a, #312e81);
      color: #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .wrapper {
      width: 100%;
      max-width: 900px;
      background: rgba(15, 23, 42, 0.9);
      border-radius: 24px;
      padding: 2.25rem;
      box-shadow: 0 30px 60px rgba(15, 23, 42, 0.65);
      border: 1px solid rgba(148, 163, 184, 0.25);
    }

    h1 {
      font-size: 2.2rem;
      margin-bottom: 0.25rem;
    }

    p.subtitle {
      margin-top: 0;
      margin-bottom: 2rem;
      color: #a5b4fc;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      overflow: hidden;
      border-radius: 16px;
      background: rgba(15, 23, 42, 0.7);
    }

    th,
    td {
      padding: 1rem;
      text-align: left;
    }

    th {
      background: rgba(99, 102, 241, 0.25);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      font-size: 0.8rem;
    }

    tbody tr {
      border-top: 1px solid rgba(148, 163, 184, 0.15);
    }

    tbody tr:nth-child(odd) {
      background: rgba(79, 70, 229, 0.08);
    }

    tbody tr:hover {
      background: rgba(59, 130, 246, 0.15);
    }

    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: #94a3b8;
    }

    .alert {
      padding: 1rem 1.2rem;
      border-radius: 14px;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }

    .alert.error {
      background: rgba(248, 113, 113, 0.15);
      border: 1px solid rgba(248, 113, 113, 0.4);
      color: #fca5a5;
    }

    .actions {
      margin-top: 1.5rem;
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .actions a {
      padding: 0.85rem 1.5rem;
      border-radius: 999px;
      text-decoration: none;
      font-weight: 600;
      color: #0f172a;
      background: linear-gradient(120deg, #34d399, #22d3ee);
      box-shadow: 0 12px 30px rgba(14, 165, 233, 0.35);
    }

    .actions a.secondary {
      background: linear-gradient(120deg, #fbbf24, #f97316);
      color: #0f172a;
      box-shadow: 0 12px 30px rgba(251, 191, 36, 0.35);
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <h1>Data Mahasiswa</h1>
    <p class="subtitle">Daftar mahasiswa dari tabel <code>mahasiswa</code>.</p>

    <?php if ($errorMessage): ?>
      <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (!$errorMessage && empty($records)): ?>
      <div class="empty-state">
        <h2>Belum ada data.</h2>
        <p>Silakan tambahkan data mahasiswa terlebih dahulu.</p>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Program Studi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['nama']); ?></td>
              <td><?php echo htmlspecialchars($row['nim']); ?></td>
              <td><?php echo htmlspecialchars($row['prodi']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="actions">
      <a href="form-input.php">Tambah Data</a>
      <a class="secondary" href="index.php">Kembali ke Viewer</a>
    </div>
  </div>
</body>

</html>