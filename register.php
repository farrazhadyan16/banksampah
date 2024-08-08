<?php
session_start();
if (isset($_SESSION['admin_username'])) {
    header("location:main_admin.php");
}
include 'database.php';
$username = "";
$password = "";
$err = "";
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username == '' or $password == ''){
        $err .= "<p>Silahkan Masukkan username dan password</p>";
    }
    if (empty($err)) {
        $sql1 = "select * from admin where username = '$username'";
        $q1 = mysqli_query($koneksi, $sql1);
    
        if ($q1) {
            // Check if any rows were returned
            if (mysqli_num_rows($q1) > 0) {
                $r1 = mysqli_fetch_array($q1);
                
                if ($r1['password'] != md5($password)) {
                    $err .= "<p>Password Salah</p>";
                }
            } else {
                $err .= "<p>Akun tidak ditemukan</p>";
            }
        } else {
            // Handle query error, e.g., display an error message or log it
            $err .= "<p>Error executing the query: " . mysqli_error($koneksi) . "</p>";
        }
    }
    if (empty($err)) {
        $login_id = $r1['login_id'];
        $sql1 = "select * from admin_akses where login_id = '$login_id'";
        $q1 = mysqli_query($koneksi, $sql1);
        while ($r1 = mysqli_fetch_array($q1)) {
            $akses[] = $r1['akses_id']; 
        }
        if (empty($akses)) {
            $err .= "<li>Kamu tidak punya akses ke halaman admin</li>";
        }
    }
    if (empty($err)) {
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_akses'] = $akses;
        header("location:main_admin.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Login</title>
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

            <form action="" method="post">
                <div class="input-group">
                    <input type="text" value="<?php echo $username ?>" name="username" class="input"
                        placeholder="Username" /><br />
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password" /><br />
                </div>
                <div class="input-group">
                    <input type="submit" class="btn" name="login" value="Login" />
                </div>
                <div class="input-group">
                    <a href="register.php"><button type="button" class="btn" name="button">Register</button> </a>
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