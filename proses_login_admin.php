<?php
include 'config/koneksi.php';
session_start();

// Pastikan form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $_SESSION['login_error'] = "Username dan password harus diisi!";
        $_SESSION['login_username'] = $_POST['username'] ?? '';
        header('Location: login_admin.php');
        exit();
    }

    // Sanitasi input
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $password_hash = md5($password); // sebaiknya pakai password_hash() di masa depan

    // Query ke database
    $query = mysqli_query($koneksi, "SELECT * FROM tb_operator WHERE username='$username' AND password='$password_hash'");
    
    if (mysqli_num_rows($query) > 0) {
        // Ambil data pengguna
        $data = mysqli_fetch_assoc($query);

        // Simpan data ke session
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'] ?? '';
        $_SESSION['role'] = $data['role'] ?? 'user';

        // Catat log login
        $log_query = "INSERT INTO log_login (username, waktu_login) VALUES ('{$data['username']}', NOW())";
        $log_result = mysqli_query($koneksi, $log_query);
        
        if (!$log_result) {
            $_SESSION['login_error'] = "Gagal mencatat log login. Silahkan coba lagi.";
            header('Location: login_admin.php');
            exit();
        }

        header('Location: laporan.php');
        exit();
    } else {
        // Login gagal
        $_SESSION['login_error'] = "Username atau password salah!";
        $_SESSION['login_username'] = $username;
        header('Location: login_admin.php');
        exit();
    }
} else {
    // Jika bukan method POST
    $_SESSION['login_error'] = "Metode request tidak valid!";
    header('Location: login_admin.php');
    exit();
}
?>
