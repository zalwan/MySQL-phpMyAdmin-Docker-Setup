/* 1. Membuat database perpustakaan_NIM */ 
CREATE DATABASE IF NOT EXISTS perpustakaan_13_067;
USE perpustakaan_13_067;

/* 2. Membuat tabel */
-- 2.a Tabel anggota
CREATE TABLE
  anggota (
    id_anggota INT PRIMARY KEY,
    nama VARCHAR(100),
    alamat TEXT,
    tgl_daftar DATE
  );

-- 2.b Tabel buku
CREATE TABLE
  buku (
    id_buku INT PRIMARY KEY,
    judul VARCHAR(100),
    pengarang VARCHAR(100),
    tahun_terbit YEAR
  );

-- 2.c Tabel petugas
CREATE TABLE
  petugas (
    id_petugas INT PRIMARY KEY,
    nama_petugas VARCHAR(100),
    shift VARCHAR(20)
  );

-- 2.d Tabel peminjaman
CREATE TABLE
  peminjaman (
    id_pinjam INT PRIMARY KEY,
    id_buku INT,
    id_anggota INT,
    id_petugas INT,
    tgl_pinjam DATE,
    tgl_kembali DATE,
    FOREIGN KEY (id_buku) REFERENCES buku (id_buku),
    FOREIGN KEY (id_anggota) REFERENCES anggota (id_anggota),
    FOREIGN KEY (id_petugas) REFERENCES petugas (id_petugas)
  );

/* 3. Insert data ke dalam tabel */
-- 3.a Data anggota
INSERT INTO
  anggota
VALUES
  (1, 'Rizal', 'Tangerang', '2025-01-13'),
  (2, 'Rivaldi', 'Lampung', '2025-03-13');

-- 3.b Data buku
INSERT INTO
  buku
VALUES
  (1, 'Dasar SQL', 'Andi', 2020),
  (2, 'Panduan Laravel', 'Budi', 2021),
  (3, 'Algoritma & Struktur', 'Citra', 2019);

-- 3.c Data petugas
INSERT INTO
  petugas
VALUES
  (1, 'Dedi', 'Pagi'),
  (2, 'Siska', 'Siang');

-- 3.d Data peminjaman
INSERT INTO
  peminjaman
VALUES
  (101, 1, 1, 2, '2023-05-01', '2023-05-07'),
  (102, 2, 2, 1, '2023-06-10', NULL);

/* 4. Menampilkan nama anggota, judul buku, dan tanggal peminjaman */
SELECT
  a.nama AS nama_anggota,
  b.judul AS judul_buku,
  p.tgl_pinjam
FROM
  peminjaman p
  JOIN anggota a ON p.id_anggota = a.id_anggota
  JOIN buku b ON p.id_buku = b.id_buku;

/* 5. Menampilkan semua data anggota termasuk yang belum pernah meminjam */
SELECT
  a.id_anggota,
  a.nama,
  p.id_pinjam
FROM
  anggota a
  LEFT JOIN peminjaman p ON a.id_anggota = p.id_anggota;

/* 6. Menampilkan buku yang sudah dikembalikan */
SELECT
  b.judul,
  a.nama AS nama_anggota,
  p.tgl_kembali
FROM
  peminjaman p
  JOIN buku b ON p.id_buku = b.id_buku
  JOIN anggota a ON p.id_anggota = a.id_anggota
WHERE
  p.tgl_kembali IS NOT NULL;

/* 7. Menampilkan nama petugas dan jumlah transaksi peminjaman */
SELECT
  pt.nama_petugas,
  COUNT(p.id_pinjam) AS jumlah_peminjaman
FROM
  petugas pt
  LEFT JOIN peminjaman p ON pt.id_petugas = p.id_petugas
GROUP BY
  pt.nama_petugas;
  
/* 8. Menampilkan judul buku yang belum pernah dipinjam */
SELECT
  b.id_buku,
  b.judul
FROM
  buku b
  LEFT JOIN peminjaman p ON b.id_buku = p.id_buku
WHERE
  p.id_buku IS NULL;