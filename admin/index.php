<?php

session_start();
include "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}


// ==========================================
// JUMLAH ROOM
// ==========================================

$q_room = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM rooms
    WHERE status = 'aktif'
");

$total_room = mysqli_fetch_assoc($q_room)['total'];


// ==========================================
// JUMLAH USER
// ==========================================

$q_user = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM users
");

$total_user = mysqli_fetch_assoc($q_user)['total'];


// ==========================================
// TOTAL BOOKING
// ==========================================

$q_booking = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM bookings
");

$total_booking = mysqli_fetch_assoc($q_booking)['total'];


// ==========================================
// BOOKING MENUNGGU
// ==========================================

$q_wait = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE status = 'menunggu'
");

$total_menunggu = mysqli_fetch_assoc($q_wait)['total'];


// ==========================================
// BOOKING MENUNGGU
// ==========================================

$booking_menunggu = mysqli_query($conn, "

    SELECT
        bookings.*,
        users.nama AS nama_user,
        rooms.nama_room

    FROM bookings

    JOIN users
        ON bookings.user_id = users.id

    JOIN rooms
        ON bookings.room_id = rooms.id

    WHERE bookings.status = 'menunggu'

    ORDER BY
        bookings.tanggal ASC,
        bookings.jam_mulai ASC

    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard - FKG UNPAD</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial;
    background: #f4f6f9;
}

.navbar {
    background: #1e3a5f;
    color: white;

    padding: 18px 40px;

    display: flex;
    justify-content: space-between;
}

.logo {
    font-weight: bold;
    font-size: 20px;
}

.user {
    display: flex;
    gap: 15px;
    align-items: center;
}

.logout {
    color: white;
    text-decoration: none;
}

.container {
    width: 1100px;
    max-width: 94%;
    margin: 35px auto;
}

h1 {
    color: #1e3a5f;
}

.subtitle {
    color: #777;
}


/* ==========================
   STAT
========================== */

.stats {
    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;

    margin-top: 30px;
}

.stat {
    background: white;
    padding: 22px;

    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,.07);
}

.number {
    font-size: 30px;
    font-weight: bold;
    color: #1e3a5f;
}

.label {
    color: #777;
}


/* ==========================
   MENU
========================== */

.menu {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;

    margin-top: 25px;
}

.menu a {
    background: white;

    padding: 25px;

    border-radius: 10px;

    text-decoration: none;

    color: #333;

    box-shadow:
        0 3px 12px rgba(0,0,0,.07);
}

.menu-icon {
    font-size: 32px;
}

.menu h3 {
    color: #1e3a5f;
}


/* ==========================
   TABLE
========================== */

.section {
    background: white;

    margin-top: 30px;

    padding: 25px;

    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,.07);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 13px;

    border-bottom:
        1px solid #eee;

    text-align: left;
}

th {
    color: #1e3a5f;
}

.action {
    text-decoration: none;
    margin-right: 10px;
}

.approve {
    color: #1e8449;
}

.reject {
    color: #c0392b;
}


@media(max-width: 700px) {

    .stats,
    .menu {
        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>


<div class="navbar">

    <div class="logo">
        FKG UNPAD
    </div>

    <div class="user">

        👤 Admin

        <a
            href="../logout.php"
            class="logout">

            Logout

        </a>

    </div>

</div>


<div class="container">

<h1>
    Dashboard Admin
</h1>

<p class="subtitle">
    Kelola sistem peminjaman room FKG UNPAD.
</p>


<!-- STATISTIK -->

<div class="stats">


<div class="stat">

    <div class="number">
        <?= $total_room; ?>
    </div>

    <div class="label">
        Room Aktif
    </div>

</div>


<div class="stat">

    <div class="number">
        <?= $total_user; ?>
    </div>

    <div class="label">
        Total User
    </div>

</div>


<div class="stat">

    <div class="number">
        <?= $total_booking; ?>
    </div>

    <div class="label">
        Total Booking
    </div>

</div>


<div class="stat">

    <div class="number">
        <?= $total_menunggu; ?>
    </div>

    <div class="label">
        Menunggu Approval
    </div>

</div>


</div>


<!-- MENU -->

<div class="menu">


<a href="room.php">

    <div class="menu-icon">
        🏢
    </div>

    <h3>
        Kelola Room
    </h3>

    <p>
        Tambah, edit dan nonaktifkan room.
    </p>

</a>


<a href="user.php">

    <div class="menu-icon">
        👥
    </div>

    <h3>
        Kelola User
    </h3>

    <p>
        Kelola akun dosen dan admin.
    </p>

</a>


<a href="booking.php">

    <div class="menu-icon">
        📋
    </div>

    <h3>
        Kelola Booking
    </h3>

    <p>
        Setujui atau tolak pengajuan.
    </p>

</a>

</div>


<!-- BOOKING MENUNGGU -->

<div class="section">

<h2>
    ⏳ Menunggu Persetujuan
</h2>


<table>

<tr>

    <th>Pemesan</th>
    <th>Acara</th>
    <th>Room</th>
    <th>Tanggal</th>
    <th>Waktu</th>
    <th>Aksi</th>

</tr>


<?php if (
    mysqli_num_rows($booking_menunggu) == 0
): ?>

<tr>

    <td colspan="6">

        Tidak ada booking yang menunggu.

    </td>

</tr>

<?php endif; ?>


<?php while (
    $data = mysqli_fetch_assoc($booking_menunggu)
): ?>

<tr>

    <td>
        <?= htmlspecialchars(
            $data['nama_user']
        ); ?>
    </td>

    <td>
        <?= htmlspecialchars(
            $data['nama_acara']
        ); ?>
    </td>

    <td>
        <?= htmlspecialchars(
            $data['nama_room']
        ); ?>
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

        <a
            class="action approve"
            href="booking.php?aksi=setujui&id=<?= $data['id']; ?>"
            onclick="return confirm('Setujui booking ini?')">

            ✓ Setujui

        </a>


        <a
            class="action reject"
            href="booking.php?aksi=tolak&id=<?= $data['id']; ?>"
            onclick="return confirm('Tolak booking ini?')">

            ✕ Tolak

        </a>

    </td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>

</html>