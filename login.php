<?php
session_start();
if (isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}
require_once 'koneksi.php';
$username = "";
$password = "";
$err = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username == '' || $password == '') {
        $err .= "<p>Silahkan Masukkan username dan password</p>";
    }

    if (empty($err)) {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($password, $user['password'])) {
                    //pass correct
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $user['role'];
                    header("location:index.php");
                    exit();
                } else {
                    //pass incor
                    $err .= "<p>Password Salah</p>";
                }
            } else {
                $err .= "<p>Akun Tidak Ditemukan</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            $err .= "<p>Error : " . mysqli_error($koneksi) . "</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>s
<style>
    .btn-custom {
        background-color: rgba(0, 50, 153, 1);
        color: white;
    }

    .btn-custom:hover {
        background-color: rgba(0, 50, 153, 0.8);
        color: white;

    }
</style>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($err)): ?>
                            <div class="alert alert-danger">
                                <?= $err ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-custom btn-block">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Belum punya akun? <a href="register.php">Register di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>