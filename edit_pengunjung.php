<?php
include 'config/koneksi.php';
$id = intval($_GET['id']);

// Ambil data berdasarkan No_Pengunjung
$result = mysqli_query($koneksi, "SELECT * FROM tamu_umum WHERE No_Pengunjung = $id");
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Amankan input
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_Tamu = mysqli_real_escape_string($koneksi, $_POST['jenis_Tamu']);
    $instansi = mysqli_real_escape_string($koneksi, $_POST['instansi']);
    $nama_instansi = mysqli_real_escape_string($koneksi, $_POST['nama_instansi']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $No_wa_Aktif = mysqli_real_escape_string($koneksi, $_POST['No_wa_Aktif']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

    // Query update
    $update = "UPDATE tamu_umum SET
        tanggal = '$tanggal',
        jenis_Tamu = '$jenis_Tamu',
        nama = '$nama',
        instansi = '$instansi',
        nama_instansi = '$nama_instansi',
        keperluan = '$keperluan',
        No_wa_Aktif = '$No_wa_Aktif',
        pesan = '$pesan'
        WHERE No_Pengunjung = $id";

    // Eksekusi query
    if (mysqli_query($koneksi, $update)) {
        header("Location: pengunjung.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Tamu</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Edit Data Tamu</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama:</label>
                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_Tamu" class="form-label">Jenis Tamu:</label>
                    <input type="text" class="form-control" name="jenis_Tamu" value="<?= htmlspecialchars($data['jenis_Tamu']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="instansi" class="form-label">Instansi:</label>
                    <input type="text" class="form-control" name="instansi" value="<?= htmlspecialchars($data['instansi']) ?>">
                </div>

                <div class="mb-3">
                    <label for="nama_instansi" class="form-label">Nama Instansi:</label>
                    <input type="text" class="form-control" name="nama_instansi" value="<?= htmlspecialchars($data['nama_instansi']) ?>">
                </div>

                <div class="mb-3">
                    <label for="keperluan" class="form-label">Keperluan:</label>
                    <input type="text" class="form-control" name="keperluan" value="<?= htmlspecialchars($data['keperluan']) ?>">
                </div>

                <div class="mb-3">
                    <label for="No_wa_Aktif" class="form-label">No. WA Aktif:</label>
                    <input type="text" class="form-control" name="No_wa_Aktif" value="<?= htmlspecialchars($data['No_wa_Aktif']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="pesan" class="form-label">Pesan:</label>
                    <input type="text" class="form-control" name="pesan" value="<?= htmlspecialchars($data['pesan']) ?>">
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal:</label>
                    <input type="date" class="form-control" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="pengunjung.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
