<?php
session_start();
include 'config/koneksi.php';

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
                    <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> isi buku tamu</a>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="table pengunjung.php"><i class="fas fa-users me-2"></i> Data Pengunjung</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users me-2"></i>Data Pengunjung</h2>
                <a href="tambah_pengunjung.php" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Pengunjung
                </a>
            </div>

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
                                    echo "<tr><td colspan='10' class='text-center'>Tidak ada data pengunjung</td></tr>";
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
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Data_Pengunjung_BPS_Tasikmalaya',
                    text: '<i class="fas fa-file-excel"></i> Ekspor ke Excel',
                    className: 'btn btn-success mb-3'
                }
            ]
        });
    });
</script>
</body>
</html>
