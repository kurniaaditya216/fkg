<?php

session_start();

include "../config/database.php";

/*if (!isset($_SESSION['login']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../login.php");
    exit;
}*/

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT
        bookings.*,
        rooms.nama_room,
        rooms.gedung
    FROM bookings
    JOIN rooms
        ON bookings.room_id = rooms.id
    WHERE bookings.user_id = $user_id
    ORDER BY bookings.tanggal DESC, bookings.jam_mulai DESC
");

?>

<?php
$page_title = 'Booking Saya - FKG UNPAD';
include "../layout/header.php";
?>

<div class="container">

    <h1>Booking Saya</h1>

    <p>
        Daftar pemesanan room yang pernah kamu lakukan.
    </p>

    <a href="index.php" class="btn">
        ← Kembali
    </a>
    <?php if (isset($_GET['success'])): ?>

    <div class="success">

        ✅ Booking berhasil diajukan.

        <br>

        Silakan tunggu persetujuan admin.

    </div>

<?php endif; ?>

    <br>

    <table>

        <tr>

            <th>No</th>
            <th>Acara</th>
            <th>Room</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Status</th>

        </tr>


        <?php

        $no = 1;

        while ($data = mysqli_fetch_assoc($query)):

        ?>

        <tr>

            <td>
                <?= $no++; ?>
            </td>

            <td>
                <?= htmlspecialchars($data['nama_acara']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['nama_room']); ?>
            </td>

            <td>
                <?= date('d-m-Y', strtotime($data['tanggal'])); ?>
            </td>

            <td>
                <?= substr($data['jam_mulai'], 0, 5); ?>
                -
                <?= substr($data['jam_selesai'], 0, 5); ?>
            </td>

            <td>

                <span class="status <?= $data['status']; ?>">

                    <?= ucfirst($data['status']); ?>

                </span>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php include "../layout/footer.php"; ?>