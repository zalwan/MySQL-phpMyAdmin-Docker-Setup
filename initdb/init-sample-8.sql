DROP DATABASE IF EXISTS perpustakaan067;

-- Buat database baru 
CREATE DATABASE perpustakaan067;

-- Gunakan database
USE perpustakaan067;

-- Tabel penerbit (tabel master, tanpa foreign key)
CREATE TABLE
  penerbit (
    idpenerbit INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    penerbit VARCHAR(50),
    kota VARCHAR(50),
    alamat TEXT
  );

-- Tabel pengarang (tabel master, tanpa foreign key)
CREATE TABLE
  pengarang (
    idpengarang INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50)
  );

-- Tabel buku (memiliki foreign key ke penerbit)
CREATE TABLE
  buku (
    idbuku INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(50),
    tahun INT (4),
    stok INT,
    idpenerbit INT,
    CONSTRAINT fk_idpenerbit FOREIGN KEY (idpenerbit) REFERENCES penerbit (idpenerbit) ON UPDATE CASCADE ON DELETE CASCADE
  );

-- Tabel buku_pengarang (relasi many-to-many antara buku dan pengarang)
CREATE TABLE
  buku_pengarang (
    id VARCHAR(12) NOT NULL PRIMARY KEY,
    idbuku INT,
    idpengarang INT,
    CONSTRAINT fk_idbuku FOREIGN KEY (idbuku) REFERENCES buku (idbuku) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_idpengarang FOREIGN KEY (idpengarang) REFERENCES pengarang (idpengarang) ON UPDATE CASCADE ON DELETE CASCADE
  );

-- Isi data ke tabel penerbit
INSERT INTO
  penerbit (penerbit, kota, alamat)
VALUES
  ('Erlangga', 'Jakarta', 'Jl. M.H Thamrin No 28A'),
  ('Andi', 'Yogyakarta', 'Jl. Kaliurang No 4A'),
  ('Informatika', 'Bandung', 'Jl. A. Yani No 32B');

-- Isi data ke tabel pengarang
INSERT INTO
  pengarang (nama)
VALUES
  ('Abdul Kadir'),
  ('Jogiyanto'),
  ('Sri Wahyuningsih');

-- Isi data ke tabel buku
INSERT INTO
  buku (judul, tahun, stok, idpenerbit)
VALUES
  ('Basis Data MySQL', 2014, 54, 2),
  ('Pemrograman Web', 2016, 30, 1),
  ('Algoritma dan Struktur Data', 2018, 25, 3);

-- Isi data ke tabel buku_pengarang
INSERT INTO
  buku_pengarang (id, idbuku, idpengarang)
VALUES
  ('BP_1_1', 1, 1),
  ('BP_2_2', 2, 2),
  ('BP_3_3', 3, 3);

-- 1. Menampilkan buku beserta nama penerbitnya
SELECT
  idbuku,
  judul,
  penerbit,
  tahun,
  stok
FROM
  buku,
  penerbit
WHERE
  buku.idpenerbit = penerbit.idpenerbit;

-- 2. Menampilkan judul buku dan nama pengarang
SELECT
  judul,
  nama AS 'nama pengarang'
FROM
  buku,
  pengarang,
  buku_pengarang
WHERE
  buku.idbuku = buku_pengarang.idbuku
  AND pengarang.idpengarang = buku_pengarang.idpengarang;