<?php
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama'] ?? '');
  $nim = trim($_POST['nim'] ?? '');
  $prodi = trim($_POST['prodi'] ?? '');

  if ($nama === '' || $nim === '' || $prodi === '') {
    $errorMessage = 'Semua kolom wajib diisi.';
  } else {
    ob_start();
    require __DIR__ . '/connection.php';
    ob_end_clean();

    if (!$conn) {
      $errorMessage = 'Gagal terhubung ke database.';
    } else {
      $stmt = mysqli_prepare($conn, "INSERT INTO mahasiswa (nama, nim, prodi) VALUES (?, ?, ?)");
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $nama, $nim, $prodi);
        if (mysqli_stmt_execute($stmt)) {
          $successMessage = 'Data mahasiswa berhasil disimpan.';
        } else {
          $errorMessage = 'Gagal menyimpan data: ' . htmlspecialchars(mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
      } else {
        $errorMessage = 'Gagal mempersiapkan query.';
      }

      mysqli_close($conn);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Data Mahasiswa</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0f172a, #1e1b4b);
      color: #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .card {
      width: 100%;
      max-width: 520px;
      background: rgba(15, 23, 42, 0.85);
      border-radius: 24px;
      padding: 2.5rem;
      box-shadow: 0 25px 50px rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(148, 163, 184, 0.25);
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    p {
      margin-bottom: 1.5rem;
      color: #94a3b8;
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.35rem;
    }

    input {
      width: 100%;
      padding: 0.85rem 1rem;
      border: 1px solid rgba(148, 163, 184, 0.4);
      border-radius: 12px;
      background: rgba(15, 23, 42, 0.6);
      color: #f8fafc;
      font-size: 1rem;
      margin-bottom: 1.25rem;
    }

    input:focus {
      outline: none;
      border-color: #38bdf8;
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
    }

    button {
      width: 100%;
      padding: 0.95rem 1.5rem;
      border: none;
      border-radius: 999px;
      background: linear-gradient(120deg, #22d3ee, #6366f1);
      color: #0f172a;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    button:hover {
      transform: translateY(-1px);
      box-shadow: 0 15px 30px rgba(79, 70, 229, 0.35);
    }

    .alert {
      padding: 0.9rem 1.1rem;
      border-radius: 12px;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .alert.success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.5);
      color: #34d399;
    }

    .alert.error {
      background: rgba(248, 113, 113, 0.15);
      border: 1px solid rgba(248, 113, 113, 0.5);
      color: #fca5a5;
    }
  </style>
</head>

<body>
  <div class="card">
    <h1>Form Input Mahasiswa</h1>
    <p>Tambahkan data mahasiswa ke tabel <code>mahasiswa</code>.</p>

    <?php if ($successMessage): ?>
      <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
      <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="nama">Nama Lengkap</label>
      <input type="text" id="nama" name="nama" placeholder="Contoh: Siti Rahmawati" required
        value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">

      <label for="nim">NIM</label>
      <input type="text" id="nim" name="nim" placeholder="Contoh: 1234567890" required
        value="<?php echo htmlspecialchars($_POST['nim'] ?? ''); ?>">

      <label for="prodi">Program Studi</label>
      <input type="text" id="prodi" name="prodi" placeholder="Contoh: Teknik Informatika" required
        value="<?php echo htmlspecialchars($_POST['prodi'] ?? ''); ?>">

      <button type="submit">Simpan Data</button>
    </form>
  </div>
</body>

</html>