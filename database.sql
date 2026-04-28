-- Database: db_sipandu

CREATE TABLE IF NOT EXISTS tb_bidang (
    id_bidang INT AUTO_INCREMENT PRIMARY KEY,
    nama_bidang VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_jenis (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_sumber (
    id_sumber INT AUTO_INCREMENT PRIMARY KEY,
    nama_sumber VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_status (
    id_status INT AUTO_INCREMENT PRIMARY KEY,
    nama_status VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_user VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'operator') NOT NULL,
    id_bidang INT DEFAULT NULL,
    FOREIGN KEY (id_bidang) REFERENCES tb_bidang(id_bidang) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS tb_pengaduan (
    id_pengaduan VARCHAR(30) PRIMARY KEY,
    nama_masyarakat VARCHAR(100) NOT NULL,
    nik_pelapor VARCHAR(20) DEFAULT NULL,
    nomor_hp VARCHAR(20) NOT NULL,
    alamat_lengkap TEXT NOT NULL,
    kecamatan VARCHAR(50) NOT NULL,
    kelurahan VARCHAR(50) NOT NULL,
    id_jenis INT NOT NULL,
    id_sumber INT NOT NULL,
    id_bidang INT NOT NULL,
    tanggal_jam_pengaduan DATETIME NOT NULL,
    uraian_pengaduan TEXT NOT NULL,
    id_status INT NOT NULL,
    uraian_penyelesaian TEXT DEFAULT NULL,
    tanggal_jam_selesai DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jenis) REFERENCES tb_jenis(id_jenis),
    FOREIGN KEY (id_sumber) REFERENCES tb_sumber(id_sumber),
    FOREIGN KEY (id_bidang) REFERENCES tb_bidang(id_bidang),
    FOREIGN KEY (id_status) REFERENCES tb_status(id_status)
);

-- Insert Default Data
INSERT INTO tb_bidang (nama_bidang) VALUES ('Pencatatan Sipil'), ('Pendaftaran Penduduk'), ('Informasi Administrasi Kependudukan');
INSERT INTO tb_jenis (nama_jenis) VALUES ('Pembuatan KTP-el'), ('Akta Kelahiran'), ('Kartu Keluarga'), ('Pindah Datang');
INSERT INTO tb_sumber (nama_sumber) VALUES ('WhatsApp'), ('Facebook'), ('Email'), ('Telepon'), ('Langsung');
INSERT INTO tb_status (nama_status) VALUES ('Menunggu'), ('Diproses'), ('Selesai'), ('Ditolak');

-- Default Admin (Password: admin123)
INSERT INTO tb_users (nama_user, username, password, role) 
VALUES ('Administrator', 'admin', '$2y$12$uJIK4hwqtlMuw/GLbiE84uL1209ZjUdEZjK83gK2y9XTQlC.mVAk6', 'admin');
