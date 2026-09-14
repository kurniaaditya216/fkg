CREATE DATABASE room_fkg;
USE room_fkg;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('dosen', 'admin') NOT NULL DEFAULT 'dosen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_room VARCHAR(100) NOT NULL,
    kode_room VARCHAR(50) NOT NULL,
    gedung VARCHAR(100),
    lantai VARCHAR(20),
    kapasitas INT,
    fasilitas TEXT,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    nama_acara VARCHAR(150) NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    jumlah_peserta INT,
    keterangan TEXT,
    status ENUM('menunggu', 'disetujui', 'ditolak', 'dibatalkan')
        DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dosen') NOT NULL DEFAULT 'dosen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT id, nama, email, role
FROM users
WHERE role = 'dosen';