<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan

$id = $_GET['id'];

$data_sampah = query("SELECT * FROM sampah WHERE id=$id")[0];

if (isset($_POST["submit"])) {

    //cek apakah data berhasil ditambahkan
    if (updatedatamonitoring($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Diubah');
					document.location.href ='monitoring.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Diubah');
					document.location.href ='monitoring.php';
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
    <?php include("sidebar.php") ?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Edit Data</span>
                <h2>Monitoring</h2>
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
                    <!-- <label for="">ID PROJECT</label><br> -->
                    <input type="hidden" placeholder="Masukkan ID Project" name="id"
                        value="<?= $data_sampah["id"] ?>"><br><br>

                    <label for="">Kategori</label><br>
                    <input type="text" placeholder="Masukkan Nomor JO" name="no_jo"
                        value="<?= $data_sampah["no_jo"] ?>"><br><br>

                    <label for="">Jenis</label><br>
                    <input type="date" placeholder="Masukan Tgl JO" name="tgl_jo"
                        value="<?= $data_sampah["tgl_jo"] ?>"><br><br>

                    <label for="">Harga Pengepul</label><br>
                    <input type="text" placeholder="Masukkan Nama Project" name="nama_project"
                        value="<?= $data_sampah["nama_project"] ?>"><br><br>

                    <label for="">Harga Nasabah</label><br>
                    <input type="text" placeholder="Masukkan Kode GBJ" name="kode_gbj"
                        value="<?= $data_sampah["kode_gbj"] ?>"><br><br>

                    <label for="">Jumlah (KG)</label><br>
                    <input type="text" placeholder="Masukkan Harga" name="nilai_harga"
                        value="<?= $data_sampah["nilai_harga"] ?>"><br><br>


                    <br><br>
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