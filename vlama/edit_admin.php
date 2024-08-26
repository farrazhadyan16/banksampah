<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan

$id = $_GET['id'];

$user = query("SELECT * FROM user WHERE id=$id")[0];

if (isset($_POST["submit"])) {

    //cek apakah data berhasil ditambahkan
    if (updatedatanasabah($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Ditambahkan');
					document.location.href ='nasabah.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Ditambahkan');
					document.location.href ='nasabah.php';
				</script>
				";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Edit Admin</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/pm.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div id="wrapper">

        <!-- Ini Sidebar -->
        <?php include("sidebar.php")?>
        <!-- Batas Akhir Sidebar -->

        <!-- Ini Main-Content -->
        <div class="main--content">
            <div class="header--wrapper">
                <div class="header--title">
                    <span>Edit Data</span>
                    <h2>Edit Admin</h2>
                </div>
                <div class="user--info">
                    <img src="./img/logoPM.png" alt="logo">
                </div>
            </div>

            <!-- Ini card-container -->
            <div class="card--container">
                <h3 class="main--title">Isi Form Berikut</h3>
                <form method="POST" action="">
                    <div class="container">
                        <hr>

                        <input type="hidden" name="page" value="design">

                        <!-- Tambahkan input hidden untuk menandai halaman -->
                        <label for="">#</label><br>
                        <input type="text" placeholder="Masukkan ID Project" name="id"
                            value="<?= $user["id"] ?>"><br><br>

                        <label for="">Username</label><br>
                        <input type="text" placeholder="Masukkan Nomor JO" name="username"
                            value="<?= $user["username"] ?>"><br><br>

                        <label for="">Nama</label><br>
                        <input type="text" placeholder="Masukan Tgl JO" name="nama"
                            value="<?= $user["nama"] ?>"><br><br>

                        <label for="">Email</label><br>
                        <input type="text" placeholder="Masukkan Nama Project" name="email"
                            value="<?= $user["email"] ?>"><br><br>

                        <label for="">NIK</label><br>
                        <input type="text" placeholder="Masukkan Kode GBJ" name="nik"
                            value="<?= $user["nik"] ?>"><br><br>

                        <label for="">Alamat</label><br>
                        <input type="text" placeholder="Masukkan Harga" name="alamat"
                            value="<?= $user["alamat"] ?>"><br><br>

                        <label for="">Tanggal Lahir</label><br>
                        <input type="date" placeholder="Masukkan Nama Panel" name="tgl_lahir"
                            value="<?= $user["tgl_lahir"] ?>"><br><br>

                        <label for="">Jenis Kelamin</label><br>
                        <input type="text" placeholder="Masukkan Tipe Jenis" name="kelamin"
                            value="<?= $user["kelamin"] ?>"><br><br>

                        <hr>

                        <button type="submit" name="submit" class="inputbtn">Input</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Batas Akhir card-container -->
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>