<?php
require 'config/database.php';
require 'classes/mahasiswa.php';

$db = (new Database())->getConnection();
$mahasiswa = new Mahasiswa($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mahasiswa->create($_POST['nim'], $_POST['nama'], $_POST['alamat'], $_POST['kota']);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa | OOP PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <header class="header">
            <div>
                <p class="eyebrow">Formulir</p>
                <h1>Tambah Mahasiswa</h1>
                <p class="lead">Lengkapi data mahasiswa baru dengan rapi.</p>
            </div>
            <div class="header-actions">
                <a class="btn ghost" href="index.php">Kembali</a>
            </div>
        </header>

        <form class="form" method="post">
            <div class="field">
                <label for="nim">NIM</label>
                <input id="nim" name="nim" type="text" placeholder="Contoh: 23123456" required>
            </div>
            <div class="field">
                <label for="nama">Nama</label>
                <input id="nama" name="nama" type="text" placeholder="Nama lengkap" required>
            </div>
            <div class="field">
                <label for="alamat">Alamat</label>
                <input id="alamat" name="alamat" type="text" placeholder="Alamat domisili" required>
            </div>
            <div class="field">
                <label for="kota">Kota</label>
                <input id="kota" name="kota" type="text" placeholder="Kota asal" required>
            </div>
            <p class="subdued">Pastikan semua kolom terisi sebelum menyimpan.</p>
            <div class="form-actions">
                <a class="btn ghost" href="index.php">Batal</a>
                <button class="btn" type="submit">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
