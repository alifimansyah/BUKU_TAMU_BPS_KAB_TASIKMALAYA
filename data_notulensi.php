<?php
session_start();
include 'config/koneksi.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Proses Update Notulensi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_notulensi'])) {
    $no_pengunjung = intval($_POST['no_pengunjung']);
    $pembahasan = mysqli_real_escape_string($koneksi, $_POST['pembahasan']);
    $catatan_lain = mysqli_real_escape_string($koneksi, $_POST['catatan_lain']);
    $petugas = $_SESSION['username'] ?? 'System';

    $query = "UPDATE tamu_umum SET 
              pembahasan = ?, 
              catatan_lain = ?,
              petugas = ?
              WHERE No_Pengunjung = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $pembahasan, $catatan_lain, $petugas, $no_pengunjung);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data notulensi berhasil diperbarui";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data notulensi: " . mysqli_error($koneksi);
    }
}

// Query untuk menampilkan data - hanya yang sudah ada kesan pelayanan (sudah selesai kunjungan)
$query = "SELECT No_Pengunjung, tanggal, nama, instansi, nama_instansi, petugas, pembahasan, catatan_lain, `kesan pelayanan`
          FROM tamu_umum 
          WHERE `kesan pelayanan` IS NOT NULL AND `kesan pelayanan` != ''
          ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

