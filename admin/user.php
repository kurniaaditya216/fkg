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

    <link rel="stylesheet" href="../css/admin/user.css">
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