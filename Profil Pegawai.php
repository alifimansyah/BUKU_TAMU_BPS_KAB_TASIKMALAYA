<?php
session_start();
include 'config/koneksi.php';

// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle DELETE
if (isset($_GET['delete'])) {
    $nip = mysqli_real_escape_string($koneksi, $_GET['delete']);
    $delete_query = mysqli_query($koneksi, "DELETE FROM tb_operator WHERE NIP = '$nip'");
    if ($delete_query) {
        echo "<script>alert('Data pegawai berhasil dihapus!'); window.location='Profil Pegawai.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location='Profil Pegawai.php';</script>";
        exit();
    }
}

// Ambil data pegawai dan urutkan berdasarkan hari
$query = mysqli_query($koneksi, "SELECT * FROM tb_operator ORDER BY FIELD(Hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')");
if (!$query) {
    die('Query Error: ' . mysqli_error($koneksi));
}

$pegawai = [];
while ($row = mysqli_fetch_assoc($query)) {
    $pegawai[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Petugas Piket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #eef5f9;
            margin: 0;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 4px solid #007bff;
        }

        .card h3 {
            margin: 10px 0 5px;
            color: #2c3e50;
            font-size: 1.2rem;
        }

        .card p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }

        .hari-piket {
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            margin: 10px 0;
            font-size: 13px;
            font-weight: 500;
        }

        .card-actions {
            margin-top: 15px;
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card-actions .btn {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }

        .card-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar {
            background-color: rgb(84, 6, 218);
            min-height: 100vh;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            /* font-weight: bold; */
        }

        .status-online {
            background-color: #28a745;
            color: white;
        }

        .status-offline {
            background-color: #d0d0d0;
            color: black;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3 text-center">
                    <h4><i class="fas fa-book-open me-2"></i>Buku Tamu</h4>
                    <p class="mb-0">BPS Tasikmalaya</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Isi Buku Tamu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="table pengunjung.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar me-2"></i> Laporan & Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_notulensi.php"><i class="fas fa-file-alt me-2"></i> Data Notulensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="Profil Pegawai.php"><i class="fas fa-cog me-2"></i> Data Pegawai</a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Profil Petugas Piket BPS</h1>
                    <div>
                        <a href="tambah_pegawai.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Pegawai
                        </a>
                    </div>
                </div>

                <?php if (empty($pegawai)): ?>
                    <div class="alert alert-info text-center">
                        <h4>Belum ada data pegawai</h4>
                        <p>Silakan tambah data pegawai terlebih dahulu</p>
                        <a href="tambah_pegawai.php" class="btn btn-primary">Tambah Pegawai</a>
                    </div>
                <?php else: ?>
                    <div class="grid-container">
                        <?php foreach ($pegawai as $p): ?>
                            <div class="card">
                                <div class="status-badge <?= $p['Status'] == 1 ? 'status-online' : 'status-offline' ?>">
                                    <?= $p['Status'] == 1 ? 'Online' : 'Offline' ?>
                                </div>

                                <img src="<?= !empty($p['foto']) && file_exists($p['foto']) ? htmlspecialchars($p['foto']) : 'assets/default.png' ?>"
                                    alt="foto <?= htmlspecialchars($p['Nama']) ?>"
                                    onerror="this.src='assets/default.png'">

                                <h3><?= htmlspecialchars($p['Nama']) ?></h3>

                                <p><strong>Username:</strong> <?= htmlspecialchars($p['username']) ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($p['NIP']) ?></p>
                                <p><strong>Role:</strong> <?= htmlspecialchars($p['Role']) ?></p>

                                <?php if (!empty($p['Hari'])): ?>
                                    <div class="hari-piket">
                                        Hari Piket: <?= htmlspecialchars($p['Hari']) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card-actions">
                                    <a href="edit_pegawai.php?nip=<?= urlencode($p['NIP']) ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-danger"
                                        onclick="confirmDelete('<?= htmlspecialchars($p['NIP'], ENT_QUOTES) ?>', '<?= htmlspecialchars($p['Nama'], ENT_QUOTES) ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(nip, nama) {
            if (confirm('Yakin ingin menghapus data pegawai "' + nama + '"?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                window.location.href = 'Profil Pegawai.php?delete=' + encodeURIComponent(nip);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>