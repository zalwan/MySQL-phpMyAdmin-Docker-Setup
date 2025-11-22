<?php
$successMessage = '';
$errorMessage = '';
$uploadedFilePath = '';
$hasFotoColumn = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama'] ?? '');
  $nim = trim($_POST['nim'] ?? '');
  $prodi = trim($_POST['prodi'] ?? '');

  if ($nama === '' || $nim === '' || $prodi === '') {
    $errorMessage = 'Semua kolom wajib diisi.';
  }

  // Handle file upload if a file is provided.
  if (!$errorMessage) {
    $uploadDir = __DIR__ . '/uploads';
    $foto = $_FILES['foto'] ?? null;
    $hasFoto = $foto && $foto['error'] !== UPLOAD_ERR_NO_FILE;

    if ($hasFoto) {
      if ($foto['error'] !== UPLOAD_ERR_OK) {
        $errorMessage = 'Gagal mengunggah file. Silakan coba lagi.';
      } else {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2 MB
        $fileSize = $foto['size'] ?? 0;
        $originalName = $foto['name'] ?? '';
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
        $mimeType = $finfo ? finfo_file($finfo, $foto['tmp_name']) : ($foto['type'] ?? '');
        if ($finfo) {
          finfo_close($finfo);
        }

        if (!in_array($fileExtension, $allowedExtensions, true) || !in_array($mimeType, $allowedMimeTypes, true)) {
          $errorMessage = 'File harus berupa gambar (JPG, JPEG, PNG, atau GIF).';
        } elseif ($fileSize > $maxSize) {
          $errorMessage = 'Ukuran file maksimal 2 MB.';
        } else {
          if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            $errorMessage = 'Folder upload tidak dapat dibuat.';
          } else {
            $uniqueName = uniqid('foto_', true) . '.' . $fileExtension;
            $targetPath = $uploadDir . '/' . $uniqueName;

            if (move_uploaded_file($foto['tmp_name'], $targetPath)) {
              $uploadedFilePath = '/uploads/' . $uniqueName;
            } else {
              $errorMessage = 'Gagal memindahkan file upload.';
            }
          }
        }
      }
    }
  }

  if (!$errorMessage) {
    ob_start();
    require __DIR__ . '/connection.php';
    ob_end_clean();

    if (!$conn) {
      $errorMessage = 'Gagal terhubung ke database.';
    } else {
      $columnCheck = mysqli_query($conn, "SHOW COLUMNS FROM mahasiswa LIKE 'foto'");
      if ($columnCheck) {
        $hasFotoColumn = mysqli_num_rows($columnCheck) > 0;
        mysqli_free_result($columnCheck);
      }

      // Auto-create foto column if missing so uploads can be saved.
      if (!$hasFotoColumn) {
        $addColumn = mysqli_query($conn, "ALTER TABLE mahasiswa ADD COLUMN foto VARCHAR(255) NULL");
        if ($addColumn) {
          $hasFotoColumn = true;
        }
      }

      $stmt = $hasFotoColumn
        ? mysqli_prepare($conn, "INSERT INTO mahasiswa (nama, nim, prodi, foto) VALUES (?, ?, ?, ?)")
        : mysqli_prepare($conn, "INSERT INTO mahasiswa (nama, nim, prodi) VALUES (?, ?, ?)");

      if ($stmt) {
        if ($hasFotoColumn) {
          $fotoPathToStore = $uploadedFilePath !== '' ? $uploadedFilePath : null;
          mysqli_stmt_bind_param($stmt, 'ssss', $nama, $nim, $prodi, $fotoPathToStore);
        } else {
          mysqli_stmt_bind_param($stmt, 'sss', $nama, $nim, $prodi);
        }

        if (mysqli_stmt_execute($stmt)) {
          $successMessage = 'Data mahasiswa berhasil disimpan.' . ($uploadedFilePath ? ' Foto berhasil diunggah.' : '');
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

    .file-input {
      border: 1px dashed rgba(148, 163, 184, 0.6);
      background: rgba(15, 23, 42, 0.4);
      cursor: pointer;
    }

    .file-input::-webkit-file-upload-button {
      padding: 0.6rem 1rem;
      border: none;
      border-radius: 10px;
      margin-right: 0.75rem;
      background: rgba(99, 102, 241, 0.25);
      color: #c7d2fe;
      cursor: pointer;
    }

    .preview {
      margin-top: 1.25rem;
      padding: 1rem;
      border-radius: 14px;
      background: rgba(34, 197, 94, 0.08);
      border: 1px solid rgba(34, 197, 94, 0.3);
      text-align: center;
    }

    .preview img {
      max-width: 100%;
      height: auto;
      border-radius: 12px;
      margin-top: 0.75rem;
      box-shadow: 0 15px 30px rgba(14, 165, 233, 0.25);
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

    <form method="POST" action="" enctype="multipart/form-data">
      <label for="nama">Nama Lengkap</label>
      <input type="text" id="nama" name="nama" placeholder="Contoh: Siti Rahmawati" required
        value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">

      <label for="nim">NIM</label>
      <input type="text" id="nim" name="nim" placeholder="Contoh: 1234567890" required
        value="<?php echo htmlspecialchars($_POST['nim'] ?? ''); ?>">

      <label for="prodi">Program Studi</label>
      <input type="text" id="prodi" name="prodi" placeholder="Contoh: Teknik Informatika" required
        value="<?php echo htmlspecialchars($_POST['prodi'] ?? ''); ?>">

      <label for="foto">Foto Mahasiswa</label>
      <input type="file" id="foto" name="foto" accept="image/*" class="file-input">

      <button type="submit">Simpan Data</button>
    </form>

    <?php if ($uploadedFilePath): ?>
      <div class="preview">
        <div>Foto berhasil diunggah.</div>
        <img src="<?php echo htmlspecialchars($uploadedFilePath); ?>" alt="Foto mahasiswa">
      </div>
    <?php endif; ?>
  </div>
</body>

</html>
