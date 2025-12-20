<?php
require 'config/database.php';
require 'classes/mahasiswa.php';

$db = (new Database())->getConnection();
$mahasiswa = new Mahasiswa($db);

$nim = trim($_GET['nim'] ?? '');
if ($nim !== '') {
    $mahasiswa->delete($nim);
}

header("Location: index.php");
exit;
?>