// Handle Export Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"Data_Notulensi_BPS_" . date('YmdHis') . ".xls\"");

    $output = '<table border="1">
                <tr>
                    <th colspan="7" style="text-align:center; font-weight:bold;">DATA NOTULENSI KUNJUNGAN BPS TASIKMALAYA</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Pengunjung</th>
                    <th>Asal Tamu</th>
                    <th>Nama Instansi</th>
                    <th>Petugas</th>
                    <th>Pembahasan</th>
                    <th>Catatan Lain</th>
                </tr>';

    if ($result && mysqli_num_rows($result) > 0) {
        $no = 1;
        mysqli_data_seek($result, 0); // Reset result pointer
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<tr>
                        <td>' . $no++ . '</td>
                        <td>' . date('d/m/Y H:i', strtotime($row['tanggal'])) . '</td>
                        <td>' . htmlspecialchars($row['nama']) . '</td>
                        <td>' . htmlspecialchars($row['instansi']) . '</td>
                        <td>' . htmlspecialchars($row['nama_instansi']) . '</td>
                        <td>' . htmlspecialchars($row['petugas'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['pembahasan'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($row['catatan_lain'] ?? '-') . '</td>
                    </tr>';
        }
    } else {
        $output .= '<tr><td colspan="8" style="text-align:center;">Tidak ada data notulensi</td></tr>';
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
    <title>Data Notulensi - BPS Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <style>
        .sidebar {
            background-color: #1a237e;
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

        .btn-bps {
            background: linear-gradient(45deg, #4CAF50, #FF9800, #03A9F4);
            color: white;
            border: none;
        }

        .btn-bps:hover {
            opacity: 0.9;
        }

        .card-header.bps-theme {
            background: linear-gradient(to right, #03A9F4, #4CAF50);
            color: white;
        }

        .form-edit {
            display: none;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-complete {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .btn-edit-notulensi {
            background: linear-gradient(45deg, #17a2b8, #20c997);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
        }

        .btn-edit-notulensi:hover {
            opacity: 0.8;
            color: white;
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
                        <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Isi Buku Tamu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="table pengunjung.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar me-2"></i> Laporan & Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="data_notulensi.php"><i class="fas fa-file-alt me-2"></i> Data Notulensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Profil Pegawai.php"><i class="fas fa-cog me-2"></i> Data Pegawai</a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="fas fa-file-alt me-2"></i>Data Notulensi Kunjungan</h2>
                        <p class="text-muted mb-0">Kelola pembahasan dan catatan kunjungan tamu</p>
                    </div>
                    <div>
                        <a href="?export=excel" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mb-4 border-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-info"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                                <p class="mb-0">Halaman ini menampilkan data kunjungan yang sudah selesai (sudah memberikan penilaian). Anda dapat menambahkan atau mengedit pembahasan dan catatan untuk setiap kunjungan.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="text-muted">
                                    <small>Total Data: <strong><?= mysqli_num_rows($result) ?></strong></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Edit Notulensi (Awalnya Disembunyikan) -->
                <div id="formEdit" class="form-edit card shadow-sm">
                    <div class="card-header bps-theme">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Data Notulensi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="update_notulensi" value="1">
                            <input type="hidden" name="no_pengunjung" id="edit_no_pengunjung">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Nama Pengunjung:</strong></label>
                                    <p id="edit_nama_display" class="form-control-plaintext"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Tanggal Kunjungan:</strong></label>
                                    <p id="edit_tanggal_display" class="form-control-plaintext"></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Asal Tamu:</strong></label>
                                    <p id="edit_instansi_display" class="form-control-plaintext"></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Nama Instansi:</strong></label>
                                    <p id="edit_nama_instansi_display" class="form-control-plaintext"></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_pembahasan" class="form-label">Pembahasan</label>
                                <textarea class="form-control" id="edit_pembahasan" name="pembahasan" rows="4" 
                                          placeholder="Tuliskan pembahasan atau topik yang dibahas selama kunjungan..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_catatan_lain" class="form-label">Catatan Lain</label>
                                <textarea class="form-control" id="edit_catatan_lain" name="catatan_lain" rows="3" 
                                          placeholder="Tuliskan catatan tambahan, tindak lanjut, atau hal penting lainnya..."></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" id="btnBatal" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Notulensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Notulensi -->
                <div class="card shadow-sm">
                    <div class="card-header bps-theme">
                        <h5 class="mb-0">Daftar Data Notulensi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelNotulensi" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pengunjung</th>
                                        <th>Asal Tamu</th>
                                        <th>Nama Instansi</th>
                                        <th>Petugas</th>
                                        <th>Status Notulensi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $has_notulensi = !empty($row['pembahasan']) || !empty($row['catatan_lain']);
                                            $status_class = $has_notulensi ? 'status-complete' : 'status-pending';
                                            $status_text = $has_notulensi ? 'Lengkap' : 'Belum Lengkap';
                                            
                                            echo "<tr>
                                            <td>" . $no++ . "</td>
                                            <td>" . date('d/m/Y H:i', strtotime($row['tanggal'])) . "</td>
                                            <td>" . htmlspecialchars($row['nama']) . "</td>
                                            <td>" . htmlspecialchars($row['instansi']) . "</td>
                                            <td>" . htmlspecialchars($row['nama_instansi']) . "</td>
                                            <td>" . htmlspecialchars($row['petugas'] ?? '-') . "</td>
                                            <td><span class='status-badge $status_class'>$status_text</span></td>
                                            <td>
                                                <button type='button' class='btn btn-edit-notulensi btn-sm' 
                                                        onclick='editNotulensi(" . json_encode($row) . ")'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </button>
                                            </td>
                                        </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>Tidak ada data notulensi</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTable
            $('#tabelNotulensi').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json'
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Data_Notulensi_BPS_Tasikmalaya',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-success mb-3'
                }],
                order: [[1, 'desc']], // Sort by tanggal descending
                pageLength: 25
            });

            // Batal edit
            $('#btnBatal').click(function() {
                $('#formEdit').slideUp();
            });
        });

        function editNotulensi(data) {
            // Populate form dengan data
            $('#edit_no_pengunjung').val(data.No_Pengunjung);
            $('#edit_nama_display').text(data.nama);
            $('#edit_tanggal_display').text(new Date(data.tanggal).toLocaleString('id-ID'));
            $('#edit_instansi_display').text(data.instansi);
            $('#edit_nama_instansi_display').text(data.nama_instansi);
            $('#edit_pembahasan').val(data.pembahasan || '');
            $('#edit_catatan_lain').val(data.catatan_lain || '');
            
            // Show form
            $('#formEdit').slideDown();
            
            // Scroll ke form
            $('html, body').animate({
                scrollTop: $('#formEdit').offset().top - 100
            }, 500);
        }
    </script>
</body>

</html>