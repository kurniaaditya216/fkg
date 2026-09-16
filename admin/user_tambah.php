<?php

session_start();
include "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$pesan = "";

if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $cek = mysqli_query($conn, "
        SELECT id FROM users
        WHERE email = '$email'
    ");

    if (mysqli_num_rows($cek) > 0) {

        $pesan = "Email sudah digunakan.";

    } else {

        $query = mysqli_query($conn, "
            INSERT INTO users
            (nama, email, password, role)
            VALUES
            ('$nama', '$email', '$password', '$role')
        ");

        if ($query) {
            header("Location: user.php");
            exit;
        }

        $pesan = "Gagal menambahkan user.";
    }
}

?>

<?php
$page_title = 'Tambah User - FKG UNPAD';
include "../layout/header.php";
?>

<div class="container">

    <h1>Tambah User</h1>

    <?php if ($pesan): ?>
        <div class="error">
            <?= $pesan; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Nama</label>

        <input
            type="text"
            name="nama"
            placeholder="Nama dosen"
            required
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="email@fkg.unpad.ac.id"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <label>Role</label>

        <select name="role">

            <option value="dosen">
                Dosen
            </option>

            <option value="admin">
                Admin
            </option>

        </select>

        <button
            type="submit"
            name="simpan">
            Simpan User
        </button>

        <a href="user.php">
            Batal
        </a>

    </form>

</div>

<?php include "../layout/footer.php"; ?>