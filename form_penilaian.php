<?php
session_start();
include 'config/koneksi.php';

$error = '';
$success = '';
$data = null;

// Jika ada parameter id dari URL (dari QR code atau link)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Ambil data pengunjung berdasarkan No_Pengunjung
    $query = "SELECT * FROM tamu_umum WHERE No_Pengunjung = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
}

// Jika form pencarian disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cari_data'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);

    // Cari data berdasarkan nama saja - ambil yang terakhir
    $query = "SELECT * FROM tamu_umum WHERE nama LIKE ? ORDER BY tanggal DESC LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query);
    $nama_like = "%$nama%";
    mysqli_stmt_bind_param($stmt, "s", $nama_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        $error = "Data tidak ditemukan. Pastikan nama sesuai dengan data kunjungan Anda.";
    }
}

// Jika form penilaian disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_penilaian'])) {
    $no_pengunjung = intval($_POST['no_pengunjung']);
    $kesan_pelayanan = mysqli_real_escape_string($koneksi, $_POST['kesan_pelayanan']);

    // Update data penilaian
    $query = "UPDATE tamu_umum SET 
        `kesan pelayanan` = ?
        WHERE No_Pengunjung = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "si", $kesan_pelayanan, $no_pengunjung);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Terima kasih! Penilaian Anda telah berhasil disimpan.";
        // Refresh data
        $query_refresh = "SELECT * FROM tamu_umum WHERE No_Pengunjung = ?";
        $stmt_refresh = mysqli_prepare($koneksi, $query_refresh);
        mysqli_stmt_bind_param($stmt_refresh, "i", $no_pengunjung);
        mysqli_stmt_execute($stmt_refresh);
        $result_refresh = mysqli_stmt_get_result($stmt_refresh);
        $data = mysqli_fetch_assoc($result_refresh);
    } else {
        $error = "Gagal menyimpan penilaian. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penilaian Pelayanan - BPS Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bps-green: #0a9e3f;
            --bps-dark-green: #078008;
            --bps-gold: #f6be07;
            --bps-blue: #005bae;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--bps-green), var(--bps-dark-green));
            color: white;
            padding: 25px;
            text-align: center;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 30px;
        }

        .rating-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .rating-option {
            text-align: center;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            min-width: 120px;
        }

        .rating-option:hover {
            border-color: var(--bps-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .rating-option input[type="radio"] {
            display: none;
        }

        .rating-option input[type="radio"]:checked+.rating-content {
            color: var(--bps-green);
        }

        .rating-option input[type="radio"]:checked {
            border-color: var(--bps-green);
        }

        .rating-option.selected {
            border-color: var(--bps-green);
            background: rgba(10, 158, 63, 0.1);
        }

        .rating-emoji {
            font-size: 2rem;
            margin-bottom: 5px;
            display: block;
        }

        .rating-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-bps {
            background: linear-gradient(135deg, var(--bps-green), var(--bps-dark-green));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-bps:hover {
            background: linear-gradient(135deg, var(--bps-dark-green), var(--bps-green));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 158, 63, 0.3);
            color: white;
        }

        .data-info {
            background: rgba(10, 158, 63, 0.1);
            border-left: 4px solid var(--bps-green);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .search-form {
            background: rgba(0, 91, 174, 0.1);
            border-left: 4px solid var(--bps-blue);
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center mb-4">
            <img src="images/logo_BPS.png" alt="BPS Logo" style="height: 80px;">
            <h2 class="mt-3 text-dark">Form Penilaian Pelayanan</h2>
            <p class="text-muted">BPS Kabupaten Tasikmalaya</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-star me-2"></i>Penilaian Kepuasan Pelayanan</h3>
                <p class="mb-0">Berikan penilaian Anda terhadap pelayanan yang telah diberikan</p>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                        <div class="mt-3">
                            <a href="index.php" class="btn btn-success">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!$data && !$success): ?>
                    <!-- Form Pencarian Data -->
                    <div class="search-form">
                        <h5><i class="fas fa-search me-2"></i>Cari Data Kunjungan Anda</h5>
                        <p class="text-muted mb-3">Masukkan nama lengkap sesuai data kunjungan terbaru Anda</p>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" required
                                        placeholder="Masukkan nama lengkap Anda">
                                </div>
                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <button type="submit" name="cari_data" class="btn btn-bps w-100">
                                        <i class="fas fa-search me-2"></i>Cari Data
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Sistem akan menampilkan data kunjungan terbaru berdasarkan nama yang Anda masukkan.</small>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($data && !$success): ?>
                    <!-- Tampilkan Data Kunjungan -->
                    <div class="data-info">
                        <h5><i class="fas fa-user me-2"></i>Data Kunjungan Anda</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
                                <p><strong>Tanggal Kunjungan:</strong> <?= date('d/m/Y H:i', strtotime($data['tanggal'])) ?></p>
                                <p><strong>Jenis Tamu:</strong> <?= htmlspecialchars($data['Jenis_Tamu']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Instansi:</strong> <?= htmlspecialchars($data['nama_instansi']) ?></p>
                                <p><strong>Keperluan:</strong> <?= htmlspecialchars($data['keperluan']) ?></p>
                                <p><strong>No. WhatsApp:</strong> <?= htmlspecialchars($data['No_wa_Aktif']) ?></p>
                            </div>
                        </div>

                        <!-- Konfirmasi data -->
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Pastikan data di atas adalah data kunjungan Anda yang ingin dinilai!</strong>
                            <br><small>Jika bukan data Anda, silakan cari ulang dengan nama yang tepat.</small>
                        </div>
                    </div>

                    <!-- Form Penilaian -->
                    <form method="POST">
                        <input type="hidden" name="no_pengunjung" value="<?= $data['No_Pengunjung'] ?>">

                        <div class="mb-4">
                            <label class="form-label"><strong>Bagaimana kesan Anda terhadap pelayanan yang diberikan?</strong></label>
                            <div class="rating-container">
                                <?php
                                $rating_options = [
                                    '1 😞 Buruk' => ['emoji' => '😞', 'text' => 'Buruk', 'color' => '#dc3545'],
                                    '2 😐 Kurang Baik' => ['emoji' => '😐', 'text' => 'Kurang Baik', 'color' => '#fd7e14'],
                                    '3 🙂 Cukup' => ['emoji' => '🙂', 'text' => 'Cukup', 'color' => '#ffc107'],
                                    '4 😊 Baik' => ['emoji' => '😊', 'text' => 'Baik', 'color' => '#20c997'],
                                    '5 😍 Baik Sekali' => ['emoji' => '😍', 'text' => 'Baik Sekali', 'color' => '#28a745']
                                ];

                                $current_rating = $data['kesan pelayanan'] ?? '';

                                foreach ($rating_options as $value => $option) {
                                    $checked = ($current_rating == $value) ? 'checked' : '';
                                    $selected = ($current_rating == $value) ? 'selected' : '';
                                    echo "
                                    <label class='rating-option $selected' onclick='selectRating(this)'>
                                        <input type='radio' name='kesan_pelayanan' value='$value' $checked required>
                                        <div class='rating-content'>
                                            <span class='rating-emoji'>{$option['emoji']}</span>
                                            <span class='rating-text'>{$option['text']}</span>
                                        </div>
                                    </label>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="submit_penilaian" class="btn btn-bps btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Penilaian
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectRating(element) {
            // Remove selected class from all options
            document.querySelectorAll('.rating-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selected class to clicked option
            element.classList.add('selected');

            // Check the radio button
            element.querySelector('input[type="radio"]').checked = true;
        }
    </script>
</body>

</html>