USE perpustakaan067;

-- Tabel petugas (admin/pengelola data)
CREATE TABLE
  IF NOT EXISTS petugas (
    idpetugas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    no_hp CHAR(15),
    alamat VARCHAR(100)
  );

-- Tabel kelola_pengarang (hubungan antara petugas dan pengarang)
CREATE TABLE
  IF NOT EXISTS kelola_pengarang (
    kdkelola INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idpetugas INT,
    idpengarang INT,
    jnskelola VARCHAR(20),
    waktu TIME,
    tanggal DATE,
    CONSTRAINT fk_kelola_petugas FOREIGN KEY (idpetugas) REFERENCES petugas (idpetugas) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_kelola_pengarang FOREIGN KEY (idpengarang) REFERENCES pengarang (idpengarang) ON UPDATE CASCADE ON DELETE CASCADE
  );

INSERT INTO
  petugas (nama, no_hp, alamat)
VALUES
  (
    'Moeljoko',
    '082254661234',
    'Jl. Abadi No 39B Pelaihari'
  ),
  (
    'Sari Amalia',
    '082211224321',
    'Jl. Perintis No 123A Pelaihari'
  );

INSERT INTO
  kelola_pengarang (idpetugas, idpengarang, jnskelola, waktu, tanggal)
VALUES
  (1, 1, 'insert', '08:20:05', '2017-05-01'),
  (1, 2, 'insert', '08:21:00', '2017-05-01'),
  (1, 3, 'insert', '08:21:45', '2017-05-01');

-- NATURAL JOIN
SELECT
  kdkelola,
  petugas.nama AS 'nama petugas',
  pengarang.nama AS 'nama pengarang',
  jnskelola AS 'jenis pengelolaan',
  tanggal,
  waktu
FROM
  petugas
  NATURAL JOIN kelola_pengarang
  NATURAL JOIN pengarang;

-- gunakan klausa WHERE atau INNER JOIN
SELECT
  kdkelola,
  petugas.nama AS 'nama petugas',
  pengarang.nama AS 'nama pengarang',
  jnskelola AS 'jenis pengelolaan',
  tanggal,
  waktu
FROM
  petugas,
  kelola_pengarang,
  pengarang
WHERE
  petugas.idpetugas = kelola_pengarang.idpetugas
  AND pengarang.idpengarang = kelola_pengarang.idpengarang;

-- LEFT JOIN
-- Menampilkan semua pengelolaan, beserta data petugas (jika ada)
SELECT
  kelola_pengarang.*,
  no_hp
FROM
  kelola_pengarang
  LEFT JOIN petugas ON kelola_pengarang.idpetugas = petugas.idpetugas;

-- Menampilkan semua petugas, termasuk yang belum mengelola data apapun
SELECT
  kelola_pengarang.*,
  no_hp
FROM
  petugas
  LEFT JOIN kelola_pengarang ON kelola_pengarang.idpetugas = petugas.idpetugas;

-- RIGHT JOIN
-- Menampilkan semua petugas dari tabel kanan, termasuk yang belum memiliki relasi
SELECT
  kelola_pengarang.*,
  no_hp
FROM
  kelola_pengarang
  RIGHT JOIN petugas ON kelola_pengarang.idpetugas = petugas.idpetugas;

-- INNER JOIN
-- Menampilkan hanya data yang berelasi antara petugas dan pengarang
SELECT
  kdkelola,
  petugas.nama AS 'nama petugas',
  pengarang.nama AS 'nama pengarang',
  jnskelola AS 'jenis pengelolaan',
  tanggal,
  waktu
FROM
  (
    kelola_pengarang
    INNER JOIN pengarang ON kelola_pengarang.idpengarang = pengarang.idpengarang
  )
  INNER JOIN petugas ON kelola_pengarang.idpetugas = petugas.idpetugas;

-- INNER JOIN dengan BUKU
-- Menampilkan pengelolaan pengarang beserta judul buku yang ditulis
SELECT
  kelola_pengarang.kdkelola,
  petugas.nama AS 'nama petugas',
  pengarang.nama AS 'nama pengarang',
  --   buku.judul AS 'buku',
  jnskelola AS 'jenis pengelaolan',
  tanggal,
  waktu
FROM
  kelola_pengarang
  INNER JOIN pengarang ON kelola_pengarang.idpengarang = pengarang.idpengarang
  INNER JOIN buku_pengarang ON pengarang.idpengarang = buku_pengarang.idpengarang
  INNER JOIN buku ON buku_pengarang.idbuku = buku.idbuku
  INNER JOIN petugas ON kelola_pengarang.idpetugas = petugas.idpetugas;