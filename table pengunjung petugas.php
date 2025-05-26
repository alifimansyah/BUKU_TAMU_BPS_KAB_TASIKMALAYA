<?php
// Tambahkan ini di paling atas file sebelum output apapun
session_start();
include 'config/koneksi.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM tamu_umum ORDER BY No_Pengunjung DESC";
$result = mysqli_query($koneksi, $query);
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <!-- DataTables Buttons CSS -->
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
        
        .bps-theme {
            background-color: #1a237e;
            color: white;
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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3 text-center">
                    <h4><i class="fas fa-book-open me-2"></i>Buku Tamu</h4>
                    <p class="mb-0">BPS Tasikmalaya</p>
                    <?php if (isset($_SESSION['username'])): ?>
                        <span>Login sebagai: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span><br>
                    <?php endif; ?>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> isi buku tamu</a>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="table_pengunjung_petugas.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a>
                            </li>
                            <li class="nav-item">
                            <a href="?logout=1" class="btn btn-danger btn-sm mt-2"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                <?php endif; ?>

               <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Data Pengunjung</h2>
    <button id="btnTambahPengunjung" class="btn btn-bps">
        <i class="fas fa-plus me-1"></i> Tambah Pengunjung
    </button>
</div>

<!-- Form Tambah Pengunjung (Initially Hidden) -->
<div id="formTambah" class="form-tambah card shadow-sm" style="display: none;">
    <div class="card-header bps-theme">
        <h5 class="mb-0">Form Tambah Data Pengunjung</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <input type="hidden" name="tambah_pengunjung" value="1">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="jenis_tamu" class="form-label">Jenis Tamu</label>
                    <select class="form-select" id="jenis_tamu" name="jenis_tamu" required>
                        <option value="">Pilih Jenis Tamu</option>
                        <option value="Umum">Tamu Umum</option>
                        <option value="Instansi">Tamu PST</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="instansi" class="form-label">Instansi</label>
                    <select class="form-select" id="instansi" name="instansi" required>
                        <option value="">Pilih Asal Tamu</option>
                        <option value="Pemerintah">Instansi/Dinas/Lembaga Pemerintah</option>
                        <option value="Swasta">Perusahaan Swasta</option>
                        <option value="Pendidikan">Pelajar/Mahasiswa</option>
                        <option value="Perorangan">Masyarakat Umum</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="nama_instansi" class="form-label">Nama Instansi</label>
                    <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="keperluan" class="form-label">Keperluan</label>
                    <select class="form-select" id="keperluan" name="keperluan" required>
                        <option value="" disabled selected>Pilih keperluan kunjungan</option>
                        <option value="Konsultasi Statistik">Konsultasi Data Statistik</option>
                        <option value="Perpustakaan">Layanan Perpustakaan</option>
                        <option value="Rekomendasi kegiatan statistik">Pelayanan Rekomendasi Kegiatan Statistik</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="keterangan_keperluan" class="form-label">Keterangan Keperluan</label>
                    <input type="text" class="form-control" id="keterangan_keperluan" name="keterangan_keperluan">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="no_wa" class="form-label">No. WhatsApp Aktif</label>
                    <input type="text" class="form-control" id="no_wa" name="no_wa" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kesan Pelayanan</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesan_pelayanan" id="kesan1" value="1 😞 Buruk" required>
                        <label class="form-check-label" for="kesan1">😞 Buruk</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesan_pelayanan" id="kesan2" value="2 😐 Kurang Baik">
                        <label class="form-check-label" for="kesan2">😐 Kurang Baik</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesan_pelayanan" id="kesan3" value="3 🙂 Cukup">
                        <label class="form-check-label" for="kesan3">🙂 Cukup</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesan_pelayanan" id="kesan4" value="4 😊 Baik">
                        <label class="form-check-label" for="kesan4">😊 Baik</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesan_pelayanan" id="kesan5" value="5 😍 Baik Sekali">
                        <label class="form-check-label" for="kesan5">😍 Baik Sekali</label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" id="btnBatal" class="btn btn-secondary me-md-2">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// JavaScript to handle form show/hide
document.addEventListener('DOMContentLoaded', function() {
    const btnTambah = document.getElementById('btnTambahPengunjung');
    const btnBatal = document.getElementById('btnBatal');
    const formTambah = document.getElementById('formTambah');
    
    // Show form when Tambah Pengunjung button is clicked
    btnTambah.addEventListener('click', function() {
        formTambah.style.display = 'block';
    });
    
    // Hide form when Batal button is clicked
    btnBatal.addEventListener('click', function() {
        formTambah.style.display = 'none';
    });
});
</script>

                <!-- Tabel Data Pengunjung -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Daftar Kunjungan Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelPengunjung" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Tamu</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>Nama Instansi</th>
                                        <th>Keperluan</th>
                                        <th>keterangan Keperluan</th>
                                        <th>No WA Aktif</th>
                                        <th>kesan Pelayanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                            <td>" . $no++ . "</td>
                                            <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                            <td>" . htmlspecialchars($row['Jenis_Tamu']) . "</td>
                                            <td>" . htmlspecialchars($row['nama']) . "</td>
                                            <td>" . htmlspecialchars($row['instansi']) . "</td>
                                            <td>" . htmlspecialchars($row['nama_instansi']) . "</td>
                                            <td>" . htmlspecialchars($row['keperluan']) . "</td>
                                            <td>" . htmlspecialchars($row['keterangan_keperluan'] ?? '-') . "</td>
                                            <td>" . htmlspecialchars($row['No_wa_Aktif']) . "</td>
                                            <td>" . htmlspecialchars($row['kesan pelayanan']) . "</td>
                                            <td>
                                                <a href='edit_pengunjung.php?id=" . $row['No_Pengunjung'] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                                <a href='hapus_pengunjung.php?id=" . $row['No_Pengunjung'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'><i class='fas fa-trash-alt'></i></a>
                                            </td>
                                        </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='11' class='text-center'>Tidak ada data pengunjung</td></tr>";
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

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelPengunjung').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json'
                },
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Data_Pengunjung_BPS_Tasikmalaya',
                    text: '<i class="fas fa-file-excel"></i> Ekspor ke Excel',
                    className: 'btn btn-success mb-3'
                }]
            });
        });
    </script>
</body>
</html>