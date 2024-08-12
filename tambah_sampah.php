<?php
include 'header.php';
require 'fungsi.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Edit Sampah</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/pm.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <span>Tambah Data</span>
                <h2>Tambah Sampah</h2>
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

                    <label for="">Jenis</label><br>
                    <input type="text" placeholder="Masukan Jenis Sampah" name=""
                        value=""><br><br>

                    <label for="">Harga Pengepul</label><br>
                    <input type="text" placeholder="Masukan Harga Pengepul" name=""
                        value=""><br><br>

                    <label for="">Harga Nasabah</label><br>
                    <input type="text" placeholder="Masukan Harga Nasabah" name=""
                        value=""><br><br>

                    <label for="kategori">Kategori</label><br>
                    <select class="form-control" id="kategori" name="kategori">
                        <option value="plastik">Plastik</option>
                        <option value="kertas">Kertas</option>
                        <option value="logam">Logam</option>
                        <option value="kaca">Kaca</option>
                        <option value="organik">Organik</option>
                    </select><br><br>

                    <hr>

                    <button type="submit" name="submit" class="inputbtn">Input</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Batas Akhir card-container -->
    </div>
    <!-- Batas Akhir Main-Content -->

    <!-- Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>