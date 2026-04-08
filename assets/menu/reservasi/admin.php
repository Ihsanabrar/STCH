<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: admin_login.php');
    exit;
}
include 'db_config.php';

// Ambil semua booking (termasuk yang sudah confirmed/pending)
$sql = "SELECT b.*, r.room_name, r.room_number 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        ORDER BY b.check_in ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manajemen Pemesanan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #C5A059; color: white; }
        .btn-hapus { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .btn-hapus:hover { background: #c82333; }
        .logout { float: right; background: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manajemen Pemesanan <a href="admin_login.php?logout=1" class="btn logout" style="float:right;">Logout</a></h1>
        <p>Berikut adalah daftar semua pemesanan. Klik <strong>Hapus</strong> untuk membatalkan pesanan dan mengosongkan kamar.</p>

        <?php if ($result->num_rows == 0): ?>
            <p>Tidak ada pemesanan.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>ID</th><th>Nama Tamu</th><th>Email</th><th>Telepon</th>
                        <th>Kamar</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['guest_name']) ?></td>
                        <td><?= htmlspecialchars($row['guest_email']) ?></td>
                        <td><?= htmlspecialchars($row['guest_phone']) ?></td>
                        <td><?= $row['room_name'] ?> (<?= $row['room_number'] ?>)</td>
                        <td><?= $row['check_in'] ?></td>
                        <td><?= $row['check_out'] ?></td>
                        <td>Rp <?= number_format($row['total_price'],0,',','.') ?></td>
                        <td><a href="admin_delete.php?id=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus pesanan ini? Kamar akan tersedia kembali.')">Hapus</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <br>
        <a href="../reservasi/reservasi.php" class="btn">Kembali ke Halaman Reservasi</a>
    </div>
</body>
</html>