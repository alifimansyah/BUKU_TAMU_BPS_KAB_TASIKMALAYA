<?php
include 'config/koneksi.php';

// Ambil data operator yang sedang online (status = 1/true)
$query_petugas = mysqli_query($koneksi, "SELECT * FROM tb_operator WHERE status=1 LIMIT 1");
$petugas = mysqli_fetch_assoc($query_petugas);

if ($petugas) {
    $nama_petugas = $petugas['Nama'];
    $nip_petugas = $petugas['NIP'];
    $foto_petugas = !empty($petugas['foto']) ? $petugas['foto'] : 'assets/img/default-user.png';
} else {
    $nama_petugas = '';
    $nip_petugas = '';
    $foto_petugas = 'assets/img/default-user.png';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Umum - BPS Kabupaten Tasikmalaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --bps-green: #0a9e3f;
            --bps-dark-green: #078035;
            --bps-gold: #f6be07;
            --bps-light: #f8f9fa;
            --bps-blue: #005bae;
            --bps-red: #e30613;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('assets/img/bps-pattern.png');
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-logo img {
            height: 80px;
            margin-bottom: 10px;
        }

        .guest-form {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin: 30px auto;
            border-top: 5px solid var(--bps-gold);
        }

        .form-header {
            background: linear-gradient(135deg, var(--bps-green), var(--bps-dark-green));
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .form-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .officer-info {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: rgba(10, 158, 63, 0.1);
            border-bottom: 1px solid #eee;
        }

        .officer-photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--bps-gold);
            margin-right: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .officer-details {
            flex: 1;
        }

        .officer-name {
            font-weight: 600;
            font-size: 18px;
            color: var(--bps-dark-green);
            margin-bottom: 5px;
        }

        .officer-nip {
            font-size: 14px;
            color: #666;
        }

        .current-time {
            background-color: var(--bps-gold);
            color: #333;
            padding: 10px;
            text-align: center;
            font-weight: 600;
            font-size: 18px;
        }

        form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--bps-dark-green);
        }

        label i {
            margin-right: 8px;
            color: var(--bps-green);
        }

        input[type="text"],
        input[type="tel"],
        input[type="datetime-local"],
        select,
        textarea {
            width: 100%;
            padding: 14px 20px;
            border: 2px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            transition: all 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--bps-green);
            box-shadow: 0 0 0 3px rgba(10, 158, 63, 0.1);
            outline: none;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit,
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 30px;
            border: none;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--bps-green), var(--bps-dark-green));
            color: white;
            margin-right: 15px;
            box-shadow: 0 4px 15px rgba(10, 158, 63, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(10, 158, 63, 0.4);
        }

        .btn-back {
            background-color: white;
            color: var(--bps-dark-green);
            border: 2px solid var(--bps-green);
        }

        .btn-back:hover {
            background-color: #f0f0f0;
        }

        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        small {
            display: block;
            margin-top: 5px;
            font-size: 13px;
            color: #777;
        }

        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: var(--border-radius);
            transition: all 0.3s;
            cursor: pointer;
        }

        .radio-option:hover {
            border-color: var(--bps-green);
            background-color: rgba(10, 158, 63, 0.05);
        }

        .radio-option input {
            margin-right: 10px;
            accent-color: var(--bps-green);
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .form-header h2 {
                font-size: 22px;
            }

            .officer-info {
                flex-direction: column;
                text-align: center;
            }

            .officer-photo {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .form-footer {
                flex-direction: column;
            }

            .btn-submit,
            .btn-back {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }

        /* Animasi */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: fadeIn 0.5s ease forwards;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.4s;
        }

        .form-group:nth-child(5) {
            animation-delay: 0.5s;
        }

        .form-group:nth-child(6) {
            animation-delay: 0.6s;
        }

        .form-group:nth-child(7) {
            animation-delay: 0.7s;
        }

        .form-group:nth-child(8) {
            animation-delay: 0.8s;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="header-logo">
            <img src="uploads/logo pst (1).svg" alt="BPS Logo">
            <h2>BUKU TAMU UMUM</h2>
        </div>

        <!-- Buku Tamu Form -->
        <div class="guest-form">
            <div class="form-header">
                <h2><i class="fas fa-book-open"></i> FORMULIR KUNJUNGAN</h2>
                <p>BPS Kabupaten Tasikmalaya</p>
            </div>

            <div class="current-time" id="realTimeClock">
                <!-- Waktu akan diupdate oleh JavaScript -->
            </div>

            <!-- Menampilkan operator login atau fallback -->
            <div class="officer-info">
                <img src="<?= $foto_petugas ?>" alt="Foto Operator" class="officer-photo">
                <div class="officer-details">
                    <?php if ($petugas): ?>
                        <div class="officer-name"><i class="fas fa-user-tie"></i> <?= htmlspecialchars($nama_petugas) ?></div>
                        <div class="officer-nip"><i class="fas fa-id-card"></i> NIP: <?= htmlspecialchars($nip_petugas) ?></div>
                    <?php else: ?>
                        <div class="officer-name"><i class="fas fa-exclamation-triangle"></i> Petugas Piket Tidak Tersedia</div>
                        <div class="officer-nip">Silakan isi buku tamu secara mandiri</div>
                    <?php endif; ?>
                </div>
            </div>

            <form action="submit_umum.php" method="POST">
                <input type="hidden" name="tanggal" value="<?= $current_datetime ?>">

                <div class="form-group">
                    <label for="Jenis_Tamu"><i class="fas fa-users"></i> Jenis Tamu</label>
                    <select id="Jenis_Tamu" name="Jenis_Tamu" required>
                        <option value="">Pilih Jenis Tamu</option>
                        <option value="umum">Tamu Umum</option>
                        <option value="PST">Tamu PST</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group">
                    <label for="instansi"><i class="fas fa-building"></i> Asal Tamu</label>
                    <select id="instansi" name="instansi" required>
                        <option value="">Pilih Asal Tamu</option>
                        <option value="Pemerintah">Instansi/Dinas/Lembaga Pemerintah</option>
                        <option value="Swasta">Perusahaan Swasta</option>
                        <option value="Pendidikan">Pelajar/Mahasiswa</option>
                        <option value="Perorangan">Masyarakat Umum</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_instansi"><i class="fas fa-landmark"></i> Nama Instansi/Dinas/Lembaga Pemerintah/Perusahaan Swasta/Mahasiswa</label>
                    <input type="text" id="nama_instansi" name="nama_instansi" placeholder="Masukkan nama lengkap instansi/institusi" required>
                </div>

                <div class="form-group">
                    <label for="keperluan"><i class="fas fa-tasks"></i> Keperluan Kunjungan</label>
                    <select id="keperluan" name="keperluan" required>
                        <option value="" disabled selected>Pilih keperluan kunjungan</option>
                        <option value="Konsultasi Statistik">Konsultasi Data Statistik</option>
                        <option value="Perpustakaan">Layanan Perpustakaan</option>
                        <option value="Rekomendasi kegiatan statistik">Pelayanan Rekomendasi Kegiatan Statistik</option>
                        <option value="Pengaduan">Pengaduan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="keterangan_keperluan"><i class="fas fa-edit"></i> Keterangan Keperluan</label>
                    <textarea id="keterangan_keperluan" name="keterangan_keperluan" placeholder="Jelaskan secara detail keperluan kunjungan Anda" required></textarea>
                </div>

                <div class="form-group">
                    <label for="wa"><i class="fab fa-whatsapp"></i> Nomor WhatsApp Aktif</label>
                    <input type="tel" id="wa" name="No_wa_Aktif" placeholder="Contoh: 8123456789" pattern="[0-9]{9,13}" required>
                    <small>Masukkan nomor tanpa 0 di depan (contoh: 8123456789)</small>
                </div>


                <div class="form-footer">
                    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> SIMPAN DATA</button>
                    <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> KEMBALI</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update waktu secara real-time
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('realTimeClock').textContent =
                now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Auto uppercase untuk field tertentu
        document.getElementById('nama')?.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        document.getElementById('nama_instansi')?.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Validasi nomor WA
        document.getElementById('wa')?.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Efek interaktif untuk radio button
        document.querySelectorAll('.radio-option input').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.radio-option').forEach(opt => {
                    opt.style.borderColor = '#ddd';
                    opt.style.backgroundColor = 'transparent';
                });
                if (this.checked) {
                    this.closest('.radio-option').style.borderColor = 'var(--bps-green)';
                    this.closest('.radio-option').style.backgroundColor = 'rgba(10,158,63,0.1)';
                }
            });
        });
    </script>
</body>

</html>