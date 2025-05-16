<?php
// Konfigurasi database
$host = 'localhost'; // Host database
$user = 'root';            // Username database
$pass = '';         // Password database
$db   = 'db_bukutamu'; // Nama database

// Membuat koneksi ke database
$koneksi = @new mysqli($host, $user, $pass, $db);

// Mengecek koneksi
if ($koneksi->connect_error) {
    // Tampilkan pesan error dengan penjelasan tambahan
    die("Koneksi ke database gagal: Periksa kembali nama host, username, password, atau nama database. Error: " . $koneksi->connect_error);
}

?>
