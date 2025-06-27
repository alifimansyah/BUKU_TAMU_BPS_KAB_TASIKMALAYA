<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SILABUS - Sistem Informasi Kunjungan dan Administrasi Tamu Statistik</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bps-green: #0a9e3f;
            --bps-dark-green: #078008;
            --bps-gold: #f6be07;
            --bps-light-blue: #e6f2ff;
            --bps-blue: #005bae;
            --bps-red: #e30613;
            --bps-orange: #ff9800;
            --bps-dark-orange: #ff5c00;
            --bps-white: #ffffff;
        }

        body {
            background-color: var(--bps-white);
            font-family: 'Poppins', sans-serif;
            color: #333;
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
            background: var(--bps-white);
            border-radius: 15px;
            padding: 15px 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .header-container::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path fill="%23078035" d="M50 0L100 50L50 100L0 50Z"/></svg>') no-repeat;
            background-size: contain;
            opacity: 0.1;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 80px;
            width: auto;
            object-fit: contain;
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
            padding: 25px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid var(--bps-gold);
            position: relative;
            overflow: hidden;
        }

        .system-title::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><path fill="%23ffffff" d="M50 0L100 50L50 100L0 50Z"/></svg>') no-repeat;
            background-size: contain;
        }

        .system-title h1 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            position: relative;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .system-title p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            font-weight: 500;
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
            background: var(--bps-white);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            border-left: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--bps-green);
        }

        .card-body {
            padding: 30px;
            text-align: center;
        }

        .card-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--bps-dark-green);
            font-size: 1.5rem;
        }

        .card-text {
            margin-bottom: 25px;
            color: #666;
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
            box-shadow: 0 4px 15px rgba(0, 91, 174, 0.1);
            width: 100%;
            display: block;
            font-size: 1.1rem;
        }

        .btn-menu:hover {
            background: linear-gradient(45deg, #004a8f, var(--bps-blue));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 91, 174, 0.15);
            color: white;
        }

        .btn-admin {
            background: linear-gradient(45deg, var(--bps-gold), #e0a800);
            box-shadow: 0 4px 15px rgba(246, 190, 7, 0.1);
        }

        .btn-admin:hover {
            background: linear-gradient(45deg, #e0a800, var(--bps-gold));
            box-shadow: 0 6px 20px rgba(246, 190, 7, 0.15);
        }

        .btn-nilai {
            background: linear-gradient(45deg, var(--bps-orange), var(--bps-dark-orange));
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.1);
        }

        .btn-nilai:hover {
            background: linear-gradient(45deg, var(--bps-dark-orange), var(--bps-orange));
            box-shadow: 0 6px 20px rgba(227, 6, 19, 0.15);
        }

        .btn-survey {
            background: linear-gradient(45deg, var(--bps-green), var(--bps-dark-green));
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.1);
        }

        .btn-survey:hover {
            background: linear-gradient(45deg, var(--bps-dark-green), var(--bps-green));
            box-shadow: 0 6px 20px rgba(227, 6, 19, 0.15);
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 10px;
            font-size: 0.9rem;
            color: #666;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .footer .copyright {
            font-weight: 500;
            color: var(--bps-green);
        }

        /* Dark/Light mode toggle */
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            background: var(--bps-white);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(76, 140, 229, 0.1);
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.dark-mode {
            background-color: #121212;
            color: #f1f1f1;
        }

        body.dark-mode .header-container,
        body.dark-mode .card-menu,
        body.dark-mode .footer {
            background-color: #1e1e1e;
            color: #f1f1f1;
        }

        body.dark-mode .card-menu {
            border-color: rgba(6, 185, 75, 0.1);
        }

        body.dark-mode .card-title {
            color: var(--bps-gold);
        }

        body.dark-mode .card-text {
            color: #ccc;
        }

        body.dark-mode .footer {
            background: rgba(255, 255, 255, 0.05);
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
            .logo {
                height: 60px;
            }

            .system-title h1 {
                font-size: 1.6rem;
            }

            .system-title {
                padding: 20px;
            }

            .card-menu {
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header with Logos and Title -->
        <div class="header-container">
            <div class="logo-container d-flex align-items-center justify-content-between">
                <!-- BPS Logo on left -->
                <img src="images/logo_BPS.png" alt="BPS Logo" class="logo">

                <!-- Centered Title -->
                <div class="title-container mx-4">
                    <div class="system-title text-center">
                        <h1 class="d-flex justify-content-center align-items-center">
                            <i class="fas fa-chart-line me-2"></i>SILABUS BPS KABUPATEN TASIKMALAYA
                        </h1>
                        <p class="mb-0">SISTEM PELAPORAN BUKU TAMU PELAYANAN STATISTIK</p>
                    </div>
                </div>

                <!-- PST Logo on right -->
                <img src="images/logo_pst.png " alt="PST Logo" class="logo">
            </div>
        </div>
    </div>

    <style>
        .logo-container {
            width: 100%;
            gap: 20px;
        }

        .logo {
            height: 80px;
            width: auto;
            flex-shrink: 0;
        }

        .title-container {
            flex-grow: 1;
            min-width: 300px;
        }

        .system-title {
            background: linear-gradient(135deg, #0a9e3f, #078035);
            color: white;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #f6be07;
        }

        @media (max-width: 768px) {
            .logo-container {
                flex-direction: column;
            }

            .logo {
                height: 60px;
            }
        }
    </style>
<style>
    /* ... (keep all existing styles) ... */

    /* Update button colors for each card */
    .btn-tamu-umum {
        background: linear-gradient(45deg, #2b5876, #4e4376);
        box-shadow: 0 4px 15px rgba(43, 88, 118, 0.2);
    }
    .btn-tamu-umum:hover {
        background: linear-gradient(45deg, #4e4376, #2b5876);
        box-shadow: 0 6px 20px rgba(43, 88, 118, 0.3);
    }

    .btn-survey {
        background: linear-gradient(45deg, #11998e, #38ef7d);
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.2);
    }
    .btn-survey:hover {
        background: linear-gradient(45deg, #38ef7d, #11998e);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.3);
    }

    .btn-nilai {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.2);
    }
    .btn-nilai:hover {
        background: linear-gradient(45deg, #ff4b2b, #ff416c);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.3);
    }

    .btn-admin {
        background: linear-gradient(45deg, #f46b45, #eea849);
        box-shadow: 0 4px 15px rgba(244, 107, 69, 0.2);
    }
    .btn-admin:hover {
        background: linear-gradient(45deg, #eea849, #f46b45);
        box-shadow: 0 6px 20px rgba(244, 107, 69, 0.3);
    }
</style>

    <!-- Menu Cards -->
    <div class="card-container">
        <!-- Tamu Umum Card -->
        <div class="card-menu">
            <div class="card-body">
                <div class="card-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="card-title">Buku Tamu</h3>
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

        <!-- Beri Penilaian -->
        <div class="card-menu">
            <div class="card-body">
                <div class="card-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="card-title">Beri Penilaian</h3>
                <p class="card-text">Beri penilaian terhadap layanan BPS Kabupaten Tasikmalaya</p>
                <a href="form_penilaian.php" target="_blank" class="btn btn-menu btn-nilai">
                    <i class="fas fa-star-half-alt me-2"></i>Beri Penilaian
                </a>
            </div>
        </div>

         <div class="card-menu">
    <div class="card-body">
        <div class="card-icon">
            <i class="fas fa-clipboard-list"></i> <!-- Changed from fa-star to fa-clipboard-list -->
        </div>
        <h3 class="card-title">Login Admin</h3>
        <p class="card-text">Akses ini Hanya diperuntukan untuk Admin</p> <!-- Updated text -->
        <a href="login Admin.php" target="_blank" class="btn btn-menu btn-nilai">
            <i class="fas fa-edit me-2"></i>Login Admin <!-- Changed icon to fa-edit -->
        </a>
    </div>
</div>

    <!-- Footer -->
    <div class="footer">
        <p class="copyright">Copyright by BPS KABUPATEN TASIKMALAYA 2025 - SILABUS v1.1</p>
    
    </div>
    </div>

    <!-- Theme Toggle Button -->
    <div class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        // Check for saved theme preference
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            body.classList.add(currentTheme);
            updateToggleIcon();
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark-mode' : '');
            updateToggleIcon();
        });

        function updateToggleIcon() {
            const icon = themeToggle.querySelector('i');
            if (body.classList.contains('dark-mode')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }

        // Replace with your actual SVG paths for logos
        document.addEventListener('DOMContentLoaded', function() {
            // You can load SVG logos here if needed
        });
    </script>
</body>

</html>