<?php
session_start();
include 'db_config.php';

if (!isset($_GET['id'])) {
    header('Location: reservasi.php');
    exit;
}

$booking_id = (int)$_GET['id'];
$sql = "SELECT b.*, r.room_name 
        FROM bookings b JOIN rooms r ON b.room_id = r.id 
        WHERE b.id = $booking_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) die("Booking tidak ditemukan.");
$booking = $result->fetch_assoc();

// Nomor admin (ganti dengan nomor tujuan)
$admin_phone = '6282387037225'; // format internasional tanpa '+'
$message = "Halo Admin, ada booking baru:%0A";
$message .= "Nama: {$booking['guest_name']}%0A";
$message .= "Email: {$booking['guest_email']}%0A";
$message .= "No. HP: {$booking['guest_phone']}%0A";
$message .= "Check-in: {$booking['check_in']}%0A";
$message .= "Check-out: {$booking['check_out']}%0A";
$message .= "Kamar: {$booking['room_name']}%0A";
$message .= "Total Harga: Rp " . number_format($booking['total_price'],0,',','.') . "%0A";
$message .= "Silakan konfirmasi.";
$wa_link = "https://wa.me/$admin_phone?text=$message";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Booking Berhasil Disimpan!</h1>
        <div class="booking-detail">
            <p><strong>Nama:</strong> <?= $booking['guest_name'] ?></p>
            <p><strong>Email:</strong> <?= $booking['guest_email'] ?></p>
            <p><strong>Telepon:</strong> <?= $booking['guest_phone'] ?></p>
            <p><strong>Check-in:</strong> <?= $booking['check_in'] ?></p>
            <p><strong>Check-out:</strong> <?= $booking['check_out'] ?></p>
            <p><strong>Kamar:</strong> <?= $booking['room_name'] ?></p>
            <p><strong>Total:</strong> Rp <?= number_format($booking['total_price'],0,',','.') ?></p>
        </div>
        <a href="<?= $wa_link ?>" class="btn" target="_blank">Kirim ke Admin via WhatsApp</a>
        <a href="cancel.php?id=<?= $booking_id ?>" class="btn btn-cancel" onclick="return confirm('Batalkan pemesanan?')">Batalkan Pemesanan</a>
        <br><br>
        <a href="../catalog.html">Kembali ke Beranda</a>
    </div>
</body>
</html>