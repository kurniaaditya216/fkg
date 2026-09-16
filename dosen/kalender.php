<?php

session_start();
include "../config/database.php";

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}


// ==========================================
// BULAN & TAHUN
// ==========================================

$bulan = isset($_GET['bulan'])
    ? (int) $_GET['bulan']
    : date('n');

$tahun = isset($_GET['tahun'])
    ? (int) $_GET['tahun']
    : date('Y');


// Validasi bulan
if ($bulan < 1) {
    $bulan = 12;
    $tahun--;
}

if ($bulan > 12) {
    $bulan = 1;
    $tahun++;
}


// ==========================================
// JUMLAH HARI
// ==========================================

$jumlah_hari = cal_days_in_month(
    CAL_GREGORIAN,
    $bulan,
    $tahun
);


// Hari pertama
$hari_pertama = date(
    'N',
    strtotime("$tahun-$bulan-01")
);


// ==========================================
// BOOKING BULAN INI
// ==========================================

$awal_bulan = sprintf(
    '%04d-%02d-01',
    $tahun,
    $bulan
);

$akhir_bulan = sprintf(
    '%04d-%02d-%02d',
    $tahun,
    $bulan,
    $jumlah_hari
);


$query = mysqli_query($conn, "

    SELECT
        bookings.*,
        rooms.nama_room

    FROM bookings

    JOIN rooms
        ON bookings.room_id = rooms.id

    WHERE bookings.tanggal
    BETWEEN '$awal_bulan'
    AND '$akhir_bulan'

    AND bookings.status IN
        ('menunggu', 'disetujui')

    ORDER BY bookings.jam_mulai

");


// Masukkan berdasarkan tanggal
$booking = [];

while ($data = mysqli_fetch_assoc($query)) {

    $tanggal = (int) date(
        'j',
        strtotime($data['tanggal'])
    );

    $booking[$tanggal][] = $data;
}


$nama_bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];


// ==========================================
// NAVIGASI
// ==========================================

$prev_bulan = $bulan - 1;
$prev_tahun = $tahun;

if ($prev_bulan < 1) {
    $prev_bulan = 12;
    $prev_tahun--;
}


$next_bulan = $bulan + 1;
$next_tahun = $tahun;

if ($next_bulan > 12) {
    $next_bulan = 1;
    $next_tahun++;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

<title>Kalender Room - FKG UNPAD</title>

<link rel="stylesheet" href="../css/dosen/kalender.css">

</head>

<body>


<div class="container">


<div class="header">

<div>

<h1>
    📅 Kalender Room
</h1>

<p>
    Jadwal penggunaan ruangan FKG UNPAD
</p>

</div>


<a
    href="index.php"
    class="back">

    ← Dashboard

</a>

</div>


<div class="calendar">


<!-- HEADER -->

<div class="calendar-header">

<a
    class="nav-btn"
    href="?bulan=<?= $prev_bulan; ?>&tahun=<?= $prev_tahun; ?>">

    ←

</a>


<h2>

<?= $nama_bulan[$bulan]; ?>

<?= $tahun; ?>

</h2>


<a
    class="nav-btn"
    href="?bulan=<?= $next_bulan; ?>&tahun=<?= $next_tahun; ?>">

    →

</a>

</div>


<!-- HARI -->

<div class="week">

<div>Sen</div>
<div>Sel</div>
<div>Rab</div>
<div>Kam</div>
<div>Jum</div>
<div>Sab</div>
<div>Min</div>

</div>


<!-- TANGGAL -->

<div class="days">


<?php for (
    $i = 1;
    $i < $hari_pertama;
    $i++
): ?>

<div class="day empty"></div>

<?php endfor; ?>


<?php for (
    $hari = 1;
    $hari <= $jumlah_hari;
    $hari++
): ?>


<?php

$tanggal_full = sprintf(
    '%04d-%02d-%02d',
    $tahun,
    $bulan,
    $hari
);

$is_today =
    $tanggal_full == date('Y-m-d');

?>


<div
    class="day <?= $is_today ? 'today' : ''; ?>">


<div class="number">

<?= $hari; ?>

</div>


<?php if (
    isset($booking[$hari])
): ?>


<?php foreach (
    $booking[$hari]
    as $event
): ?>


<a
    href="booking.php?room_id=<?= $event['room_id']; ?>&tanggal=<?= $tanggal_full; ?>"
    class="event <?= $event['status'] == 'menunggu' ? 'pending' : ''; ?>">


<strong>

<?= htmlspecialchars(
    $event['nama_room']
); ?>

</strong>


<br>


<?= htmlspecialchars(
    $event['nama_acara']
); ?>


<br>


<?= substr(
    $event['jam_mulai'],
    0,
    5
); ?>

-

<?= substr(
    $event['jam_selesai'],
    0,
    5
); ?>


</a>


<?php endforeach; ?>


<?php else: ?>


<?php if (
    $_SESSION['role'] == 'dosen'
): ?>

<a
    href="booking.php?tanggal=<?= $tanggal_full; ?>"
    class="available">

    🟢 Tersedia

</a>

<?php endif; ?>


<?php endif; ?>


</div>


<?php endfor; ?>


</div>


<div class="legend">

<span>
    <i class="dot red"></i>
    Disetujui
</span>

<span>
    <i class="dot yellow"></i>
    Menunggu
</span>

<span>
    <i class="dot green"></i>
    Tersedia
</span>

</div>


</div>

</div>

</body>

</html>