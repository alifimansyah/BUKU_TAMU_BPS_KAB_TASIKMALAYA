<?php
session_start();
include 'config/koneksi.php';

// Proses Tambah Data Pengunjung
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_pengunjung'])) {
    $tanggal = date('Y-m-d H:i:s');
    $petugas = $_POST['username'] ?? 'System';
    $jenis_tamu = $_POST['jenis_tamu'];
    $nama = $_POST['nama'];
    $instansi = $_POST['instansi'];
    $nama_instansi = $_POST['nama_instansi'];
    $keperluan = $_POST['keperluan'];
    $keterangan_keperluan = $_POST['keterangan_keperluan'] ?? '';
    $no_wa = $_POST['No_wa_Aktif'];
    $pembahasan = $_POST['pembahasan'] ?? '';
    $catatan_lain = $_POST['catatan_lain'] ?? '';
    $kesan_pelayanan = $_POST['kesan_pelayanan'];
    $rating_fasilitas = $_POST['Rating_Fasilitas'] ?? '';
    $rating_kepuasan = $_POST['Rating_Kepuasan'] ?? '';
    $catatan_pelayanan = $_POST['catatan_pelayanan'] ?? '';
    $catatan_fasilitas = $_POST['catatan_fasilitas'] ?? '';
    $catatan_kepuasan = $_POST['catatan_kepuasan'] ?? '';
    // Query yang sudah diperbaiki
    $query = "INSERT INTO tamu_umum (
                tanggal, petugas, Jenis_Tamu, nama, instansi, nama_instansi, 
                keperluan, keterangan_keperluan, No_wa_Aktif, pembahasan, 
                catatan_lain, `kesan pelayanan`, `Rating_Fasilitas`, 
                `Rating_Kepuasan`, catatan_pelayanan, catatan_fasilitas, catatan_kepuasan
              ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
              )";

    $stmt = mysqli_prepare($koneksi, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt, 
            "sssssssssssssssss", 
            $tanggal, 
            $petugas, 
            $jenis_tamu, 
            $nama, 
            $instansi, 
            $nama_instansi,
            $keperluan,
            $keterangan_keperluan,
            $no_wa,
            $pembahasan,
            $catatan_lain,
            $kesan_pelayanan,
            $rating_fasilitas,
            $rating_kepuasan,
            $catatan_pelayanan,
            $catatan_fasilitas,
            $catatan_kepuasan
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Data pengunjung berhasil ditambahkan";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = "Gagal menambahkan data pengunjung: " . mysqli_stmt_error($stmt);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Gagal mempersiapkan statement: " . mysqli_error($koneksi);
    }
}

