<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIGATRA - Sistem Informasi Kunjungan dan Administrasi Tamu Statistik</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bps-green: #0a9e3f;
            --bps-dark-green: #078035;
            --bps-gold: #f6be07;
            --bps-light: #f8f9fa;
            --bps-blue: #005bae;
            --bps-red: #e30613;
        }
        
        body {
            background: linear-gradient(135deg, rgba(10,158,63,0.95), rgba(7,128,53,0.95)), url('https://www.bps.go.id/assets/img/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            padding: 15px 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        .bps-logo, .pst-logo {
            height: 80px;
        }
        
        .title-container {
            flex: 1;
            min-width: 300px;
            text-align: center;
            padding: 0 20px;
        }
        
        .system-title {
            background: linear-gradient(135deg, var(--bps-green), var(--bps-dark-green));
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-left: 5px solid var(--bps-gold);
            position: relative;
            overflow: hidden;
        }
        
        .system-title::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: url('https://www.bps.go.id/assets/img/pattern.png') no-repeat;
            background-size: contain;
            opacity: 0.1;
        }
        
        .system-title h1 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            position: relative;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        
        .system-title p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
        }
        
        .card-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        .card-menu {
            width: 350px;
            border: none;
            border-radius: 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            overflow: hidden;
            border-top: 1px solid rgba(255,255,255,0.2);
            border-left: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }
        
        .card-menu:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            background: rgba(255,255,255,0.2);
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--bps-gold);
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        
        .card-body {
            padding: 30px;
            text-align: center;
        }
        
        .card-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: white;
            font-size: 1.5rem;
        }
        
        .card-text {
            margin-bottom: 25px;
            opacity: 0.9;
            font-size: 1rem;
            min-height: 60px;
        }
        
        .btn-menu {
            background: linear-gradient(45deg, var(--bps-blue), #004a8f);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 500;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,91,174,0.3);
            width: 100%;
            display: block;
            font-size: 1.1rem;
        }
        
        .btn-menu:hover {
            background: linear-gradient(45deg, #004a8f, var(--bps-blue));
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,91,174,0.4);
            color: white;
        }
        
        .btn-admin {
            background: linear-gradient(45deg, var(--bps-gold), #e0a800);
            box-shadow: 0 4px 15px rgba(246,190,7,0.3);
        }
        
        .btn-admin:hover {
            background: linear-gradient(45deg, #e0a800, var(--bps-gold));
            box-shadow: 0 6px 20px rgba(246,190,7,0.4);
        }
        
        .btn-survey {
            background: linear-gradient(45deg, var(--bps-red), #c10510);
            box-shadow: 0 4px 15px rgba(227,6,19,0.3);
        }
        
        .btn-survey:hover {
            background: linear-gradient(45deg, #c10510, var(--bps-red));
            box-shadow: 0 6px 20px rgba(227,6,19,0.4);
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
            font-size: 0.9rem;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .footer .copyright {
            font-weight: 500;
            color: var(--bps-gold);
        }
        
        @media (max-width: 992px) {
            .header-container {
                flex-direction: column;
                text-align: center;
            }
            
            .logo-container {
                justify-content: center;
                margin-bottom: 20px;
            }
            
            .title-container {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .bps-logo, .pst-logo {
                height: 60px;
            }
            
            .system-title h1 {
                font-size: 1.6rem;
            }
            
            .card-menu {
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>
<body>
    <!-- Header with Logos and Title -->
<div class="header-container">
    <img src="images/logo pst.png" alt="PST Logo" class="logo">
    
<div class="title-container">
    <div class="system-title">
        <h1><i class="fas fa-chart-line me-2"></i>SILABUS BPS Kab. Tasikmalaya</h1>
        <p>SISTEM PELAPORAN BUKU TAMU PELAYANAN STATISTIK</p>
    </div>
</div>

<style>
.title-container {
    flex: 1;
    padding: 40px 100px; /* Diperbesar (atas-bawah 30px, kiri-kanan 40px) */
    text-align: center;
    margin: 0 20px; /* Memberi jarak dari logo */
}

.system-title {
    background: linear-gradient(135deg,hsl(141, 88.10%, 32.90%), #078035);
    color: white;
    padding: 30px 40px; /* Diperbesar */
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    border-left: 6px solid #135deg;
    min-height: 120px; /* Tinggi minimum */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.system-title h1 {
    font-weight: 700;
    font-size: 2.2rem;
    margin-bottom: 15px; /* Jarak ke paragraf diperbesar */
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
}

.system-title p {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 0;
    opacity: 0.9;
}
</style>
    <img src="images/logo bps.png" alt="BPS Logo" class="logo">
</div>
        <!-- Menu Cards -->
        <div class="card-container">
            <!-- Tamu Umum Card -->
            <div class="card-menu">
                <div class="card-body">
                    <div class="card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="card-title">Buku Tamu Umum</h3>
                    <p class="card-text">Isi buku tamu untuk pengunjung BPS Kabupaten Tasikmalaya</p>
                    <a href="BukutamuUmum.php" class="btn btn-menu">
                        <i class="fas fa-sign-in-alt me-2"></i>Isi Buku Tamu
                    </a>
                </div>
            </div>
            
            <!-- Survei Kebutuhan Data Card -->
            <div class="card-menu">
                <div class="card-body">
                    <div class="card-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="card-title">Survei Kebutuhan Data</h3>
                    <p class="card-text">Isi formulir kebutuhan data statistik untuk penelitian/kegiatan Anda</p>
                    <a href="https://skd.bps.go.id/SKD2025/web/entri/responden/blok1?token=HRmhlwqR4rVKy68IzR9GNRPC_Ky4DOub4CpB-icHgbkdLc-APf31gyurdiyzpAMShOR0THYJchMTSz5ov8IsvrDxUAWI5ILs-xrc" target="_blank" class="btn btn-menu btn-survey">
                        <i class="fas fa-edit me-2"></i>Isi Survei
                    </a>
                </div>
            </div>
            
            <!-- Admin Card -->
            <div class="card-menu">
                <div class="card-body">
                    <div class="card-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 class="card-title">Login Petugas</h3>
                    <p class="card-text">Akses sistem untuk administrator dan petugas BPS</p>
                    <a href="login.php" class="btn btn-menu btn-admin">
                        <i class="fas fa-lock me-2"></i>Login Petugas
                    </a>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p class="copyright">2025 BPS Kabupaten Tasikmalaya - SILABUS v1.1</p>
            <p>Badan Pusat Statistik Kabupaten Tasikmalaya</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>