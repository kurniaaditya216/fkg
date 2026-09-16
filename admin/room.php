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

    <link rel="stylesheet" href="../css/admin/room.css">

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