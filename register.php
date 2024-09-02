<?php
session_start();
require_once 'koneksi.php';

// Ambil data role dari session jika pengguna login
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Tentukan pilihan role yang bisa diakses
$roles = [];
if ($loggedInRole === 'superadmin') {
    $roles = ['superadmin', 'admin', 'nasabah'];
} elseif ($loggedInRole === 'admin') {
    $roles = ['admin', 'nasabah'];
} else {
    $roles = ['nasabah'];
    $autoRole = 'nasabah';
}

// Inisialisasi variabel error
$err = '';
$username = $nama = $email = $notelp = $nik = $alamat = $tgl_lahir = $kelamin = '';

// Proses form jika method POST terdeteksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $notelp = $_POST['notelp'];
    $nik = $_POST['nik'];
    $alamat = $_POST['alamat'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $kelamin = $_POST['kelamin'];

    // Jika pengguna tidak login, role otomatis adalah Nasabah
    if (!isset($loggedInRole)) {
        $role = 'nasabah';
    } else {
        $role = $_POST['role'];
    }

    // Validasi input
    if (empty($username) || empty($password) || empty($nama) || empty($role) || empty($email) || empty($notelp) || empty($nik) || empty($alamat) || empty($tgl_lahir) || empty($kelamin)) {
        $err = "Semua bidang harus diisi!";
    } elseif (isset($loggedInRole) && !in_array($role, $roles)) {
        $err = "Role tidak valid!";
    } else {
        $check_query = "SELECT username FROM user WHERE username = ?";
        $check_stmt = mysqli_prepare($koneksi, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $err = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO user (username, password, nama, role, email, notelp, nik, alamat, tgl_lahir, kelamin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($koneksi, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "ssssssssss", $username, $hashed_password, $nama, $role, $email, $notelp, $nik, $alamat, $tgl_lahir, $kelamin);

            if (mysqli_stmt_execute($insert_stmt)) {
                header("location: login.php");
                exit();
            } else {
                $err = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }

            mysqli_stmt_close($insert_stmt);
        }

        mysqli_stmt_close($check_stmt);
    }
}
?>

<script>
    function validateForm() {
        var password = document.getElementById("password").value;
        if (password.length < 8) {
            alert("Password harus terdiri dari minimal 8 karakter.");
            return false;
        }
        return true;
    }

    function togglePassword() {
        var passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .scrollable-container {
            max-height: 80vh;
            overflow-y: auto;
        }

        .btn-custom {
            background-color: rgba(0, 50, 153, 1);
            color: white;
        }

        .btn-custom:hover {
            background-color: rgba(0, 50, 153, 0.8);
            color: white;

        }
    </style>
</head>

<body>
    <div class="container mt-5 scrollable-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Register</h4>
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
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($nama); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="notelp">No. Telp</label>
                                <input type="text" name="notelp" id="notelp" class="form-control" value="<?= htmlspecialchars($notelp); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text" name="nik" id="nik" class="form-control" maxlength="16" pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control" required><?= htmlspecialchars($alamat); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" value="<?= htmlspecialchars($tgl_lahir); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kelamin">Jenis Kelamin</label>
                                <select name="kelamin" id="kelamin" class="form-control" required>
                                    <option value="Pria" <?= $kelamin == 'Pria' ? 'selected' : '' ?>>Pria</option>
                                    <option value="Wanita" <?= $kelamin == 'Wanita' ? 'selected' : '' ?>>Wanita</option>
                                </select>
                            </div>

                            <?php if (isset($loggedInRole)): ?>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control">
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role ?>"><?= ucfirst($role) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-custom btn-block">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>