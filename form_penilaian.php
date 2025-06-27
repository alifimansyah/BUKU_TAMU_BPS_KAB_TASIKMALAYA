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
    $rating_fasilitas = mysqli_real_escape_string($koneksi, $_POST['rating_fasilitas']);
    $rating_kepuasan = mysqli_real_escape_string($koneksi, $_POST['rating_kepuasan']);
    $catatan_pelayanan = mysqli_real_escape_string($koneksi, $_POST['catatan_pelayanan'] ?? '');
    $catatan_fasilitas = mysqli_real_escape_string($koneksi, $_POST['catatan_fasilitas'] ?? '');
    $catatan_kepuasan = mysqli_real_escape_string($koneksi, $_POST['catatan_kepuasan'] ?? '');

    // Update data penilaian
    $query = "UPDATE tamu_umum SET 
        `kesan pelayanan` = ?,
        `Rating_Fasilitas` = ?,
        `Rating_Kepuasan` = ?,
        `catatan_pelayanan` = ?,
        `catatan_fasilitas` = ?,
        `catatan_kepuasan` = ?
        WHERE No_Pengunjung = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", 
        $kesan_pelayanan, 
        $rating_fasilitas, 
        $rating_kepuasan,
        $catatan_pelayanan,
        $catatan_fasilitas,
        $catatan_kepuasan,
        $no_pengunjung
    );

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
            gap: 15px;
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
            min-width: 100px;
        }

        .rating-option:hover {
            border-color: var(--bps-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .rating-option input[type="radio"] {
            display: none;
        }

        .rating-option.selected {
            border-color: var(--bps-green);
            background: rgba(10, 158, 63, 0.1);
        }

        .rating-stars {
            font-size: 1.8rem;
            margin-bottom: 5px;
            display: block;
            color: #ffc107;
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
        
        .rating-section {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .rating-title {
            color: var(--bps-blue);
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .catatan-field {
            margin-top: 15px;
        }
        
        .catatan-field textarea {
            min-height: 80px;
        }

        <style>
    .rating-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .rating-option {
        display: flex;
        align-items: center;
        border: 2px solid transparent;
        padding: 10px 15px;
        border-radius: 12px;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .rating-option:hover {
        background-color: #eaf4ff;
    }

    .rating-option.selected {
        background-color: #d0f0ff;
        border-color: #20a8d8; /* Biru muda */
    }

    .rating-option input[type="radio"] {
        display: none;
    }

    .rating-content {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 16px;
        width: 100%;
    }

    .rating-stars {
        font-family: 'Arial', sans-serif;
        font-size: 20px;
        color: #ff9800; /* Oranye */
        min-width: 100px;
        letter-spacing: 2px;
    }

    .rating-text {
        font-weight: 600;
        color: #28a745; /* Hijau BPS */
    }

    .catatan-field {
        margin-top: 16px;
    }

    .form-label {
        font-weight: 500;
        color: #333;
    }

    textarea.form-control {
        border-radius: 10px;
        border: 1px solid #ced4da;
    }

    button.btn-bps {
        background-color: #20a8d8; /* Biru muda BPS */
        color: #fff;
        border: none;
    }

    button.btn-bps:hover {
        background-color: #1b8eb7;
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
                   <?php
                        $labelRating = [
                            5 => 'Sangat Puas',
                            4 => 'Puas',
                            3 => 'Cukup',
                            2 => 'Kurang Puas',
                            1 => 'Tidak Puas'
                        ];
                        ?>

                        <form method="POST">
                            <input type="hidden" name="no_pengunjung" value="<?= $data['No_Pengunjung'] ?>">

                            <!-- Rating Pelayanan -->
                            <div class="rating-section">
                                <h5 class="rating-title"><i class="fas fa-handshake me-2"></i>Rating petugas piket</h5>
                                <p class="text-muted mb-3">Bagaimana kesan Anda terhadap pelayanan yang diberikan?</p>
                                
                                <div class="star-rating">
                                    <?php 
                                    $current_rating = isset($data['kesan pelayanan']) ? (int)substr($data['kesan pelayanan'], 0, 1) : 0;
                                    for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="pelayanan-star-<?= $i ?>" name="kesan_pelayanan" value="<?= $i ?> ⭐<?= str_repeat('⭐', $i-1) ?>" 
                                            <?= ($current_rating == $i) ? 'checked' : '' ?>>
                                        <label for="pelayanan-star-<?= $i ?>" title="<?= $labelRating[$i] ?>">⭐</label>
                                    <?php endfor; ?>
                                </div>
                                
                                <div class="rating-text-display" id="pelayanan-rating-text">
                                    <?= isset($labelRating[$current_rating]) ? $labelRating[$current_rating] : 'Pilih rating'; ?>
                                </div>
                                
                                <div class="catatan-field">
                                    <label for="catatan_pelayanan" class="form-label">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" name="catatan_pelayanan" 
                                        placeholder="Berikan catatan khusus tentang pelayanan"><?= htmlspecialchars($data['catatan_pelayanan'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Rating Fasilitas -->
                            <div class="rating-section">
                                <h5 class="rating-title"><i class="fas fa-building me-2"></i>Rating Fasilitas PST</h5>
                                <p class="text-muted mb-3">Bagaimana penilaian Anda terhadap fasilitas yang disediakan?</p>
                                
                                <div class="star-rating">
                                    <?php 
                                    $current_rating_fasilitas = isset($data['Rating_Fasilitas']) ? (int)substr($data['Rating_Fasilitas'], 0, 1) : 0;
                                    for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="fasilitas-star-<?= $i ?>" name="rating_fasilitas" value="<?= $i ?> ⭐<?= str_repeat('⭐', $i-1) ?>" 
                                            <?= ($current_rating_fasilitas == $i) ? 'checked' : '' ?>>
                                        <label for="fasilitas-star-<?= $i ?>" title="<?= $labelRating[$i] ?>">⭐</label>
                                    <?php endfor; ?>
                                </div>
                                
                                <div class="rating-text-display" id="fasilitas-rating-text">
                                    <?= isset($labelRating[$current_rating_fasilitas]) ? $labelRating[$current_rating_fasilitas] : 'Pilih rating'; ?>
                                </div>
                                
                                <div class="catatan-field">
                                    <label for="catatan_fasilitas" class="form-label">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" name="catatan_fasilitas" 
                                        placeholder="Berikan catatan khusus tentang fasilitas"><?= htmlspecialchars($data['catatan_fasilitas'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Rating Kepuasan -->
                            <div class="rating-section">
                                <h5 class="rating-title"><i class="fas fa-smile me-2"></i>Rating Kepuasan Layanan Data</h5>
                                <p class="text-muted mb-3">Secara keseluruhan, bagaimana tingkat kepuasan Anda?</p>
                                
                                <div class="star-rating">
                                    <?php 
                                    $current_rating_kepuasan = isset($data['Rating_Kepuasan']) ? (int)substr($data['Rating_Kepuasan'], 0, 1) : 0;
                                    for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="kepuasan-star-<?= $i ?>" name="rating_kepuasan" value="<?= $i ?> ⭐<?= str_repeat('⭐', $i-1) ?>" 
                                            <?= ($current_rating_kepuasan == $i) ? 'checked' : '' ?>>
                                        <label for="kepuasan-star-<?= $i ?>" title="<?= $labelRating[$i] ?>">⭐</label>
                                    <?php endfor; ?>
                                </div>
                                
                                <div class="rating-text-display" id="kepuasan-rating-text">
                                    <?= isset($labelRating[$current_rating_kepuasan]) ? $labelRating[$current_rating_kepuasan] : 'Pilih rating'; ?>
                                </div>
                                
                                <div class="catatan-field">
                                    <label for="catatan_kepuasan" class="form-label">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" name="catatan_kepuasan" 
                                        placeholder="Berikan catatan keseluruhan tentang kunjungan Anda"><?= htmlspecialchars($data['catatan_kepuasan'] ?? '') ?></textarea>
                                </div>
                            </div>

                        <style>
                        .star-rating {
                            display: flex;
                            justify-content: center;
                            font-size: 2.5rem;
                            line-height: 2.5rem;
                            margin: 15px 0;
                        }
                        .star-rating input {
                            display: none;
                        }
                        .star-rating label {
                            color: #ddd;
                            cursor: pointer;
                            padding: 0 5px;
                            transition: all 0.2s ease;
                        }
                        .star-rating input:checked ~ label,
                        .star-rating label:hover,
                        .star-rating label:hover ~ label {
                            color: #ffcc00;
                            transform: scale(1.1);
                        }
                        .rating-text-display {
                            margin: 10px 0;
                            font-weight: bold;
                            font-size: 1.1rem;
                            color: #005bae;
                            text-align: center;
                            min-height: 24px;
                        }
                        </style>

<script>
// Function to update rating text display
function updateRatingText(ratingValue, displayElementId) {
    const ratingTexts = {
        '5': 'Sangat Puas',
        '4': 'Puas',
        '3': 'Cukup',
        '2': 'Kurang Puas',
        '1': 'Tidak Puas'
    };
    document.getElementById(displayElementId).textContent = ratingTexts[ratingValue] || 'Pilih rating';
}

// Initialize all star rating systems
document.querySelectorAll('.star-rating').forEach(ratingContainer => {
    const inputs = ratingContainer.querySelectorAll('input[type="radio"]');
    const displayId = ratingContainer.nextElementSibling.id;
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            updateRatingText(this.value.split(' ')[0], displayId);
        });
    });
});
</script>


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
            // Remove selected class from all options in the same container
            const container = element.closest('.rating-container');
            container.querySelectorAll('.rating-option').forEach(option => {
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