<?php
session_start();
include 'config/koneksi.php';

// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Ambil NIP dari URL
$nip = isset($_GET['nip']) ? mysqli_real_escape_string($koneksi, $_GET['nip']) : '';
if (empty($nip)) {
    header("Location: Profil Pegawai.php");
    exit();
}

// Ambil data pegawai
$query = mysqli_query($koneksi, "SELECT * FROM tb_operator WHERE NIP = '$nip'");
$pegawai = mysqli_fetch_assoc($query);

if (!$pegawai) {
    header("Location: Profil Pegawai.php");
    exit();
}

// Daftar hari yang tersedia
$all_days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $new_nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    
    // Handle multiple days selection
    $selected_days = isset($_POST['hari']) ? $_POST['hari'] : [];
    $hari = implode(',', $selected_days);
    
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    // Determine status automatically based on selected days
    $current_day = date('N'); // 1 (Monday) through 7 (Sunday)
    $day_names = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $current_day_name = $day_names[$current_day - 1];
    $status = in_array($current_day_name, $selected_days) ? 1 : 0;
    
    // Cek NIP dan username sudah ada atau belum (kecuali milik sendiri)
    $check_nip = mysqli_query($koneksi, "SELECT NIP FROM tb_operator WHERE NIP = '$new_nip' AND NIP != '$nip'");
    $check_username = mysqli_query($koneksi, "SELECT username FROM tb_operator WHERE username = '$username' AND NIP != '$nip'");
    
    if (mysqli_num_rows($check_nip) > 0) {
        $error = "NIP sudah digunakan!";
    } elseif (mysqli_num_rows($check_username) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Handle upload foto
        $foto = $pegawai['foto']; // Keep existing foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $foto_name = time() . '_' . $_FILES['foto']['name'];
                $foto_path = $upload_dir . $foto_name;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
                    // Hapus foto lama jika ada
                    if ($pegawai['foto'] && file_exists($pegawai['foto'])) {
                        unlink($pegawai['foto']);
                    }
                    $foto = $foto_path;
                }
            }
        }
        
        // Update query
        $update_query = "UPDATE tb_operator SET 
                         NIP='$new_nip', 
                         username='$username', 
                         Nama='$nama', 
                         foto='$foto', 
                         Hari='$hari', 
                         Role='$role', 
                         Status=$status";
        
        // Update password jika diisi
        if (!empty($_POST['password'])) {
            $password = mysqli_real_escape_string($koneksi, $_POST['password']);
            $hashed_password = md5($password);
            $update_query .= ", password='$hashed_password'";
        }
        
        $update_query .= " WHERE NIP = '$nip'";
        
        if (mysqli_query($koneksi, $update_query)) {
            $success = "Data pegawai berhasil diperbarui!";
            // Update NIP variable jika berubah
            $nip = $new_nip;
            // Refresh data pegawai
            $query = mysqli_query($koneksi, "SELECT * FROM tb_operator WHERE NIP = '$nip'");
            $pegawai = mysqli_fetch_assoc($query);
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pegawai - BPS Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .day-checkbox {
            display: inline-block;
            margin-right: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4><i class="fas fa-user-edit"></i> Edit Data Pegawai</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                                <br><a href="Profil Pegawai.php" class="btn btn-sm btn-success mt-2">Kembali ke Daftar Pegawai</a>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?= htmlspecialchars($pegawai['Nama']) ?>" maxlength="30" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" class="form-control" id="nip" name="nip" 
                                               value="<?= htmlspecialchars($pegawai['NIP']) ?>" maxlength="10" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?= htmlspecialchars($pegawai['username']) ?>" maxlength="15" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                            <label class="form-label">Hari Piket</label>
                            <div class="row">
                                <?php 
                                $current_days = !empty($pegawai['Hari']) ? explode(',', $pegawai['Hari']) : [];
                                foreach ($all_days as $day): 
                                    $checked = in_array($day, $current_days) ? 'checked' : '';
                                ?>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="hari_<?= strtolower($day) ?>" 
                                            name="hari[]" value="<?= htmlspecialchars($day) ?>" <?= $checked ?>>
                                        <label class="form-check-label" for="hari_<?= strtolower($day) ?>">
                                            <?= htmlspecialchars($day) ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <small class="text-muted">Centang hari-hari ketika pegawai bertugas (bisa pilih lebih dari satu)</small>
                        </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="">Pilih Role</option>
                                            <option value="adminPST" <?= $pegawai['Role'] == 'adminPST' ? 'selected' : '' ?>>Admin PST</option>
                                            <option value="Petugas PST" <?= $pegawai['Role'] == 'Petugas PST' ? 'selected' : '' ?>>Petugas PST</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-control-plaintext">
                                            <?php 
                                            $current_day = date('N');
                                            $day_names = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                            $current_day_name = $day_names[$current_day - 1];
                                            $is_active = in_array($current_day_name, $current_days);
                                            ?>
                                            Status akan otomatis <?= $is_active ? 'Aktif' : 'Non-Aktif' ?> (hari ini <?= $current_day_name ?>)
                                        </div>
                                        <input type="hidden" name="status" value="<?= $is_active ? 1 : 0 ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Kosongkan jika tidak ingin mengubah)</small>
                                <?php if ($pegawai['foto']): ?>
                                    <div class="mt-2">
                                        <img src="<?= $pegawai['foto'] ?>" alt="Current photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="Profil Pegawai.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>