<?php
session_start();
include 'config/koneksi.php';

// Proses Tambah Data Pengunjung
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_pengunjung'])) {
    $tanggal = date('Y-m-d H:i:s');
    $jenis_tamu = $_POST['jenis_tamu'];
    $nama = $_POST['nama'];
    $instansi = $_POST['instansi'];
    $nama_instansi = $_POST['nama_instansi'];
    $keperluan = $_POST['keperluan'];
    $keterangan_keperluan = $_POST['keterangan_keperluan'] ?? null;
    $no_wa = $_POST['no_wa'];
    $kesan = $_POST['kesan'];

    $query = "INSERT INTO tamu_umum (tanggal, Jenis_Tamu, nama, instansi, nama_instansi, keperluan, keterangan_keperluan, No_wa_Aktif, `kesan pelayanan`) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssssssss", $tanggal, $jenis_tamu, $nama, $instansi, $nama_instansi, $keperluan, $keterangan_keperluan, $no_wa, $kesan);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data pengunjung berhasil ditambahkan";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan data pengunjung: " . mysqli_error($koneksi);
    }
}

// Query untuk menampilkan data
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
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
                    <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar me-2"></i> Laporan & Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Profil Pegawai.php"><i class="fas fa-cog me-2"></i> Profil Pegawai</a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users me-2"></i>Data Pengunjung</h2>
                <button id="btnTambah" class="btn btn-bps">
                    <i class="fas fa-plus me-1"></i> Tambah Pengunjung
                </button>
            </div>

            <!-- Form Tambah Pengunjung (Awalnya Disembunyikan) -->
            <div id="formTambah" class="form-tambah card shadow-sm">
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
                                    <option value="Umum">Umum</option>
                                    <option value="Instansi">Instansi</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Peneliti">Peneliti</option>
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
                                    <option value="">Pilih Instansi</option>
                                    <option value="Pemerintah">Pemerintah</option>
                                    <option value="Swasta">Swasta</option>
                                    <option value="Pendidikan">Pendidikan</option>
                                    <option value="Lainnya">Lainnya</option>
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
                                    <option value="">Pilih Keperluan</option>
                                    <option value="Konsultasi">Konsultasi</option>
                                    <option value="Pengambilan Data">Pengambilan Data</option>
                                    <option value="Penelitian">Penelitian</option>
                                    <option value="Lainnya">Lainnya</option>
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
                                <label for="kesan" class="form-label">Kesan Pelayanan</label>
                                <select class="form-select" id="kesan" name="kesan" required>
                                    <option value="">Pilih Kesan</option>
                                    <option value="Sangat Puas">Sangat Puas</option>
                                    <option value="Puas">Puas</option>
                                    <option value="Cukup">Cukup</option>
                                    <option value="Kurang Puas">Kurang Puas</option>
                                </select>
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
                                    <th>Jenis Tamu</th>
                                    <th>Nama</th>
                                    <th>Instansi</th>
                                    <th>Nama Instansi</th>
                                    <th>Keperluan</th>
                                    <th>Keterangan Keperluan</th>
                                    <th>No WA Aktif</th>
                                    <th>Kesan Pelayanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>".$no++."</td>
                                            <td>".htmlspecialchars($row['tanggal'])."</td>
                                            <td>".htmlspecialchars($row['Jenis_Tamu'])."</td>
                                            <td>".htmlspecialchars($row['nama'])."</td>
                                            <td>".htmlspecialchars($row['instansi'])."</td>
                                            <td>".htmlspecialchars($row['nama_instansi'])."</td>
                                            <td>".htmlspecialchars($row['keperluan'])."</td>
                                            <td>".htmlspecialchars($row['keterangan_keperluan'] ?? '-')."</td>
                                            <td>".htmlspecialchars($row['No_wa_Aktif'])."</td>
                                            <td>".htmlspecialchars($row['kesan pelayanan'])."</td>
                                            <td>
                                                <a href='edit_pengunjung.php?id=".$row['No_Pengunjung']."' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                                <a href='hapus_pengunjung.php?id=".$row['No_Pengunjung']."' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'><i class='fas fa-trash-alt'></i></a>
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
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Data_Pengunjung_BPS_Tasikmalaya',
                    text: '<i class="fas fa-file-excel"></i> Ekspor ke Excel',
                    className: 'btn btn-success mb-3'
                }
            ]
        });

        // Validasi form
        $('form').submit(function(e) {
            let valid = true;
            
            $('[required]').each(function() {
                if ($(this).val() === '') {
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi!');
            }
        });
    });
</script>
</body>
</html>