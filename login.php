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

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username == '' || $password == ''){
        $err .= "<p>Silahkan Masukkan username dan password</p>";
    }

    if (empty($err)) {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt,"s",$username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0){
                $user = mysqli_fetch_assoc($result);

                if(password_verify($password, $user['password'])){
                    //pass correct
                    $_SESSION['username']=$username;
                    $_SESSION['role'] = $user['role'];
                    header("location:index.php");
                    exit();
                }
                else{
                    //pass incor
                    $err .="<p>Password Salah</p>";
                }
            }
                else{
                $err .= "<p>Akun Tidak Ditemukan</p>";
                }
        
            mysqli_stmt_close($stmt);
            }
                else{
                $err .= "<p>Error : ".mysqli_error($koneksi) . "</p>";
            }
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
                    <input type="text" value="<?php echo htmlspecialchars($username); ?>" name="username" class="input"
                        placeholder="Username"><br>
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password"><br>
                </div>
                <div class="input-group">
                    <input type="submit" class="btn" name="login" value="Login"><br>
                </div>

                <div class="input-group">
                    <a href="register.php"><button type="button" class="btn" name="button">Register</button>
                    </a>
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