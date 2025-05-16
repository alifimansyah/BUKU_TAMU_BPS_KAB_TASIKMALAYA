<?php
include 'config/koneksi.php';

$id = intval($_GET['id']);
$query = "DELETE FROM tamu_umum WHERE No_pengunjung=$id";

if (mysqli_query($koneksi, $query)) {
    header("Location: table pengunjung.php");
} else {
    echo "Gagal menghapus data.";
}
?>
