<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan
if (isset($_POST["submit"])) {

    //cek apakah data berhasil ditambahkan
    if (inputdata($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Ditambahkan');
					document.location.href ='monitoring.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Ditambahkan');
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
    <title>PMElectric | Import Project</title>
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
                <span>Import Data</span>
                <h2>Project</h2>
            </div>
            <div class="user--info">
                <img src="./img/logoPM.png" alt="logo">
            </div>
        </div>

        <!-- Ini card-container -->
        <!-- <div class="card--container">
            <h3 class="main--title">Import Project</h3>
            <form method="POST" action="impor_aksi.php" enctype="multipart/form-data">
                <div class="container">
                    <hr>

                    <label for="">Masukkan Dokumen</label><br>
                    <span style="color:red">*</span>
                    <input type="file" name="excel_data" required>

                    <button type="submit" name="submit" class="inputbtn">Input</button>
                </div>
            </form>
        </div> -->
        <div class="card--container">
            <!-- <h3 class="main--title">Import Project</h3> -->
            <form method="POST" action="impor_aksi.php" enctype="multipart/form-data">
                <div class="container">
                    <hr>

                    <label for="excel" class="drop-container" id="dropcontainer">
                        <span class="drop-title">Drop files here</span>
                        or
                        <input type="file" id= "excel" name="excel_data" accept=".xlsx" required>
                    </label><br>
                    <button type="submit" name="submit" class="inputbtn">Input</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Batas Akhir card-container -->
    </div>
    <!-- Batas Akhir Main-Content -->
</body>
<script src="/khalilul/magang/web_pm/js/script.js"></script>

</html>