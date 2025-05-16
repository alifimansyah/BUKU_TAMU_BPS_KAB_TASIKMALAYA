<?php
session_start();

error_log("Session before destroy: " . session_id());

session_unset();
session_destroy();  // Hancurkan sesi

// Debugging: Cek apakah sesi sudah dihancurkan
error_log("Session after destroy: " . session_id());

header('Location: index.php');  // Arahkan ke halaman login
exit;

// https://github.com/Madleyym/Rental-Mobil/blob/main/logout.php