// Query untuk menampilkan data dari tamu_umum
$query = "SELECT * FROM tamu_umum ORDER BY No_Pengunjung DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Buku Tamu Digital - BPS Tasikmalaya</title>
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
        .form-tambah {
            display: none;
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .invalid-feedback {
            color: #dc3545;
            display: none;
            font-size: 0.875em;
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
                        <a class="nav-link active" href="table pengunjung.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar me-2"></i> Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_notulensi.php"><i class="fas fa-file-alt me-2"></i> Data Notulensi</a>
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
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users me-2"></i>Data Pengunjung</h2>
                    <button id="btnTambah" class="btn btn-bps">
                        <i class="fas fa-plus me-1"></i> Tambah Pengunjung
                    </button>
                </div>

                <!-- Form Tambah Pengunjung -->
                <div id="formTambah" class="form-tambah card shadow">
                    <div class="card-header bps-theme">
                        <h5><i class="fas fa-user-plus me-2"></i>Form Tambah Data Pengunjung</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="formTambahPengunjung">
                            <input type="hidden" name="tambah_pengunjung" value="1">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tanggal" class="form-label">Tanggal Kunjungan</label>
                                    <input type="datetime-local" class="form-control" name="tanggal" id="tanggal" value="<?= date('Y-m-d\TH:i') ?>" required>
                                    <div class="invalid-feedback">Harap isi tanggal kunjungan</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="petugas" class="form-label">Petugas</label>
                                    <input type="text" class="form-control" name="petugas" id="petugas" value="<?= htmlspecialchars($_SESSION['username'] ?? 'System') ?>" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="jenis_tamu" class="form-label">Jenis Tamu</label>
                                    <select class="form-select" id="jenis_tamu" name="jenis_tamu" required>
                                        <option value="">Pilih Jenis Tamu</option>
                                        <option value="Umum">Tamu Umum</option>
                                        <option value="Instansi">Tamu PST</option>
                                    </select>
                                    <div class="invalid-feedback">Harap pilih jenis tamu</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                    <div class="invalid-feedback">Harap isi nama lengkap</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="instansi" class="form-label">Jenis Instansi</label>
                                    <select class="form-select" id="instansi" name="instansi">
                                        <option value="">Pilih Jenis Instansi</option>
                                        <option value="Pemerintah">Instansi/Dinas/Lembaga Pemerintah</option>
                                        <option value="Swasta">Perusahaan Swasta</option>
                                        <option value="Pendidikan">Pelajar/Mahasiswa</option>
                                        <option value="Perorangan">Masyarakat Umum</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_instansi" class="form-label">Nama Instansi</label>
                                    <input type="text" class="form-control" id="nama_instansi" name="nama_instansi">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="keperluan" class="form-label">Keperluan</label>
                                    <select class="form-select" id="keperluan" name="keperluan">
                                        <option value="">Pilih Keperluan</option>
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
                                    <input type="text" class="form-control" id="no_wa" name="No_wa_Aktif" required>
                                    <div class="invalid-feedback">Harap isi nomor WhatsApp</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="pembahasan" class="form-label">Pembahasan</label>
                                    <input type="text" class="form-control" id="pembahasan" name="pembahasan">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="catatan_lain" class="form-label">Tindak Lanjut
                                    <input type="text" class="form-control" id="catatan_lain" name="catatan_lain">
                                </div>
                            </div>

                            <!-- Rating Section -->
                            <div class="rating-section mb-4">
                                <h5><i class="fas fa-star me-2"></i>Penilaian</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="kesan_pelayanan" class="form-label">Kesan Pelayanan</label>
                                        <select class="form-select" name="kesan_pelayanan" id="kesan_pelayanan">
                                            <option value="">Pilih Kesan</option>
                                            <option value="1 ⭐⭐⭐⭐⭐">⭐⭐⭐⭐⭐</option>
                                            <option value="2 ⭐⭐⭐⭐">⭐⭐⭐⭐</option>
                                            <option value="3 ⭐⭐⭐">⭐⭐⭐</option>
                                            <option value="4 ⭐⭐">⭐⭐</option>
                                            <option value="5 ⭐">⭐</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="Rating_Fasilitas" class="form-label">Rating Fasilitas</label>
                                        <select class="form-select" name="Rating_Fasilitas" id="Rating_Fasilitas">
                                            <option value="">Pilih Rating</option>
                                            <option value="1 ⭐⭐⭐⭐⭐">⭐⭐⭐⭐⭐</option>
                                            <option value="2 ⭐⭐⭐⭐">⭐⭐⭐⭐</option>
                                            <option value="3 ⭐⭐⭐">⭐⭐⭐</option>
                                            <option value="4 ⭐⭐">⭐⭐</option>
                                            <option value="5 ⭐">⭐</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="Rating_Kepuasan" class="form-label">Rating Kepuasan</label>
                                        <select class="form-select" name="Rating_Kepuasan" id="Rating_Kepuasan">
                                            <option value="">Pilih Rating</option>
                                            <option value="1 ⭐⭐⭐⭐⭐">⭐⭐⭐⭐⭐</option>
                                            <option value="2 ⭐⭐⭐⭐">⭐⭐⭐⭐</option>
                                            <option value="3 ⭐⭐⭐">⭐⭐⭐</option>
                                            <option value="4 ⭐⭐">⭐⭐</option>
                                            <option value="5 ⭐">⭐</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" id="btnBatal" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </button>
                                <button type="submit" class="btn btn-bps">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Pengunjung -->
                <div class="card shadow-sm">
                    <div class="card-header bps-theme">
                        <h5 class="mb-0">Daftar Kunjungan Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelPengunjung" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Petugas</th>
                                        <th>Jenis Tamu</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>Nama Instansi</th>
                                        <th>Keperluan</th>
                                        <th>Keterangan Keperluan</th>
                                        <th>No WA Aktif</th>
                                        <th>Pembahasan</th>
                                        <th>Tindak Lanjut</th>
                                        <th>Kesan Pelayanan</th>
                                        <th>Rating Fasilitas</th>
                                        <th>Rating Kepuasan</th>
                                        <th>Catatan Pelayanan</th>
                                        <th>Catatan Fasilitas</th> 
                                        <th>Catatan Kepuasan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                <td>" . $no++ . "</td>
                                                <td>" . date('d/m/Y H:i', strtotime($row['tanggal'])) . "</td>
                                                <td>" . htmlspecialchars($row['petugas']) . "</td>
                                                <td>" . htmlspecialchars($row['Jenis_Tamu']) . "</td>
                                                <td>" . htmlspecialchars($row['nama']) . "</td>
                                                <td>" . htmlspecialchars($row['instansi']) . "</td>
                                                <td>" . htmlspecialchars($row['nama_instansi']) . "</td>
                                                <td>" . htmlspecialchars($row['keperluan']) . "</td>
                                                <td>" . htmlspecialchars($row['keterangan_keperluan'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['No_wa_Aktif']) . "</td>
                                                <td>" . htmlspecialchars($row['pembahasan'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['catatan_lain'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['kesan pelayanan']) . "</td>
                                                <td>" . htmlspecialchars($row['Rating_Fasilitas'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['Rating_Kepuasan'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['catatan_pelayanan'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['catatan_fasilitas'] ?? '-') . "</td>
                                                <td>" . htmlspecialchars($row['catatan_kepuasan'] ?? '-') . "</td>
                                                <td>
                                                    <a href='edit_pengunjung.php?id=" . $row['No_Pengunjung'] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                                    <a href='hapus_pengunjung.php?id=" . $row['No_Pengunjung'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'><i class='fas fa-trash-alt'></i></a>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='19' class='text-center'>Tidak ada data pengunjung</td></tr>";
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
            // Toggle form tambah pengunjung
            $('#btnTambah').click(function() {
                $('#formTambah').slideDown();
                $(this).hide();
            });

            $('#btnBatal').click(function() {
                $('#formTambah').slideUp();
                $('#btnTambah').show();
            });

            // DataTable
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
                }],
                scrollX: true,
                columnDefs: [
                    { width: '80px', targets: 0 }, // No
                    { width: '150px', targets: 1 }, // Tanggal
                    { width: '100px', targets: 2 }, // Petugas
                    { width: '100px', targets: 3 }, // Jenis Tamu
                    { width: '120px', targets: 4 }, // Nama
                    { width: '100px', targets: 18 } // Aksi
                ]
            });

            // Form validation
            $('#formTambahPengunjung').submit(function(e) {
                let isValid = true;
                
                // Reset validation
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                
                // Check required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        $(this).next('.invalid-feedback').show();
                        isValid = false;
                        
                        // Scroll to first error
                        if (isValid === false) {
                            $('html, body').animate({
                                scrollTop: $(this).offset().top - 100
                            }, 500);
                            isValid = null; // Prevent multiple scrolls
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });

            // Real-time validation
            $('input[required], select[required]').on('input change', function() {
                if ($(this).val()) {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').hide();
                }
            });
        });
    </script>
</body>
</html>