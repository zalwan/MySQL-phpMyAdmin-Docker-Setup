<?php
class Mahasiswa {
    private $conn;
    private $table = "mahasiswa";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY nim DESC");
        $stmt->execute();
        return $stmt;
    }

    public function create($nim, $nama, $alamat, $kota) {
        $stmt = $this->conn->prepare(
            "INSERT INTO $this->table (nim, nama, alamat, kota) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$nim, $nama, $alamat, $kota]);
    }

    public function getById($nim) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE nim = ?");
        $stmt->execute([$nim]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>