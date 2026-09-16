<?php

session_start();
include "../config/database.php";

/*if (!isset($_SESSION['login']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../login.php");
    exit;
}*/

$user_id = $_SESSION['user_id'];


// ==========================================
// JUMLAH BOOKING
// ==========================================

$q_total = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE user_id = $user_id
");

$total_booking = mysqli_fetch_assoc($q_total)['total'];


// ==========================================
// BOOKING MENUNGGU
// ==========================================

$q_menunggu = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE user_id = $user_id
    AND status = 'menunggu'
");

$menunggu = mysqli_fetch_assoc($q_menunggu)['total'];


// ==========================================
// BOOKING DISETUJUI
// ==========================================

$q_disetujui = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE user_id = $user_id
    AND status = 'disetujui'
");

$disetujui = mysqli_fetch_assoc($q_disetujui)['total'];


// ==========================================
// BOOKING TERBARU
// ==========================================

$booking_terbaru = mysqli_query($conn, "
    SELECT
        bookings.*,
        rooms.nama_room,
        rooms.gedung

    FROM bookings

    JOIN rooms
        ON bookings.room_id = rooms.id

    WHERE bookings.user_id = $user_id

    ORDER BY bookings.tanggal DESC,
             bookings.jam_mulai DESC

    LIMIT 5
");

?>

<?php
$page_title = 'Dashboard Dosen - FKG UNPAD';
include "../layout/header.php";
?>


<div class="container">


<!-- WELCOME -->

<div class="welcome">

    <h1>
        Dashboard Dosen
    </h1>

    <p>
        Selamat datang di Sistem Manajemen Room FKG UNPAD.
    </p>

</div>


<!-- MENU -->

<div class="menu">


<a
    href="kalender.php"
    class="menu-card">

    <div class="menu-icon">
        📅
    </div>

    <h3>
        Kalender Room
    </h3>

    <p>
        Lihat jadwal dan ketersediaan ruangan.
    </p>

</a>


<a
    href="booking.php"
    class="menu-card">

    <div class="menu-icon">
        ➕
    </div>

    <h3>
        Booking Room
    </h3>

    <p>
        Ajukan pemakaian ruangan.
    </p>

</a>


<a
    href="booking_saya.php"
    class="menu-card">

    <div class="menu-icon">
        📋
    </div>

    <h3>
        Booking Saya
    </h3>

    <p>
        Lihat status pemesanan room.
    </p>

</a>

</div>


<!-- STATISTIK -->

<div class="stats">


<div class="stat">

    <div class="stat-number">
        <?= $total_booking; ?>
    </div>

    <div class="stat-label">
        Total Booking
    </div>

</div>


<div class="stat">

    <div class="stat-number">
        <?= $menunggu; ?>
    </div>

    <div class="stat-label">
        Menunggu Persetujuan
    </div>

</div>


<div class="stat">

    <div class="stat-number">
        <?= $disetujui; ?>
    </div>

    <div class="stat-label">
        Booking Disetujui
    </div>

</div>


</div>


<!-- BOOKING TERBARU -->

<div class="section">

<h2>
    📋 Booking Terbaru
</h2>


<table>

<tr>

    <th>Acara</th>
    <th>Room</th>
    <th>Tanggal</th>
    <th>Waktu</th>
    <th>Status</th>

</tr>


<?php if (mysqli_num_rows($booking_terbaru) == 0): ?>

<tr>

    <td colspan="5">
        Belum ada booking.
    </td>

</tr>

<?php endif; ?>


<?php while ($data = mysqli_fetch_assoc($booking_terbaru)): ?>

<tr>

    <td>
        <?= htmlspecialchars($data['nama_acara']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['nama_room']); ?>
    </td>

    <td>
        <?= date(
            'd-m-Y',
            strtotime($data['tanggal'])
        ); ?>
    </td>

    <td>

        <?= substr(
            $data['jam_mulai'],
            0,
            5
        ); ?>

        -

        <?= substr(
            $data['jam_selesai'],
            0,
            5
        ); ?>

    </td>

    <td>

        <span
            class="status <?= $data['status']; ?>">

            <?= ucfirst($data['status']); ?>

        </span>

    </td>

</tr>

<?php endwhile; ?>

</table>

</div>


</div>

<?php include "../layout/footer.php"; ?>