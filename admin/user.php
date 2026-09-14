<?php

session_start();
include "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT * FROM users
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User - FKG UNPAD</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
        }

        .container {
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
            margin: 15px 5px 20px 0;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1e3a5f;
            color: white;
        }

        .role {
            padding: 5px 10px;
            border-radius: 15px;
        }

        .admin {
            background: #d6eaf8;
            color: #21618c;
        }

        .dosen {
            background: #d5f5e3;
            color: #1e8449;
        }

        .hapus {
            color: #c0392b;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Kelola User</h1>

    <p>Kelola akun Admin dan Dosen FKG UNPAD.</p>

    <a href="index.php" class="btn">
        ← Dashboard
    </a>

    <a href="user_tambah.php" class="btn">
        + Tambah User
    </a>

    <table>

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)):
        ?>

        <tr>

            <td><?= $no++; ?></td>

            <td>
                <?= htmlspecialchars($data['nama']); ?>
            </td>

            <td>
                <?= htmlspecialchars($data['email']); ?>
            </td>

            <td>

                <span class="role <?= $data['role']; ?>">
                    <?= ucfirst($data['role']); ?>
                </span>

            </td>

            <td>

                <?php if ($data['id'] != $_SESSION['user_id']): ?>

                    <a
                        class="hapus"
                        href="user_hapus.php?id=<?= $data['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus user ini?')">
                        Hapus
                    </a>

                <?php else: ?>

                    Akun Anda

                <?php endif; ?>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

</body>
</html>