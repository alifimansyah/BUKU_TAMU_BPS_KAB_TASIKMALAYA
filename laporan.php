<?php
session_start();
include 'config/koneksi.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Default filter
$filter = 'harian';
$tanggal = date('Y-m-d');
$bulan = date('Y-m');
$tahun = date('Y');

// Tangkap parameter filter
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    if ($filter == 'harian' && isset($_GET['tanggal'])) {
        $tanggal = $_GET['tanggal'];
    } elseif ($filter == 'bulanan' && isset($_GET['bulan'])) {
        $bulan = $_GET['bulan'];
    } elseif ($filter == 'tahunan' && isset($_GET['tahun'])) {
        $tahun = $_GET['tahun'];
    }
}

// Query berdasarkan filter
switch ($filter) {
    case 'harian':
        $title = "Laporan Harian - " . date('d F Y', strtotime($tanggal));
        $query = "SELECT * FROM tamu_umum WHERE DATE(tanggal) = '$tanggal' ORDER BY tanggal DESC";
        break;

    case 'bulanan':
        $title = "Laporan Bulanan - " . date('F Y', strtotime($bulan . '-01'));
        $query = "SELECT * FROM tamu_umum WHERE MONTH(tanggal) = MONTH('$bulan-01') AND YEAR(tanggal) = YEAR('$bulan-01') ORDER BY tanggal DESC";
        break;

    case 'tahunan':
        $title = "Laporan Tahunan - " . $tahun;
        $query = "SELECT * FROM tamu_umum WHERE YEAR(tanggal) = '$tahun' ORDER BY tanggal DESC";
        break;

    default:
        $title = "Laporan Harian - " . date('d F Y');
        $query = "SELECT * FROM tamu_umum WHERE DATE(tanggal) = CURDATE() ORDER BY tanggal DESC";
}

$result = mysqli_query($koneksi, $query);
$total_pengunjung = mysqli_num_rows($result);

// Query statistik
$query_statistik = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN jenis_Tamu = 'Umum' THEN 1 END) as umum,
                    COUNT(CASE WHEN jenis_Tamu = 'PST' THEN 1 END) as PST,
                    COUNT(CASE WHEN instansi = 'Pemerintah' THEN 1 END) as pemerintah,
                    COUNT(CASE WHEN instansi = 'Swasta' THEN 1 END) as swasta,
                    COUNT(CASE WHEN instansi = 'Lainnya' THEN 1 END) as lainnya
                    FROM tamu_umum";

if ($filter == 'harian') {
    $query_statistik .= " WHERE DATE(tanggal) = '$tanggal'";
} elseif ($filter == 'bulanan') {
    $query_statistik .= " WHERE MONTH(tanggal) = MONTH('$bulan-01') AND YEAR(tanggal) = YEAR('$bulan-01')";
} elseif ($filter == 'tahunan') {
    $query_statistik .= " WHERE YEAR(tanggal) = '$tahun'";
}

$statistik = mysqli_fetch_assoc(mysqli_query($koneksi, $query_statistik));

