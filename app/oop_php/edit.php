<?php
require 'config/database.php';
require 'classes/mahasiswa.php';

$db = (new Database())->getConnection();
$mahasiswa = new Mahasiswa($db);

$error = '';
$data = null;
$nim = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $kota = trim($_POST['kota'] ?? '');

    if ($nim === '') {
        $error = 'NIM tidak ditemukan.';
    } else {
        $updated = $mahasiswa->update($nim, $nama, $alamat, $kota);
        if ($updated) {
            header("Location: index.php");
            exit;
        }
        $error = 'Gagal memperbarui data.';
    }

    $data = [
        'nim' => $nim,
        'nama' => $nama,
        'alamat' => $alamat,
        'kota' => $kota,
    ];
} else {
    $nim = trim($_GET['nim'] ?? '');
    if ($nim === '') {
        $error = 'NIM tidak ditemukan.';
    } else {
        $data = $mahasiswa->getById($nim);
        if (!$data) {
            $error = 'Data mahasiswa tidak ditemukan.';
        }
    }
}

$showForm = is_array($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa | OOP PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <header class="header">
            <div>
                <p class="eyebrow">Formulir</p>
                <h1>Edit Mahasiswa</h1>
                <p class="lead">Perbarui data mahasiswa yang sudah tersimpan.</p>
            </div>
            <div class="header-actions">
                <a class="btn ghost" href="index.php">Kembali</a>
            </div>
        </header>

        <?php if ($error) : ?>
            <div class="notice error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($showForm) : ?>
            <form class="form" method="post">
                <div class="field">
                    <label for="nim">NIM</label>
                    <input id="nim" name="nim" type="text" value="<?= htmlspecialchars($data['nim'] ?? '', ENT_QUOTES, 'UTF-8') ?>" readonly required>
                </div>
                <div class="field">
                    <label for="nama">Nama</label>
                    <input id="nama" name="nama" type="text" value="<?= htmlspecialchars($data['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="field">
                    <label for="alamat">Alamat</label>
                    <input id="alamat" name="alamat" type="text" value="<?= htmlspecialchars($data['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="field">
                    <label for="kota">Kota</label>
                    <input id="kota" name="kota" type="text" value="<?= htmlspecialchars($data['kota'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <p class="subdued">Periksa ulang data sebelum menyimpan perubahan.</p>
                <div class="form-actions">
                    <a class="btn ghost" href="index.php">Batal</a>
                    <button class="btn" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        <?php else : ?>
            <div class="form-actions">
                <a class="btn ghost" href="index.php">Kembali</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
