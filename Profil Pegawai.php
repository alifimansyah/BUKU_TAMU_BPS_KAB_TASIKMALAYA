<?php
session_start();
include 'config/koneksi.php';

// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pegawai dan urutkan berdasarkan hari
$query = mysqli_query($koneksi, "SELECT * FROM tb_operator ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')");
if (!$query) {
    die('Query Error: ' . mysqli_error($koneksi));
}

$pegawai = [];
while ($row = mysqli_fetch_assoc($query)) {
    $pegawai[] = $row;
}
if (empty($pegawai)) {
    echo "Tidak ada data pegawai.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Petugas Piket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #eef5f9;
            margin: 0;
        }

        header {
            background-color: #004080;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #ffcc00;
        }

        .container {
            padding: 30px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease;
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
            margin-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<header>
    <h1>Profil Petugas Piket BPS</h1>
    <div class="nav-links">
        <a href="laporan.php">📊 Dashboard</a>
    </div>
</header>

<div class="container">
    <div class="grid-container">
        <?php foreach ($pegawai as $p): ?>
            <div class="card">
                <img src="<?= $p['foto'] ? $p['foto'] : 'assets/default.png' ?>" alt="foto <?= htmlspecialchars($p['Nama']) ?>">
                <h3><?= htmlspecialchars($p['Nama']) ?></h3>
                <p><strong>Username:</strong> <?= htmlspecialchars($p['username']) ?></p>
                <p><strong>NIP:</strong> <?= htmlspecialchars($p['NIP']) ?></p>
                <div class="hari-piket">Hari Piket: <?= htmlspecialchars($p['Hari']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
