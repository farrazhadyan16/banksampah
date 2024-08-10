<?php
session_start();
require_once 'koneksi.php';

// Cek apakah pengguna sudah login
$logged_in_role = isset($_SESSION['logged_in_role']) ? $_SESSION['logged_in_role'] : null;

$query = "SELECT DISTINCT role FROM user"; // Ubah query sesuai struktur tabel Anda
$result = mysqli_query($koneksi, $query);

// Inisialisasi variabel error
$err = '';

// Proses form jika method POST terdeteksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];

    // Validasi input
    if (empty($username) || empty($password) || empty($nama) || empty($role)) {
        $err = "Semua bidang harus diisi!";
    } else {
        // Check if username already exists
        $check_query = "SELECT username FROM user WHERE username = ?";
        $check_stmt = mysqli_prepare($koneksi, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $err = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data ke dalam tabel user
            $insert_query = "INSERT INTO user (username, password, nama, role) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($koneksi, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $hashed_password, $nama, $role);

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./img/">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div id="app">
        <div class="container">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Daftar Pengguna</p> <br />
            <form method="POST" action="">
                <div class="input-group">
                    <label>Username</label>
                    <br>
                    <input type="text" class="input" placeholder="Masukkan Username" name="username" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <br>
                    <input type="password" id="password" class="form-control" placeholder="Masukkan Password"
                        name="password" required>
                </div>
                <div class="input-group">
                    <label>Nama</label>
                    <br>
                    <input type="text" class="form-control" placeholder="Masukkan Nama" name="nama" required>
                </div>

                <div class="input-group">
                    <label>Role</label>
                    <br>
                    <select name="role" id="role" class="input-group" required>
                        <option value=''>Pilih</option>
                        <?php
                        if ($logged_in_role === 'superadmin') {
                            echo "<option value='nasabah'>Nasabah</option>";
                            echo "<option value='admin'>Admin</option>";
                            echo "<option value='superadmin'>Super Admin</option>";
                        } elseif ($logged_in_role === 'admin') {
                            echo "<option value='nasabah'>Nasabah</option>";
                            echo "<option value='admin'>Admin</option>";
                        } else {
                            // Jika pengguna belum login, hanya tampilkan pilihan Nasabah
                            echo "<option value='nasabah'>Nasabah</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <button type="submit" name="submit" class="btn">Daftar</button>
                </div>
            </form>

            <?php if ($err) { echo "<h style='color: red; text-align: center;'>$err</h>"; } ?>
        </div>
    </div>

</body>

</html>