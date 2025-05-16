<?php
include 'config/koneksi.php'; // Pastikan file koneksi.php sudah benar

session_start();

// Pastikan username dan password tidak kosong
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Mengamankan input dari SQL Injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Enkripsi password menggunakan md5
    $password_hash = md5($password);
    
    // Cek login
    $query = mysqli_query($koneksi, "SELECT * FROM tb_operator WHERE username='$username' AND password='$password_hash'");

    if (mysqli_num_rows($query) > 0) {
        // Login sukses
        $_SESSION['username'] = $username;

        // Catat ke tabel log_login
        $log = mysqli_query($koneksi, "INSERT INTO log_login (username, waktu_login) VALUES ('$username', NOW())");

        if (!$log) {
            // Kalau gagal insert log, tampilkan error
            die('Gagal mencatat log login: ' . mysqli_error($koneksi));
        }

        header('Location: table pengunjung petugas.php'); // Ganti ke halaman dashboard kamu
        exit();
    } else {
        // Login gagal
        header('Location: login.php?error=Username atau Password salah!');
        exit();
    }
} else {
    // Jika username atau password tidak dikirimkan
    header('Location: login.php?error=Username atau Password tidak valid!');
    exit();
}
?>
