<?php
session_start();
include 'config/koneksi.php';

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    $_SESSION['error'] = "ID pengunjung tidak valid";
    header("Location: table pengunjung.php");
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
    header("Location: table pengunjung.php");
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

    // Update data dengan prepared statement (tanpa kesan pelayanan, pembahasan, catatan_lain)
    $query = "UPDATE tamu_umum SET 
        tanggal = ?, 
        jenis_Tamu = ?, 
        nama = ?, 
        instansi = ?, 
        nama_instansi = ?, 
        keperluan = ?, 
        No_wa_Aktif = ?, 
        keterangan_keperluan = ?,
        petugas = ?
        WHERE No_Pengunjung = ?";

    $petugas = $_SESSION['username'] ?? 'System';

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssi",
        $_POST['tanggal'],
        $_POST['jenis_Tamu'],
        $_POST['nama'],
        $_POST['instansi'],
        $_POST['nama_instansi'],
        $_POST['keperluan'],
        $_POST['No_wa_Aktif'],
        $_POST['keterangan_keperluan'],
        $petugas,
        $id
    );

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data pengunjung berhasil diperbarui";
        header("Location: table pengunjung.php");
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

        .btn-bps {
            background-color: #1a237e;
            color: white;
        }

        .btn-bps:hover {
            background-color: #303f9f;
            color: white;
        }

        .penilaian-info {
            background: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn-trigger {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-trigger:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header">
                <h4><i class="fas fa-user-edit me-2"></i>Edit Data Pengunjung</h4>
            </div>
            <div class="card-body">
                <!-- Info Penilaian -->
                <div class="penilaian-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Penilaian</h6>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="mb-0">Tamu dapat memberikan penilaian pelayanan melalui form khusus. Klik tombol di samping untuk memicu form penilaian.</p>
                            <?php if (!empty($data['kesan pelayanan'])): ?>
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Status: Sudah dinilai - <?= htmlspecialchars($data['kesan pelayanan']) ?>
                                </small>
                            <?php else: ?>
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    Status: Belum dinilai
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="form_penilaian.php?id=<?= $data['No_Pengunjung'] ?>"
                                target="_blank" class="btn btn-trigger">
                                <i class="fas fa-star me-2"></i>Trigger Penilaian
                            </a>
                        </div>
                    </div>
                </div>

                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal Kunjungan</label>
                            <input type="datetime-local" class="form-control" name="tanggal"
                                value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal'])) ?>" required>
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

                    <!-- Tampilkan data penilaian jika sudah ada (read-only) - hanya kesan pelayanan -->
                    <?php if (!empty($data['kesan pelayanan'])): ?>
                        <div class="card mb-3" style="background: rgba(40, 167, 69, 0.05);">
                            <div class="card-header" style="background: rgba(40, 167, 69, 0.1); color: #155724;">
                                <h6 class="mb-0"><i class="fas fa-star me-2"></i>Data Penilaian Tamu</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><strong>Kesan Pelayanan:</strong> <?= htmlspecialchars($data['kesan pelayanan']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between">
                        <a href="table pengunjung.php" class="btn btn-secondary">
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