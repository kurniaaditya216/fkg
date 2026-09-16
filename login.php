<?php
session_start();

include "config/database.php";

$error = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $query = mysqli_query($conn, "
        SELECT *
        FROM users
        WHERE email = '$email'
        LIMIT 1
    ");

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/index.php");
                exit;
            }

            if ($user['role'] == 'dosen') {
                header("Location: dosen/index.php");
                exit;
            }

            session_unset();
            session_destroy();
            $error = "Role pengguna tidak valid.";

        } else {
            $error = "Email atau password salah.";
        }

    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

<title>Login - FKG UNPAD</title>

<link rel="stylesheet" href="assets/css/login.css">

</head>

<body>


<div class="login-box">


<div class="logo">
    🦷
</div>


<h1>
    FKG UNPAD
</h1>


<div class="subtitle">

Sistem Manajemen Room

</div>


<?php if ($error != ""): ?>

<div class="error">

<?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>


<form method="POST">


<label>
    Email
</label>

<input
    type="email"
    name="email"
    autocomplete="off"
    placeholder="Masukkan email"
    required
>


<label>
    Password
</label>

<input
    type="password"
    name="password"
    autocomplete="new-password"
    placeholder="Masukkan password"
    required
>


<button
    type="submit"
    name="login">

    Login

</button>


</form>


</div>

</body>

</html>