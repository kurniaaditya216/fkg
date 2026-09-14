<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query(
    $conn,
    "SELECT * FROM rooms ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Kelola Room - FKG UNPAD</title>

    <style>

        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
        }

        .content {
            padding: 30px;
        }

        h1 {
            color: #1e3a5f;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #1e3a5f;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .btn:hover {
            background: #2d527d;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1e3a5f;
            color: white;
        }

        .aksi a {
            text-decoration: none;
            margin-right: 8px;
        }

        .edit {
            color: #2980b9;
        }

        .detail {
            color: #27ae60;
        }

        .hapus {
            color: #c0392b;
        }

        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 13px;
        }

        .aktif {
            background: #d5f5e3;
            color: #1e8449;
        }

        .nonaktif {
            background: #fadbd8;
            color: #922b21;
        }

    </style>

</head>

<body>

<div class="content">

    <h1>Kelola Room</h1>

    <p>Daftar ruangan FKG UNPAD</p>

    <br>

    <a href="index.php" class="btn">
        ← Dashboard
    </a>

    <a href="room_tambah.php" class="btn">
        + Tambah Room
    </a>


    <table>

        <tr>

            <th>No</th>
            <th>Nama Room</th>
            <th>Kode</th>
            <th>Gedung</th>
            <th>Lantai</th>
            <th>Kapasitas</th>
            <th>Status</th>
            <th>Aksi</th>

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
                <?= htmlspecialchars($data['nama_room']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['kode_room']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['gedung']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['lantai']); ?>
            </td>

            <td>
                <?= $data['kapasitas']; ?> orang
            </td>

            <td>

                <?php if ($data['status'] == 'aktif'): ?>

                    <span class="status aktif">
                        Aktif
                    </span>

                <?php else: ?>

                    <span class="status nonaktif">
                        Nonaktif
                    </span>

                <?php endif; ?>

            </td>

            <td class="aksi">

                <a
                    href="room_detail.php?id=<?= $data['id']; ?>"
                    class="detail">
                    Detail
                </a>

                <a
                    href="room_edit.php?id=<?= $data['id']; ?>"
                    class="edit">
                    Edit
                </a>

                <a
                    href="room_hapus.php?id=<?= $data['id']; ?>"
                    class="hapus"
                    onclick="return confirm('Yakin ingin menghapus room ini?')">
                    Hapus
                </a>

            </td>

        </tr>

        <?php endwhile; ?>


    </table>

</div>

</body>

</html>