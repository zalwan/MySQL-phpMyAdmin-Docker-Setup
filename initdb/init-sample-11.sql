-- A) Buat database Routine 
CREATE DATABASE Routine;
USE Routine;

-- B) Stored function fungsi1
CREATE FUNCTION fungsi1 (a SMALLINT)
RETURNS INT 
DETERMINISTIC 
NO SQL
RETURN a + a;

SELECT fungsi1(60);

-- C) Stored function fungsi2
CREATE FUNCTION fungsi2(kar CHAR(50))
RETURNS INT
DETERMINISTIC
NO SQL
RETURN LENGTH(kar);

SELECT fungsi2('MySQL');

-- D) Stored function fungsi3
CREATE FUNCTION fungsi3(a SMALLINT)
RETURNS INT
DETERMINISTIC
NO SQL
RETURN a * a;

SELECT fungsi3(10);

-- E) Show create fungsi1
SHOW CREATE FUNCTION fungsi1;

-- F) Show create fungsi2 dan fungsi3
SHOW CREATE FUNCTION fungsi2;
SHOW CREATE FUNCTION fungsi3;

-- Kseluruhan function 
SHOW FUNCTION STATUS WHERE Db = 'Routine';

-- G) Hapus fungsi3
DROP FUNCTION fungsi3;

-- H) Database sekolah + tabel siswa
CREATE DATABASE sekolah;
USE sekolah;

CREATE TABLE siswa(
    nis VARCHAR(15) PRIMARY KEY,
    nama CHAR(20),
    angkatan VARCHAR(30)
);

-- I) Insert data siswa
INSERT INTO siswa VALUES ('11234','ana','2008/2009');
INSERT INTO siswa VALUES ('11235','bayu','2009/2010');
INSERT INTO siswa VALUES ('11236','canda','2010/2011');
INSERT INTO siswa VALUES ('11237','dirga','2012/2013');
INSERT INTO siswa VALUES ('11238','endang','2013/2014');

-- J) Buat procedure jumlahsiswa
CREATE PROCEDURE jumlahsiswa(OUT parameter1 INT)
BEGIN
    SELECT COUNT(*) INTO parameter1 FROM siswa;
END;

-- K) Pemanggilan procedure jumlahsiswa
CALL jumlahsiswa(@a);
SELECT @a;

-- L) Database toko + tabel barang
CREATE DATABASE toko;
USE toko;

CREATE TABLE barang(
    NamaBarang VARCHAR(50),
    Satuan VARCHAR(20),
    Harga INT,
    Jumlah INT
);

INSERT INTO barang VALUES ('Sabun', 'Bungkus', 3000, 20);
INSERT INTO barang VALUES ('Sikat gigi', 'Bungkus', 4000, 50);
INSERT INTO barang VALUES ('Sampho', 'Botol', 10000, 30);
INSERT INTO barang VALUES ('Kopi', 'Kg', 30000, 20);
INSERT INTO barang VALUES ('Teh', 'Bungkus', 5000, 40);
INSERT INTO barang VALUES ('Beras', 'Kg', 10000, 10);

-- L) Procedure jumlahbarang
CREATE PROCEDURE jumlahbarang(OUT total INT)
BEGIN
    SELECT COUNT(*) INTO total FROM barang;
END;

-- L) Call procedure jumlahbarang
CALL jumlahbarang(@x);
SELECT @x;
