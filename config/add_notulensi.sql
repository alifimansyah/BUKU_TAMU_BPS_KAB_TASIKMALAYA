-- Menambahkan kolom pembahasan dan catatan_lain ke tabel tamu_umum
USE db_bukutamu;

-- Tambah kolom pembahasan
ALTER TABLE tamu_umum 
ADD COLUMN pembahasan TEXT NULL 
COMMENT 'Pembahasan detail kunjungan tamu';

-- Tambah kolom catatan_lain
ALTER TABLE tamu_umum 
ADD COLUMN catatan_lain TEXT NULL 
COMMENT 'Catatan tambahan dari petugas';

ALTER TABLE tamu_umum 
MODIFY COLUMN `kesan pelayanan` varchar(150) NULL;


-- Verifikasi perubahan struktur tabel
DESC tamu_umum;

-- Tampilkan struktur tabel lengkap untuk konfirmasi
SHOW CREATE TABLE tamu_umum;