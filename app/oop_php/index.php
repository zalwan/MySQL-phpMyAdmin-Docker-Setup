<?php
require 'config/database.php';
require 'classes/mahasiswa.php';

$db = (new Database())->getConnection();
$mahasiswa = new Mahasiswa($db);
$data = $mahasiswa->getAll()->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa | OOP PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="panel">
        <header class="header">
            <div>
                <p class="eyebrow">Database</p>
                <h1>Data Mahasiswa</h1>
                <p class="lead">Kelola data mahasiswa dengan tampilan yang lebih rapi.</p>
            </div>
            <div class="header-actions">
                <a class="btn" href="tambah.php">+ Tambah Mahasiswa</a>
            </div>
        </header>

        <div class="table-wrap">
            <div class="table-scroller">
                <table>
                    <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$data) : ?>
                        <tr>
                            <td class="empty" colspan="5">Belum ada data mahasiswa.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><span class="badge"><?= htmlspecialchars($row['nim'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['alamat'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['kota'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="link" href="edit.php?nim=<?= urlencode($row['nim']) ?>">Edit</a>
                                        <a class="link" href="hapus.php?nim=<?= urlencode($row['nim']) ?>"
                                           onclick="return confirm('Hapus data ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
