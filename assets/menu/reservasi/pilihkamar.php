<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['guest_data'] = [
        'email' => $_POST['email'],
        'title' => $_POST['title'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'phone' => $_POST['phone'],
        'check_in' => $_POST['check_in'],
        'check_out' => $_POST['check_out']
    ];
} else {
    header('Location: reservasi.php');
    exit;
}

$check_in = $_SESSION['guest_data']['check_in'];
$check_out = $_SESSION['guest_data']['check_out'];
if (strtotime($check_out) <= strtotime($check_in)) die("Error: Check-out harus setelah check-in.");

$nights = (strtotime($check_out) - strtotime($check_in)) / 86400;

$sql = "SELECT r.*, 
       (SELECT COUNT(*) FROM bookings b 
        WHERE b.room_id = r.id AND b.status IN ('pending','confirmed') 
        AND b.check_in < '$check_out' AND b.check_out > '$check_in') as is_booked
        FROM rooms r";
$result = $conn->query($sql);
$available_rooms = [];
while ($row = $result->fetch_assoc()) {
    if ($row['is_booked'] == 0) $available_rooms[] = $row;
}

/**
 * Fungsi untuk mendapatkan gambar kamar
 * Prioritas:
 * 1. Kolom 'image' di database (jika terisi dan file ada)
 * 2. File dengan nama sesuai room_name di folder GUEST_HOUSE/
 * 3. Gambar default
 */
function getRoomImage($room) {
    // 1. Jika kolom image di database terisi dan file-nya ada
    if (!empty($room['image']) && file_exists($room['image'])) {
        return $room['image'];
    }
    
    // 2. Cari gambar berdasarkan nama kamar (ubah spasi jadi underscore, lower case)
    $basePath = 'GUEST_HOUSE/';
    $imageName = str_replace(' ', '_', strtolower($room['room_name'])) . '.jpg';
    $customPath = $basePath . $imageName;
    if (file_exists($customPath)) {
        return $customPath;
    }
    
    // 3. Default fallback
    return 'GUEST_HOUSE/TGH-13.jpg';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pilih Kamar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Pilih Kamar</h1>
        <p>Periode: <?= $check_in ?> s/d <?= $check_out ?> (<?= $nights ?> malam)</p>
        <?php if (empty($available_rooms)): ?>
        <p class="error">Maaf, tidak ada kamar tersedia.</p>
        <a href="reservasi.php" class="btn">Kembali</a>
        <?php else: ?>
        <form action="proses.php" method="POST">
            <input type="hidden" name="nights" value="<?= $nights ?>">
            <div class="room-list">
                <?php foreach ($available_rooms as $room): ?>
                <div class="room-card">
                    <img src="<?= getRoomImage($room) ?>" alt="<?= $room['room_name'] ?>">
                    <h3><?= $room['room_name'] ?></h3>
                    <p><?= $room['description'] ?></p>
                    <p>Kapasitas: <?= $room['capacity'] ?> orang</p>
                    <p class="price">Rp <?= number_format($room['price_per_night'],0,',','.') ?> / malam</p>
                    <p>Total: Rp <?= number_format($room['price_per_night'] * $nights,0,',','.') ?></p>
                    <label>
                        <input type="radio" name="room_id" value="<?= $room['id'] ?>" required>
                        Pilih kamar ini
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn">Selanjutnya</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>