<?php
// session_start();
// if (isset($_SESSION['admin_username'])) {
//     header("location:main_admin.php");
// }
require_once 'koneksi.php';

// Inisialisasi variabel error
$err = '';

// Proses form jika method POST terdeteksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];

    // Validasi input
    if (empty($username) || empty($password) || empty($nama)) {
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
            $role = 'Nasabah'; // Role ditetapkan sebagai Nasabah
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
    <title>Bank Sampah | Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./img/">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div id="app">

        <div class="container">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;"><img src="" alt="logo" width="225"
                    height="46.5"></p> <br />

            <form method="POST" action="">
                <div class="input-group">
                    <label class="control-label col-sm-4">Username</label>
                    <br>
                    <input type="text" class="input" placeholder="Masukkan Username" name="username" required>
                </div>
                <div class="input-group">
                    <label class="control-label col-sm-4">Password</label>
                    <br>
                    <input type="password" class="form-control" placeholder="Masukkan Password" name="password"
                        required>
                </div>
                <div class="input-group">
                    <label class="control-label col-sm-4">Nama</label>
                    <br>
                    <input type="text" class="form-control" placeholder="Masukkan Nama" name="nama" required>
                </div>

                <div class="input-group">
                    <button type="submit" name="submit" class="btn">Daftar</button>
                </div>
            </form>


            <?php
             if ($err) {
            echo "<h style='color: red; text-align: center;'>$err</h>";
               }
            ?>

        </div>
    </div>

</body>

</html>