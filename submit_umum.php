<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal       = $_POST['tanggal'];
    $jenis_Tamu    = $_POST['Jenis_Tamu'];
    $nama          = strtoupper(trim($_POST['nama']));
    $instansi      = $_POST['instansi'];
    $nama_instansi = strtoupper(trim($_POST['nama_instansi']));
    $keperluan     = strtoupper(trim($_POST['keperluan']));
    $no_wa         = preg_replace('/[^0-9]/', '', $_POST['No_wa_Aktif']);
    $kesan_pelayanan = $_POST['kesan_pelayanan']; // ini yang benar

    // Validasi sederhana
    if (!$tanggal || !$jenis_Tamu || !$nama || !$instansi || !$nama_instansi || !$keperluan || !$no_wa) {
        echo "<script>alert('Semua kolom wajib diisi!'); history.back();</script>";
        exit();
    }

    $query = "INSERT INTO tamu_umum (
                tanggal, Jenis_Tamu, nama, instansi, 
                nama_instansi, keperluan, No_wa_Aktif, `kesan pelayanan`
              ) VALUES (
                '$tanggal', '$jenis_Tamu', '$nama', '$instansi', 
                '$nama_instansi', '$keperluan', '$no_wa', '$kesan_pelayanan'
              )";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Terima kasih, data berhasil disimpan.');
                window.location.href = 'index.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menyimpan data: " . mysqli_error($koneksi) . "');
                history.back();
              </script>";
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
