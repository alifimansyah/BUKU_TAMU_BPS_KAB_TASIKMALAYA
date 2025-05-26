<?php
session_start();
include 'config/koneksi.php';

// Jika ingin update status user di database menjadi false (0)
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    mysqli_query($koneksi, "UPDATE tb_operator SET status=0 WHERE username='$username'");
}

session_unset();
session_destroy();
header('Location: login.php');
exit();
?>