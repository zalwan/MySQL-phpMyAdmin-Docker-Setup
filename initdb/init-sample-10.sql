USE perpustakaan067;

-- Tambahkan gelar depan untuk menandai profesor (dibutuhkan oleh contoh view)
ALTER TABLE pengarang
  ADD COLUMN IF NOT EXISTS gelar_depan VARCHAR(20) AFTER nama;

-- Lengkapi data gelar untuk pengarang awal
UPDATE pengarang
SET
  gelar_depan = 'Prof.'
WHERE
  idpengarang IN (1, 3);

UPDATE pengarang
SET
  gelar_depan = 'Dr.'
WHERE
  idpengarang = 2;

-- Bangun ulang view untuk buku yang dikarang profesor
DROP VIEW IF EXISTS viewbuku_prof;

CREATE VIEW viewbuku_prof AS
SELECT
  idbuku,
  judul,
  tahun,
  stok,
  penerbit
FROM
  buku
  NATURAL JOIN penerbit
  NATURAL JOIN pengarang
  NATURAL JOIN buku_pengarang
WHERE
  gelar_depan LIKE '%prof%';

-- Bangun ulang view untuk buku yang tidak dikarang profesor
DROP VIEW IF EXISTS viewbuku_notprof;

CREATE VIEW viewbuku_notprof AS
SELECT
  idbuku,
  judul,
  tahun,
  stok,
  penerbit
FROM
  buku
  NATURAL JOIN penerbit
WHERE
  idbuku NOT IN (
    SELECT
      idbuku
    FROM
      viewbuku_prof
  );

-- Contoh penambahan buku oleh seorang profesor (akan otomatis muncul di view)
INSERT INTO
  buku (judul, tahun, stok, idpenerbit)
VALUES
  ('Fuzzy MADM', 2012, 32, 2);

SET @buku_prof_id = LAST_INSERT_ID();

INSERT INTO
  buku_pengarang (id, idbuku, idpengarang)
VALUES
  (CONCAT('BP_', @buku_prof_id, '_', 3), @buku_prof_id, 3);

-- Cek isi view
SELECT
  *
FROM
  viewbuku_prof;

SELECT
  *
FROM
  viewbuku_notprof;

-- Penghapusan view jika tidak diperlukan lagi:
DROP VIEW viewbuku_notprof;
DROP VIEW viewbuku_prof;
