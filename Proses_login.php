<?php
session_start();
include 'config/koneksi.php'; // Harus sesuai dan variabelnya harus $koneksi

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Gunakan md5 hanya jika database memang menyimpan md5
    $password_hash = md5($password);

    $query = mysqli_query($koneksi, "SELECT * FROM tb_operator WHERE username='$username' AND password='$password_hash'");

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['Status'] = true;

        mysqli_query($koneksi, "UPDATE tb_operator SET Status=1 WHERE username='$username'");

        header('Location: table pengunjung petugas.php');
        exit();
    } else {
        header('Location: login.php?error=Username atau Password salah!');
        exit();
    }
} else {
    header('Location: login.php?error=Data tidak valid!');
    exit();
}
?>
