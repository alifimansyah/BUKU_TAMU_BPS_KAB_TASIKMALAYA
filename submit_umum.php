<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu sesuai
    $tanggal       = date('Y-m-d H:i:s');
    $jenis_Tamu    = $_POST['Jenis_Tamu'];
    $nama          = strtoupper(trim($_POST['nama']));
    $instansi      = $_POST['instansi'];
    $nama_instansi = strtoupper(trim($_POST['nama_instansi']));
    $keperluan     = strtoupper(trim($_POST['keperluan']));
    $keterangan_keperluan = strtoupper(trim($_POST['keterangan_keperluan']));
    $no_wa         = preg_replace('/[^0-9]/', '', $_POST['No_wa_Aktif']);
    $kesan_pelayanan = $_POST['kesan_pelayanan']; // ini yang benar

    // Ambil nama petugas (operator) yang sedang online
    $petugas = '';
    $result_petugas = mysqli_query($koneksi, "SELECT Nama FROM tb_operator WHERE status=1 LIMIT 1");
    if ($row_petugas = mysqli_fetch_assoc($result_petugas)) {
        $petugas = $row_petugas['Nama'];
    }

    // Validasi sederhana
    if (!$tanggal || !$jenis_Tamu || !$nama || !$instansi || !$nama_instansi || !$keperluan || !$keterangan_keperluan || !$no_wa) {
        echo "<script>alert('Semua kolom wajib diisi!'); history.back();</script>";
        exit();
    }

    $query = "INSERT INTO tamu_umum (
                tanggal, Jenis_Tamu, nama, instansi, 
                nama_instansi, keperluan, keterangan_keperluan, No_wa_Aktif, `kesan pelayanan`, petugas
              ) VALUES (
                '$tanggal', '$jenis_Tamu', '$nama', '$instansi', 
                '$nama_instansi', '$keperluan', '$keterangan_keperluan', '$no_wa', '$kesan_pelayanan', '$petugas'
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
