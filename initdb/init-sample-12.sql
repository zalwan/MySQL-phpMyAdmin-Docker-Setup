-- A) Membuat database rumahsakit & tabel pasien
CREATE DATABASE IF NOT EXISTS rumahsakit;
USE rumahsakit;

CREATE TABLE pasien (
    id_pasien VARCHAR(5) PRIMARY KEY,
    nama CHAR(20),
    alamat CHAR(20),
    no_antrian VARCHAR(5),
    tgl_masuk DATE
);

-- B) Insert data ke tabel pasien
INSERT INTO pasien VALUES ('a1','yahya','pondok cabe','a11','2014-05-21');
INSERT INTO pasien VALUES ('a2','yanto','cinere','a12','2014-05-23');
INSERT INTO pasien VALUES ('a4','wandi','ciputat','a14','2014-05-24');

SELECT * FROM pasien;

-- C) Membuat trigger untuk operasi INSERT

-- Membuat variabel penanda penambahan data
SET @jmlpenambahan = 0;

-- Trigger: menghitung berapa kali INSERT dilakukan
DROP TRIGGER IF EXISTS trigger1;
CREATE TRIGGER trigger1
BEFORE INSERT ON pasien
FOR EACH ROW
SET @jmlpenambahan = @jmlpenambahan + 1;

-- Tambahan data untuk menguji trigger
INSERT INTO pasien VALUES ('a3','lulu','cinere','a15','2014-05-24');
INSERT INTO pasien VALUES ('a5','sari','cilandak','a16','2014-05-25');
INSERT INTO pasien VALUES ('a6','bari','cinere','a16','2014-05-25');

-- Melihat hasil penambahan
SELECT @jmlpenambahan AS jumlah_insert;

-- D) Membuat database bioskop & tabel JadwalFilm
CREATE DATABASE IF NOT EXISTS bioskop;
USE bioskop;

CREATE TABLE JadwalFilm (
    Id_film VARCHAR(15) PRIMARY KEY,
    Judul CHAR(20),
    Waktu DATETIME
);

DESC JadwalFilm;

-- E) Insert data ke tabel JadwalFilm
INSERT INTO JadwalFilm VALUES ('D11','In Fear','2014-03-07 18:30:00');
INSERT INTO JadwalFilm VALUES ('H12','Haunt','2014-03-07 19:00:00');
INSERT INTO JadwalFilm VALUES ('C13','Bad Words','2014-03-07 19:30:00');
INSERT INTO JadwalFilm VALUES ('A14','Divergent','2014-03-07 20:00:00');
INSERT INTO JadwalFilm VALUES ('E15','Enemy','2014-03-07 20:30:00');

-- F) Membuat tabel kedua: stdio
CREATE TABLE stdio (
    kode_stdio VARCHAR(15) PRIMARY KEY,
    namaStdio CHAR(20),
    Id_film VARCHAR(10),
    judul CHAR(20)
);

DESC stdio;

-- G) Insert data ke tabel stdio
INSERT INTO stdio VALUES ('STD4','Stdio 4','E15','Enemy');
INSERT INTO stdio VALUES ('STD3','Stdio 3','D11','In Fear');
INSERT INTO stdio VALUES ('STD2','Stdio 2','C13','Bad Words');
INSERT INTO stdio VALUES ('STD5','Stdio 5','A14','Divergent');
INSERT INTO stdio VALUES ('STD1','Stdio 1','H12','Haunt');

-- H) Membuat VIEW tblview
DROP VIEW IF EXISTS tblview;

CREATE VIEW tblview AS
SELECT 
    JadwalFilm.judul,
    JadwalFilm.waktu,
    stdio.namaStdio
FROM JadwalFilm
JOIN stdio ON JadwalFilm.id_film = stdio.id_film;

-- Test view
SELECT * FROM tblview;

-- I) Menampilkan data view dengan WHERE
SELECT * FROM tblview
WHERE waktu >= '2014-03-07 19:00:00';