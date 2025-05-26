<?php
session_start();
include 'config/koneksi.php';

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    $_SESSION['error'] = "ID pengunjung tidak valid";
    header("Location: table_pengunjung_petugas.php");
    exit;
}

// Ambil data berdasarkan No_Pengunjung dengan prepared statement
$query = "SELECT * FROM tamu_umum WHERE No_Pengunjung = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$data) {
    $_SESSION['error'] = "Data tidak ditemukan";
    header("Location: table_pengunjung_petugas.php");
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    $required_fields = ['nama', 'jenis_Tamu', 'No_wa_Aktif', 'tanggal'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error'] = "Field " . ucfirst($field) . " harus diisi";
            header("Location: edit_pengunjung.php?id=$id");
            exit;
        }
    }

    // Update data dengan prepared statement
    $query = "UPDATE tamu_umum SET 
        tanggal = ?, 
        jenis_Tamu = ?, 
        nama = ?, 
        instansi = ?, 
        nama_instansi = ?, 
        keperluan = ?, 
        No_wa_Aktif = ?, 
        keterangan_keperluan = ?,
        `kesan pelayanan` = ?
        WHERE No_Pengunjung = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssi", 
        $_POST['tanggal'],
        $_POST['jenis_Tamu'],
        $_POST['nama'],
        $_POST['instansi'],
        $_POST['nama_instansi'],
        $_POST['keperluan'],
        $_POST['No_wa_Aktif'],
        $_POST['keterangan_keperluan'],
        $_POST['kesan_pelayanan'],
        $id
    );

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data pengunjung berhasil diperbarui";
        header("Location: table_pengunjung_petugas.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($koneksi);
        header("Location: edit_pengunjung.php?id=$id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pengunjung - BPS Tasikmalaya</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-header {
            background-color: #1a237e;
            color: white;
        }
        .form-label {
            font-weight: 500;
        }
        .radio-group label {
            margin-right: 15px;
            cursor: pointer;
        }
        .btn-bps {
            background-color: #1a237e;
            color: white;
        }
        .btn-bps:hover {
            background-color: #303f9f;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <div class="card shadow">
        <div class="card-header">
            <h4><i class="fas fa-user-edit me-2"></i>Edit Data Pengunjung</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal Kunjungan</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_Tamu" class="form-label">Jenis Tamu</label>
                        <select class="form-select" name="jenis_Tamu" required>
                            <option value="Umum" <?= $data['Jenis_Tamu'] == 'Umum' ? 'selected' : '' ?>>Tamu Umum</option>
                            <option value="Instansi" <?= $data['Jenis_Tamu'] == 'Instansi' ? 'selected' : '' ?>>Tamu PST</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="instansi" class="form-label">Jenis Instansi</label>
                        <select class="form-select" name="instansi">
                            <option value="">Pilih Jenis Instansi</option>
                            <option value="Pemerintah" <?= $data['instansi'] == 'Pemerintah' ? 'selected' : '' ?>>Instansi/Dinas/Lembaga Pemerintah</option>
                            <option value="Swasta" <?= $data['instansi'] == 'Swasta' ? 'selected' : '' ?>>Perusahaan Swasta</option>
                            <option value="Pendidikan" <?= $data['instansi'] == 'Pendidikan' ? 'selected' : '' ?>>Pelajar/Mahasiswa</option>
                            <option value="Perorangan" <?= $data['instansi'] == 'Perorangan' ? 'selected' : '' ?>>Masyarakat Umum</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama_instansi" class="form-label">Nama Instansi</label>
                        <input type="text" class="form-control" name="nama_instansi" value="<?= htmlspecialchars($data['nama_instansi']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="keperluan" class="form-label">Keperluan</label>
                        <select class="form-select" name="keperluan">
                            <option value="">Pilih Keperluan</option>
                            <option value="Konsultasi Statistik" <?= $data['keperluan'] == 'Konsultasi Statistik' ? 'selected' : '' ?>>Konsultasi Data Statistik</option>
                            <option value="Perpustakaan" <?= $data['keperluan'] == 'Perpustakaan' ? 'selected' : '' ?>>Layanan Perpustakaan</option>
                            <option value="Rekomendasi kegiatan statistik" <?= $data['keperluan'] == 'Rekomendasi kegiatan statistik' ? 'selected' : '' ?>>Pelayanan Rekomendasi Kegiatan Statistik</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="keterangan_keperluan" class="form-label">Keterangan Keperluan</label>
                        <input type="text" class="form-control" name="keterangan_keperluan" value="<?= htmlspecialchars($data['keterangan_keperluan']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="No_wa_Aktif" class="form-label">No. WhatsApp Aktif</label>
                        <input type="text" class="form-control" name="No_wa_Aktif" value="<?= htmlspecialchars($data['No_wa_Aktif']) ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kesan Pelayanan</label>
                        <div class="radio-group">
                            <?php 
                            $kesan_options = [
                                '1 😞 Buruk',
                                '2 😐 Kurang Baik',
                                '3 🙂 Cukup',
                                '4 😊 Baik',
                                '5 😍 Baik Sekali'
                            ];
                            
                            foreach ($kesan_options as $option) {
                                $checked = ($data['kesan pelayanan'] == $option) ? 'checked' : '';
                                echo "<label><input type='radio' name='kesan_pelayanan' value='$option' $checked required> $option</label>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="table_pengunjung_petugas.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-bps">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
