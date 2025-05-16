<?php
session_start();
include 'config/koneksi.php';

// Query data pengunjung
$query = "SELECT * FROM tamu_umum ORDER BY No_Pengunjung DESC";
$result = mysqli_query($koneksi, $query);

// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Buku Tamu Digital - BPS Tasikmalaya</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar {
            background-color: #1a237e;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background-color: #f8f9fa;
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
                    <a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-book me-2"></i> Buku Tamu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="table_pengunjung.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar me-2"></i> Laporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Profil_Pegawai.php"><i class="fas fa-cog me-2"></i> Profil Pegawai</a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h2>
            </div>

            <!-- Dashboard Cards -->
            <div class="row">
                <!-- Buku Tamu -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-book-open fa-3x mb-3"></i>
                            <h5 class="card-title">Buku Tamu</h5>
                            <a href="index.php" class="btn btn-primary w-100">Isi Buku Tamu</a>
                        </div>
                    </div>
                </div>

                <!-- Data Pengunjung -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5 class="card-title">Data Pengunjung</h5>
                            <a href="table_pengunjung.php" class="btn btn-success w-100">Lihat Data</a>
                        </div>
                    </div>
                </div>

                <!-- Laporan -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x mb-3"></i>
                            <h5 class="card-title">Laporan</h5>
                            <a href="laporan.php" class="btn btn-warning w-100">Lihat Laporan</a>
                        </div>
                    </div>
                </div>

                <!-- Profil Pegawai -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-cog fa-3x mb-3"></i>
                            <h5 class="card-title">Profil Pegawai</h5>
                            <a href="Profil_Pegawai.php" class="btn btn-info w-100">Lihat Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
