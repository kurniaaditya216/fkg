<?php

session_start();
include "../config/database.php";

/*if (!isset($_SESSION['login']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../login.php");
    exit;
}*/

$user_id = $_SESSION['user_id'];

$pesan = "";
$tipe_pesan = "";


// ==========================================
// DATA DARI KALENDER
// ==========================================

$room_selected = isset($_GET['room_id'])
    ? (int) $_GET['room_id']
    : 0;

$tanggal_selected = isset($_GET['tanggal'])
    ? $_GET['tanggal']
    : date('Y-m-d');


// ==========================================
// PROSES BOOKING
// ==========================================

if (isset($_POST['booking'])) {

    $room_id = (int) $_POST['room_id'];
    $nama_acara = mysqli_real_escape_string(
        $conn,
        $_POST['nama_acara']
    );

    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $jumlah_peserta = (int) $_POST['jumlah_peserta'];

    $keterangan = mysqli_real_escape_string(
        $conn,
        $_POST['keterangan']
    );


    // ==========================================
    // CEK JAM
    // ==========================================

    if ($jam_mulai >= $jam_selesai) {

        $pesan = "Jam selesai harus lebih besar dari jam mulai.";
        $tipe_pesan = "error";

    } else {


        // ==========================================
        // CEK ROOM
        // ==========================================

        $room_query = mysqli_query($conn, "
            SELECT *
            FROM rooms
            WHERE id = $room_id
            AND status = 'aktif'
        ");

        $room = mysqli_fetch_assoc($room_query);


        if (!$room) {

            $pesan = "Room tidak ditemukan atau sedang tidak aktif.";
            $tipe_pesan = "error";

        } elseif ($jumlah_peserta > $room['kapasitas']) {

            $pesan = "Jumlah peserta melebihi kapasitas room.";
            $tipe_pesan = "error";

        } else {


            // ==========================================
            // CEK BENTROK
            // ==========================================

            $cek = mysqli_query($conn, "
                SELECT *
                FROM bookings

                WHERE room_id = $room_id

                AND tanggal = '$tanggal'

                AND status IN
                    ('menunggu', 'disetujui')

                AND jam_mulai < '$jam_selesai'

                AND jam_selesai > '$jam_mulai'
            ");


            if (mysqli_num_rows($cek) > 0) {

                $pesan = "❌ Jadwal tersebut sudah digunakan.";
                $tipe_pesan = "error";

            } else {


                // ==========================================
                // SIMPAN BOOKING
                // ==========================================

                $query = mysqli_query($conn, "
                    INSERT INTO bookings
                    (
                        user_id,
                        room_id,
                        nama_acara,
                        tanggal,
                        jam_mulai,
                        jam_selesai,
                        jumlah_peserta,
                        keterangan,
                        status
                    )

                    VALUES
                    (
                        $user_id,
                        $room_id,
                        '$nama_acara',
                        '$tanggal',
                        '$jam_mulai',
                        '$jam_selesai',
                        $jumlah_peserta,
                        '$keterangan',
                        'menunggu'
                    )
                ");


                if ($query) {

                    header(
                        "Location: booking_saya.php?success=1"
                    );

                    exit;

                } else {

                    $pesan = "Booking gagal disimpan.";
                    $tipe_pesan = "error";

                }

            }

        }

    }

}


// ==========================================
// AMBIL ROOM
// ==========================================

$rooms = mysqli_query($conn, "
    SELECT *
    FROM rooms
    WHERE status = 'aktif'
    ORDER BY nama_room
");

?>

<?php
$page_title = 'Booking Room - FKG UNPAD';
include "../layout/header.php";
?>


<div class="container">



<div class="card">

<h1>📅 Booking Room</h1>

<p class="subtitle">

Ajukan pemakaian ruangan untuk kegiatan FKG UNPAD.

</p>


<?php if ($pesan != ""): ?>

<div class="pesan <?= $tipe_pesan; ?>">

<?= htmlspecialchars($pesan); ?>

</div>

<?php endif; ?>


<div class="info">

Booking akan berstatus
<strong>Menunggu</strong>
sampai disetujui oleh admin.

</div>


<form method="POST">


<label>
Nama Acara
</label>

<input
    type="text"
    name="nama_acara"
    placeholder="Contoh: Seminar Nasional FKG"
    required
>


<label>
Tanggal
</label>

<input
    type="date"
    name="tanggal"
    value="<?= htmlspecialchars($tanggal_selected); ?>"
    min="<?= date('Y-m-d'); ?>"
    required
>


<label>
Room
</label>

<select name="room_id" required>

<option value="">
-- Pilih Room --
</option>


<?php while ($room = mysqli_fetch_assoc($rooms)): ?>

<option
    value="<?= $room['id']; ?>"
    <?= $room['id'] == $room_selected
        ? 'selected'
        : ''; ?>
>

<?= htmlspecialchars(
    $room['nama_room']
); ?>

-

<?= htmlspecialchars(
    $room['gedung']
); ?>

-

Kapasitas
<?= $room['kapasitas']; ?>

</option>

<?php endwhile; ?>

</select>


<div class="row">


<div>

<label>
Jam Mulai
</label>

<input
    type="time"
    name="jam_mulai"
    required
>

</div>


<div>

<label>
Jam Selesai
</label>

<input
    type="time"
    name="jam_selesai"
    required
>

</div>


</div>


<label>
Jumlah Peserta
</label>

<input
    type="number"
    name="jumlah_peserta"
    min="1"
    placeholder="Contoh: 50"
    required
>


<label>
Keterangan
</label>

<textarea
    name="keterangan"
    placeholder="Contoh: Membutuhkan projector dan microphone"
></textarea>


<button
    type="submit"
    name="booking">

📅 Ajukan Booking

</button>

    <a href="index.php" class="btn">
        kembali
    </a>

</form>

</div>

</div>

<?php include "../layout/footer.php"; ?>