<?php

session_start();

include "../config/database.php";

/*if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}*/

$id = (int) $_GET['id'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM rooms WHERE id = $id"
);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Room tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Detail Room</title>

    <link rel="stylesheet" href="../css/admin/room_detail.css">

</head>

<body>

<div class="container">

    <h1>
        <?= htmlspecialchars($data['nama_room']); ?>
    </h1>

    <hr>

    <div class="data">
        <div class="label">Kode Room</div>
        <?= htmlspecialchars($data['kode_room']); ?>
    </div>

    <div class="data">
        <div class="label">Gedung</div>
        <?= htmlspecialchars($data['gedung']); ?>
    </div>

    <div class="data">
        <div class="label">Lantai</div>
        <?= htmlspecialchars($data['lantai']); ?>
    </div>

    <div class="data">
        <div class="label">Kapasitas</div>
        <?= $data['kapasitas']; ?> orang
    </div>

    <div class="data">
        <div class="label">Fasilitas</div>
        <?= nl2br(htmlspecialchars($data['fasilitas'])); ?>
    </div>

    <div class="data">
        <div class="label">Status</div>
        <?= htmlspecialchars($data['status']); ?>
    </div>

    <a href="room.php">
        ← Kembali
    </a>

</div>

</body>

</html>