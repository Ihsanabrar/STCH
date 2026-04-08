<?php
session_start();
unset($_SESSION['guest_data']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reservasi Tabrani Guest House</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Form Reservasi</h1>
        <form action="pilihkamar.php" method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Judul Panggilan:</label>
            <select name="title">
                <option>Tuan</option><option>Nyonya</option>
                <option>Nona</option><option>Dr</option>
            </select>

            <label>Nama Depan:</label>
            <input type="text" name="first_name" required>

            <label>Nama Belakang:</label>
            <input type="text" name="last_name" required>

            <label>Nomor Telepon:</label>
            <input type="tel" name="phone" required>

            <label>Check-in:</label>
            <input type="date" name="check_in" required min="<?= date('Y-m-d') ?>">

            <label>Check-out:</label>
            <input type="date" name="check_out" required>

            <button type="submit">Check Ketersediaan</button>
        </form>
    </div>
</body>
</html>