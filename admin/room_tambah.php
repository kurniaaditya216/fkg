<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama_room = mysqli_real_escape_string(
        $conn,
        $_POST['nama_room']
    );

    $kode_room = mysqli_real_escape_string(
        $conn,
        $_POST['kode_room']
    );

    $gedung = mysqli_real_escape_string(
        $conn,
        $_POST['gedung']
    );

    $lantai = mysqli_real_escape_string(
        $conn,
        $_POST['lantai']
    );

    $kapasitas = (int) $_POST['kapasitas'];

    $fasilitas = mysqli_real_escape_string(
        $conn,
        $_POST['fasilitas']
    );

    $status = $_POST['status'];


    $query = "INSERT INTO rooms
        (nama_room, kode_room, gedung, lantai, kapasitas, fasilitas, status)
        VALUES
        ('$nama_room',
         '$kode_room',
         '$gedung',
         '$lantai',
         '$kapasitas',
         '$fasilitas',
         '$status')";


    if (mysqli_query($conn, $query)) {

        header("Location: room.php");

        exit;

    } else {

        echo "Gagal menyimpan data: "
             . mysqli_error($conn);

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Room</title>

    <style>

        body {
            font-family: Arial;
            background: #f4f6f9;
        }

        .container {
            width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            color: #1e3a5f;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #1e3a5f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        a {
            margin-left: 10px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Tambah Room</h1>

    <form method="POST">

        <label>Nama Room</label>

        <input
            type="text"
            name="nama_room"
            placeholder="Contoh: Ruang Seminar"
            required
        >


        <label>Kode Room</label>

        <input
            type="text"
            name="kode_room"
            placeholder="Contoh: RSM-01"
            required
        >


        <label>Gedung</label>

        <input
            type="text"
            name="gedung"
            placeholder="Contoh: Gedung A"
            required
        >


        <label>Lantai</label>

        <input
            type="text"
            name="lantai"
            placeholder="Contoh: Lantai 2"
            required
        >


        <label>Kapasitas</label>

        <input
            type="number"
            name="kapasitas"
            min="1"
            placeholder="Contoh: 100"
            required
        >


        <label>Fasilitas</label>

        <textarea
            name="fasilitas"
            placeholder="Contoh: AC, Proyektor, Sound System"
        ></textarea>


        <label>Status</label>

        <select name="status">

            <option value="aktif">
                Aktif
            </option>

            <option value="nonaktif">
                Nonaktif
            </option>

        </select>


        <button
            type="submit"
            name="simpan">
            Simpan Room
        </button>

        <a href="room.php">
            Batal
        </a>

    </form>

</div>

</body>

</html>