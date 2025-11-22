<?php
$records = [];
$errorMessage = '';
$successMessage = '';
$editingRecord = null;
$searchTerm = trim($_GET['q'] ?? ($_POST['q'] ?? ''));
$redirectTarget = $_SERVER['PHP_SELF'];
$hasFotoColumn = false;

ob_start();
require __DIR__ . '/connection.php';
ob_end_clean();

if (!$conn) {
  $errorMessage = 'Tidak dapat terhubung ke database.';
} else {
  $columnCheck = mysqli_query($conn, "SHOW COLUMNS FROM mahasiswa LIKE 'foto'");
  if ($columnCheck) {
    $hasFotoColumn = mysqli_num_rows($columnCheck) > 0;
    mysqli_free_result($columnCheck);
  }
  if (!$hasFotoColumn) {
    $addColumn = mysqli_query($conn, "ALTER TABLE mahasiswa ADD COLUMN foto VARCHAR(255) NULL");
    if ($addColumn) {
      $hasFotoColumn = true;
    }
  }
  $selectColumns = $hasFotoColumn ? 'id, nama, nim, prodi, foto' : 'id, nama, nim, prodi';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id <= 0) {
        $errorMessage = 'ID tidak valid untuk dihapus.';
      } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM mahasiswa WHERE id = ?");
        if ($stmt) {
          mysqli_stmt_bind_param($stmt, 'i', $id);
          if (mysqli_stmt_execute($stmt)) {
            $successMessage = 'Data mahasiswa berhasil dihapus.';
            if ($searchTerm !== '') {
              $redirectTarget .= '?q=' . urlencode($searchTerm);
            }
            header('Location: ' . $redirectTarget);
            exit;
          } else {
            $errorMessage = 'Gagal menghapus data: ' . htmlspecialchars(mysqli_error($conn));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage = 'Gagal mempersiapkan query hapus.';
        }
      }
    } elseif ($action === 'update') {
      $id = (int)($_POST['id'] ?? 0);
      $nama = trim($_POST['nama'] ?? '');
      $nim = trim($_POST['nim'] ?? '');
      $prodi = trim($_POST['prodi'] ?? '');
      $searchTermPost = trim($_POST['q'] ?? '');
      if ($searchTermPost !== '') {
        $searchTerm = $searchTermPost;
      }

      if ($id <= 0 || $nama === '' || $nim === '' || $prodi === '') {
        $errorMessage = 'Semua kolom wajib diisi untuk pembaruan.';
        $editingRecord = ['id' => $id, 'nama' => $nama, 'nim' => $nim, 'prodi' => $prodi];
      } else {
        $stmt = mysqli_prepare($conn, "UPDATE mahasiswa SET nama = ?, nim = ?, prodi = ? WHERE id = ?");
        if ($stmt) {
          mysqli_stmt_bind_param($stmt, 'sssi', $nama, $nim, $prodi, $id);
          if (mysqli_stmt_execute($stmt)) {
            $successMessage = 'Data mahasiswa berhasil diperbarui.';
            if ($searchTerm !== '') {
              $redirectTarget .= '?q=' . urlencode($searchTerm);
            }
            header('Location: ' . $redirectTarget);
            exit;
          } else {
            $errorMessage = 'Gagal memperbarui data: ' . htmlspecialchars(mysqli_error($conn));
            $editingRecord = ['id' => $id, 'nama' => $nama, 'nim' => $nim, 'prodi' => $prodi];
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage = 'Gagal mempersiapkan query update.';
          $editingRecord = ['id' => $id, 'nama' => $nama, 'nim' => $nim, 'prodi' => $prodi];
        }
      }
    }
  }

  if (!$errorMessage && isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
      $stmt = mysqli_prepare($conn, "SELECT $selectColumns FROM mahasiswa WHERE id = ?");
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        if (mysqli_stmt_execute($stmt)) {
          $result = mysqli_stmt_get_result($stmt);
          $editingRecord = mysqli_fetch_assoc($result) ?: null;
          mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);
      }
    }
  }

  if ($searchTerm !== '') {
    $stmt = mysqli_prepare(
      $conn,
      "SELECT $selectColumns FROM mahasiswa
       WHERE nama LIKE ? OR nim LIKE ? OR prodi LIKE ?
       ORDER BY id DESC"
    );
    if ($stmt) {
      $likeTerm = '%' . $searchTerm . '%';
      mysqli_stmt_bind_param($stmt, 'sss', $likeTerm, $likeTerm, $likeTerm);
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
          $records[] = $row;
        }
        mysqli_free_result($result);
      } elseif (!$errorMessage) {
        $errorMessage = 'Gagal menjalankan pencarian: ' . htmlspecialchars(mysqli_error($conn));
      }
      mysqli_stmt_close($stmt);
    } elseif (!$errorMessage) {
      $errorMessage = 'Gagal menyiapkan query pencarian.';
    }
  } else {
    $result = mysqli_query($conn, "SELECT $selectColumns FROM mahasiswa ORDER BY id DESC");
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
      }
      mysqli_free_result($result);
    } elseif (!$errorMessage) {
      $errorMessage = 'Gagal mengambil data: ' . htmlspecialchars(mysqli_error($conn));
    }
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

    .search-bar {
      display: flex;
      gap: 0.75rem;
      margin: 1.5rem 0;
      flex-wrap: wrap;
    }

    .search-bar input {
      flex: 1;
      min-width: 220px;
      padding: 0.8rem 1rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.4);
      background: rgba(15, 23, 42, 0.7);
      color: #f8fafc;
    }

    .search-bar input:focus {
      outline: none;
      border-color: #22d3ee;
      box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.2);
    }

    .search-bar button,
    .search-bar a.reset-search {
      padding: 0.8rem 1.6rem;
      border-radius: 999px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .search-bar button {
      background: linear-gradient(120deg, #22d3ee, #6366f1);
      color: #0f172a;
    }

    .search-bar a.reset-search {
      background: rgba(148, 163, 184, 0.15);
      color: #e2e8f0;
      border: 1px solid rgba(148, 163, 184, 0.3);
    }

    .search-bar button:hover,
    .search-bar a.reset-search:hover {
      transform: translateY(-1px);
      box-shadow: 0 12px 24px rgba(15, 23, 42, 0.3);
    }

    .alert.success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.5);
      color: #34d399;
    }

    .actions-cell {
      display: flex;
      gap: 0.35rem;
    }

    .actions-cell form {
      margin: 0;
    }

    .badge-btn {
      padding: 0.35rem 0.85rem;
      border-radius: 999px;
      border: none;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .badge-btn.edit {
      background: rgba(59, 130, 246, 0.2);
      color: #93c5fd;
      border: 1px solid rgba(59, 130, 246, 0.4);
      text-decoration: none;
      display: inline-block;
    }

    .badge-btn.delete {
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      border: 1px solid rgba(239, 68, 68, 0.4);
    }

    .edit-card {
      margin-top: 2rem;
      padding: 2rem;
      border-radius: 20px;
      background: rgba(15, 23, 42, 0.75);
      border: 1px solid rgba(148, 163, 184, 0.25);
    }

    .edit-card h2 {
      margin-top: 0;
      margin-bottom: 1rem;
    }

    .edit-card label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.3rem;
    }

    .edit-card input {
      width: 100%;
      padding: 0.8rem 1rem;
      border-radius: 12px;
      border: 1px solid rgba(148, 163, 184, 0.4);
      background: rgba(15, 23, 42, 0.6);
      color: #f8fafc;
      margin-bottom: 1.1rem;
    }

    .edit-card input:focus {
      outline: none;
      border-color: #38bdf8;
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
    }

    .edit-card button {
      width: 100%;
      padding: 0.9rem;
      border-radius: 999px;
      border: none;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      background: linear-gradient(120deg, #22d3ee, #6366f1);
      color: #0f172a;
    }

    .foto-cell img {
      width: 64px;
      height: 64px;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid rgba(148, 163, 184, 0.3);
      background: rgba(15, 23, 42, 0.5);
    }

    .muted {
      color: #94a3b8;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <h1>Data Mahasiswa</h1>
    <p class="subtitle">Daftar mahasiswa dari tabel <code>mahasiswa</code>.</p>

    <?php if ($successMessage): ?>
      <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
      <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form class="search-bar" method="GET">
      <input type="text" name="q" placeholder="Cari nama, NIM, atau prodi..." value="<?php echo htmlspecialchars($searchTerm); ?>">
      <button type="submit">Cari</button>
      <?php if ($searchTerm !== ''): ?>
        <a class="reset-search" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">Reset</a>
      <?php endif; ?>
    </form>

    <?php if (!$errorMessage && empty($records)): ?>
      <div class="empty-state">
        <?php if ($searchTerm !== ''): ?>
          <h2>Tidak ada hasil.</h2>
          <p>Tidak ditemukan data dengan kata kunci "<?php echo htmlspecialchars($searchTerm); ?>".</p>
        <?php else: ?>
          <h2>Belum ada data.</h2>
          <p>Silakan tambahkan data mahasiswa terlebih dahulu.</p>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Program Studi</th>
            <?php if ($hasFotoColumn): ?>
              <th>Foto</th>
            <?php endif; ?>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['nama']); ?></td>
              <td><?php echo htmlspecialchars($row['nim']); ?></td>
              <td><?php echo htmlspecialchars($row['prodi']); ?></td>
              <?php if ($hasFotoColumn): ?>
                <td class="foto-cell">
                  <?php if (!empty($row['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto <?php echo htmlspecialchars($row['nama']); ?>">
                  <?php else: ?>
                    <span class="muted">Tidak ada foto</span>
                  <?php endif; ?>
                </td>
              <?php endif; ?>
              <td class="actions-cell">
                <a class="badge-btn edit" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo urlencode($row['id']); ?><?php echo $searchTerm !== '' ? '&amp;q=' . urlencode($searchTerm) : ''; ?>">Edit</a>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                  <?php if ($searchTerm !== ''): ?>
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($searchTerm); ?>">
                  <?php endif; ?>
                  <button type="submit" class="badge-btn delete">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="actions">
      <a href="form-input.php">Tambah Data</a>
      <a class="secondary" href="index.php">Kembali ke Viewer</a>
    </div>

    <?php if ($editingRecord): ?>
      <div class="edit-card">
        <h2>Edit Data Mahasiswa</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($editingRecord['id']); ?>">
          <?php if ($searchTerm !== ''): ?>
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($searchTerm); ?>">
          <?php endif; ?>

          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" required value="<?php echo htmlspecialchars($editingRecord['nama']); ?>">

          <label for="nim">NIM</label>
          <input type="text" id="nim" name="nim" required value="<?php echo htmlspecialchars($editingRecord['nim']); ?>">

          <label for="prodi">Program Studi</label>
          <input type="text" id="prodi" name="prodi" required value="<?php echo htmlspecialchars($editingRecord['prodi']); ?>">

          <button type="submit">Simpan Perubahan</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>
