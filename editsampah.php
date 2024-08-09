<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan

$id = $_GET['id'];

$sampah = query("SELECT * FROM sampah WHERE id=$id")[0];

if (isset($_POST["submit"])) {

    //cek apakah data berhasil ditambahkan
    if (updatedatasampah($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Ditambahkan');
					document.location.href ='sampah.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Ditambahkan');
					document.location.href ='sampah.php';
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
    <title>PMElectric | Edit Monitoring</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/pm.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <!-- Ini Sidebar -->
    <?php include("sidebar.php")?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Input Data</span>
                <h2>Edit Monitoring</h2>
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
                    <input type="text" placeholder="Masukkan ID Project" name="id" value="<?= $sampah["id"] ?>"><br><br>

                    <label for="">ID Kategori</label><br>
                    <input type="text" placeholder="Masukkan Nomor JO" name="id_kategori"
                        value="<?= $sampah["id_kategoti"] ?>"><br><br>

                    <label for="">Jenis</label><br>
                    <input type="text" placeholder="Masukan Tgl JO" name="jenis"
                        value="<?= $sampah["jenis"] ?>"><br><br>

                    <label for="">Harga Pengepul</label><br>
                    <input type="text" placeholder="Masukkan Nama Project" name="harga"
                        value="<?= $sampah["harga"] ?>"><br><br>

                    <label for="">Harga Nasabah</label><br>
                    <input type="text" placeholder="Masukkan Kode GBJ" name="harga_pusat"
                        value="<?= $sampah["harga_pusat"] ?>"><br><br>

                    <label for="">Harga</label><br>
                    <input type="text" placeholder="Masukkan Harga" name="jumlah"
                        value="<?= $sampah["jumlah"] ?>"><br><br>

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