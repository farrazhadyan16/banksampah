<?php
require_once 'header.php';
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
</head>

<body onload=getData()>

    <!-- Ini Sidebar -->
    <?php include("sidebar.php")?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Halaman</span>
                <h2>Dashboard</h2>
            </div>
            <div class="user--info">
                <!-- <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search" />
                </div> -->
                <img src="./img/logoPM.png" alt="logo">
            </div>
        </div>

        <!-- Ini card-container -->
        <?php include("card-container.php")?>
        <!-- Batas Akhir card-container -->

    </div>
    <!-- Batas Akhir Main-Content -->
    <!-- script -->
    <script src="./js/script.js"></script>
</body>

</html>