// Handle Excel Export
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"Laporan_Kunjungan_" . date('YmdHis') . ".xls\"");

    $output = '<table border="1">
                <tr>
                    <th colspan="10" style="text-align:center;">' . $title . '</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis Tamu</th>
                    <th>Nama</th>
                    <th>Instansi</th>
                    <th>Nama Instansi</th>
                    <th>Keperluan</th>
                    <th>Keterangan Keperluan</th>
                    <th>No WA</th>
                    <th>Pembahasan</th>
                    <th>Catatan Lain</th>
                    <th>Kesan Pelayanan</th>
                    <th>Rating_Fasilitas</th>
                    <th>Rating_Kepuasan</th>
                    <th>Catatan Pelayanan</th>
                    <th>Catatan Fasilitas</th>
                    <th>Catatan Kepuasan</th>
                </tr>';

    if ($result && mysqli_num_rows($result) > 0) {
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<tr>
                        <td>' . $no++ . '</td>
                        <td>' . date('d/m/Y H:i', strtotime($row['tanggal'])) . '</td>
                        <td>' . htmlspecialchars($row['petugas']) . '</td>
                        <td>' . htmlspecialchars($row['Jenis_Tamu']) . '</td>
                        <td>' . htmlspecialchars($row['nama']) . '</td>
                        <td>' . htmlspecialchars($row['instansi']) . '</td>
                        <td>' . htmlspecialchars($row['nama_instansi']) . '</td>
                        <td>' . htmlspecialchars($row['keperluan']) . '</td>
                        <td>' . htmlspecialchars($row['keterangan_keperluan'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['No_wa_Aktif']) . '</td>
                        <td>' . htmlspecialchars($row['Pembahasan'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['catatan_lain'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['kesan pelayanan']) . '</td>
                        <td>' . htmlspecialchars($row['Rating_Fasilitas'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['Rating_Kepuasan'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['catatan_pelayanan'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['catatan_fasilitas'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['catatan_kepuasan'] ?? '-') . '</td>
                    </tr>';
        }
    } else {
        $output .= '<tr><td colspan="10" style="text-align:center;">Tidak ada data pengunjung</td></tr>';
    }

    $output .= '</table>';
    echo $output;
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kunjungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <style>
        .sidebar {
            background-color: rgb(84, 6, 218);
            min-height: 100vh;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            background-color: #f8f9fa;
        }

        .stat-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3 text-center">
                    <h4><i class="fas fa-book-open me-2"></i>Buku Tamu</h4>
                    <p class="mb-0">BPS Tasikmalaya</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> isi buku tamu</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="table pengunjung.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a></li>
                    <li class="nav-item"><a class="nav-link active" href="laporan.php"><i class="fas fa-chart-bar me-2"></i> Laporan </a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_notulensi.php"><i class="fas fa-file-alt me-2"></i> Data Notulensi</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="Profil Pegawai.php"><i class="fas fa-cog me-2"></i> Profil Pegawai</a></li>
                    <li class="nav-item mt-3"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-chart-bar me-2"></i>Laporan Kunjungan</h2>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i> Filter Laporan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?filter=harian">Harian</a></li>
                            <li><a class="dropdown-item" href="?filter=bulanan">Bulanan</a></li>
                            <li><a class="dropdown-item" href="?filter=tahunan">Tahunan</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form method="get" action="laporan.php">
                            <input type="hidden" name="filter" value="<?= $filter ?>">

                            <?php if ($filter == 'harian'): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Pilih Tanggal</label>
                                        <input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>">
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Search</button>
                                    </div>
                                </div>

                            <?php elseif ($filter == 'bulanan'): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Pilih Bulan</label>
                                        <input type="month" class="form-control" name="bulan" value="<?= $bulan ?>">
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Search</button>
                                    </div>
                                </div>

                            <?php elseif ($filter == 'tahunan'): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Pilih Tahun</label>
                                        <select class="form-select" name="tahun">
                                            <?php
                                            $current_year = date('Y');
                                            for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                                echo "<option value='$i' " . ($tahun == $i ? 'selected' : '') . ">$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Search</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-primary stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Total Pengunjung</h6>
                                        <h2 class="mb-0"><?= $statistik['total'] ?></h2>
                                    </div>
                                    <i class="fas fa-users fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-success stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Tamu Umum</h6>
                                        <h2 class="mb-0"><?= $statistik['umum'] ?></h2>
                                    </div>
                                    <i class="fas fa-user fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-warning stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Tamu PST</h6>
                                        <h2 class="mb-0"><?= $statistik['PST'] ?></h2>
                                    </div>
                                    <i class="fas fa-user-shield fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= $title ?></h5>
                        <a href="laporan.php?filter=<?= $filter ?>&tanggal=<?= $tanggal ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&export=excel" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel me-1"></i> Download Excel
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>petugas</th>
                                        <th>Jenis Tamu</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>Nama Instansi</th>
                                        <th>Keperluan</th>
                                        <th>Keterangan Keperluan</th>
                                        <th>No WA</th>
                                        <th>Pembahasan</th>
                                        <th>Tindak Lanjut</th>
                                        <th>Kesan Pelayanan</th>
                                        <th>Rating Fasilitas</th>
                                        <th>Rating Kepuasan</th>
                                        <th>Catatan Pelayanan</th>
                                        <th>Catatan Fasilitas</th>
                                        <th>Catatan Kepuasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                        <?php $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                                <td><?= htmlspecialchars($row['petugas']) ?></td>
                                                <td><?= htmlspecialchars($row['Jenis_Tamu']) ?></td>
                                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                                <td><?= htmlspecialchars($row['instansi']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_instansi']) ?></td>
                                                <td><?= htmlspecialchars($row['keperluan']) ?></td>
                                                <td><?= htmlspecialchars($row['keterangan_keperluan'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['No_wa_Aktif']) ?></td>
                                                <td><?= htmlspecialchars($row['pembahasan'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['catatan_lain'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['kesan pelayanan'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['Rating_Fasilitas'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['Rating_Kepuasan'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['catatan_pelayanan'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['catatan_fasilitas'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['catatan_kepuasan'] ?? '-') ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data pengunjung</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>