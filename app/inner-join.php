<?php
// Konfigurasi koneksi
$host = 'mysql';
$username = 'root';
$password = 'root';
$database = 'pemweb_db';

// Koneksi ke MySQL
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

// Query JOIN
$sql = "SELECT t_mahasiswa.id_mhs, t_mahasiswa.nama_mhs, t_prodi.nama_prodi 
        FROM t_mahasiswa
        INNER JOIN t_prodi ON t_mahasiswa.id_prodi = t_prodi.id_prodi";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>INNER JOIN PHP</title>
</head>

<body>
  <h2>Data Mahasiswa & Program Studi (INNER JOIN)</h2>

  <table border='1' cellpadding='8'>
    <tr>
      <th>ID Mahasiswa</th>
      <th>Nama Mahasiswa</th>
      <th>Program Studi</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                        <td>{$row['id_mhs']}</td>
                        <td>{$row['nama_mhs']}</td>
                        <td>{$row['nama_prodi']}</td>
                      </tr>";
      }
    } else {
      echo "<tr><td colspan='3'>Data tidak ditemukan</td></tr>";
    }
    ?>
  </table>

</body>

</html>