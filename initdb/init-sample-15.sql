-- =====================================================
-- NAMA : RIZAL SURYAWAN
-- NIM  : 241011750067
-- MATKUL : PERANCANGAN BASIS DATA 2
-- =====================================================

/* ===============================
   NO 1. DATABASE & TABLE
   =============================== */

DROP DATABASE IF EXISTS perpustakaan_067;
CREATE DATABASE perpustakaan_067;
USE perpustakaan_067;

-- TABLE MASTER
CREATE TABLE penerbit (
    idpenerbit INT AUTO_INCREMENT PRIMARY KEY,
    penerbit VARCHAR(50),
    kota VARCHAR(20),
    alamat VARCHAR(150)
);

CREATE TABLE pengarang (
    idpengarang INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    gelar_depan VARCHAR(10),
    gelar_belakang VARCHAR(20),
    foto BLOB,
    instansi VARCHAR(30)
);

CREATE TABLE petugas (
    idpetugas INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    no_hp CHAR(15),
    alamat VARCHAR(100)
);

CREATE TABLE buku (
    idbuku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(50),
    tahun INT(4),
    stok INT,
    idpenerbit INT,
    FOREIGN KEY (idpenerbit) REFERENCES penerbit(idpenerbit)
);

-- TABLE RELASI
CREATE TABLE buku_pengarang (
    idbp VARCHAR(12) PRIMARY KEY,
    idbuku INT,
    idpengarang INT,
    FOREIGN KEY (idbuku) REFERENCES buku(idbuku),
    FOREIGN KEY (idpengarang) REFERENCES pengarang(idpengarang)
);

CREATE TABLE kelola_penerbit (
    idkelola INT AUTO_INCREMENT PRIMARY KEY,
    idbuku INT,
    idpetugas INT,
    idpenerbit INT,
    jnskelola VARCHAR(100),
    waktu TIME,
    tanggal DATE
);

/* ===============================
   NO 2. TRIGGER INSERT & UPDATE
   =============================== */

CREATE TRIGGER trg_insert_penerbit
AFTER INSERT ON penerbit
FOR EACH ROW
BEGIN
    INSERT INTO kelola_penerbit
    (idbuku, idpetugas, idpenerbit, jnskelola, waktu, tanggal)
    VALUES (NULL, NULL, NEW.idpenerbit, 'INSERT', CURTIME(), CURDATE());
END;

CREATE TRIGGER trg_update_penerbit
AFTER UPDATE ON penerbit
FOR EACH ROW
BEGIN
    INSERT INTO kelola_penerbit
    (idbuku, idpetugas, idpenerbit, jnskelola, waktu, tanggal)
    VALUES (NULL, NULL, NEW.idpenerbit, 'UPDATE', CURTIME(), CURDATE());
END;

/* ===============================
   NO 3. INSERT 5 DATA PENERBIT
   =============================== */

INSERT INTO penerbit (penerbit, kota, alamat) VALUES
('Gramedia', 'Jakarta', 'Jl. Palmerah'),
('Erlangga', 'Jakarta', 'Jl. Baping'),
('Informatika', 'Bandung', 'Jl. Setiabudi'),
('Andi Offset', 'Yogyakarta', 'Jl. Beo'),
('Deepublish', 'Yogyakarta', 'Jl. Elang');

/* DATA PENDUKUNG AGAR JOIN TAMPIL */

INSERT INTO pengarang (nama, gelar_depan, gelar_belakang, instansi) VALUES
('Ahmad Fauzi', 'Dr.', 'M.Kom', 'UNPAM'),
('Budi Santoso', '', 'M.T', 'UI');

INSERT INTO petugas (nama, no_hp, alamat) VALUES
('Siti Aminah', '08123456789', 'Tangerang'),
('Rudi Hartono', '08234567890', 'Serpong');

INSERT INTO buku (judul, tahun, stok, idpenerbit) VALUES
('Basis Data', 2023, 10, 1),
('Sistem Informasi', 2022, 5, 2);

INSERT INTO buku_pengarang VALUES
('BP01', 1, 1),
('BP02', 2, 2);

UPDATE kelola_penerbit SET idbuku = 1, idpetugas = 1 WHERE idkelola = 1;
UPDATE kelola_penerbit SET idbuku = 2, idpetugas = 2 WHERE idkelola = 2;

/* ===============================
   NO 4. QUERY JOIN (TAMPILAN DATA)
   =============================== */

SELECT
    pg.nama AS nama_pengarang,
    kp.tanggal AS tanggal_insert_pengarang,
    b.judul AS judul_buku,
    p.penerbit AS nama_penerbit,
    kp.tanggal AS tanggal_insert_penerbit,
    pt.nama AS nama_petugas
FROM buku b
JOIN buku_pengarang bp ON b.idbuku = bp.idbuku
JOIN pengarang pg ON bp.idpengarang = pg.idpengarang
JOIN penerbit p ON b.idpenerbit = p.idpenerbit
JOIN kelola_penerbit kp ON p.idpenerbit = kp.idpenerbit
JOIN petugas pt ON kp.idpetugas = pt.idpetugas;

/* ===============================
   NO 5. VIEW info_buku
   =============================== */

CREATE VIEW info_buku AS
SELECT
    pg.nama AS nama_pengarang,
    kp.tanggal AS tanggal_insert_pengarang,
    b.judul AS judul_buku,
    p.penerbit AS nama_penerbit,
    kp.tanggal AS tanggal_insert_penerbit,
    pt.nama AS nama_petugas
FROM buku b
JOIN buku_pengarang bp ON b.idbuku = bp.idbuku
JOIN pengarang pg ON bp.idpengarang = pg.idpengarang
JOIN penerbit p ON b.idpenerbit = p.idpenerbit
JOIN kelola_penerbit kp ON p.idpenerbit = kp.idpenerbit
JOIN petugas pt ON kp.idpetugas = pt.idpetugas;