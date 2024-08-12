<?php
include 'header.php';
require 'fungsi.php';

// Fetch all categories from the database
$categories = query("SELECT * FROM kategori_sampah ORDER BY name ASC");

if (isset($_POST["submit"])) {
    $jenis = $_POST['jenis'];
    $harga_pengepul = $_POST['harga_pengepul'];
    $harga_nasabah = $_POST['harga_nasabah'];
    $kategori = $_POST['kategori'];

    // Insert the data into the database
    $query = "INSERT INTO sampah (jenis, harga, harga_pusat, id_kategori) 
              VALUES ('$jenis', '$harga_pengepul', '$harga_nasabah', '$kategori')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data berhasil ditambahkan');
                document.location.href = 'sampah.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Tambah Sampah</title>
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

                    <label for="jenis">Jenis</label><br>
                    <input type="text" placeholder="Masukkan Jenis Sampah" name="jenis" required><br><br>

                    <label for="harga_pengepul">Harga Pengepul</label><br>
                    <input type="text" placeholder="Masukkan Harga Pengepul" name="harga_pengepul" required><br><br>

                    <label for="harga_nasabah">Harga Nasabah</label><br>
                    <input type="text" placeholder="Masukkan Harga Nasabah" name="harga_nasabah" required><br><br>

                    <label for="kategori">Kategori</label><br>
                    <select class="form-control" id="kategori" name="kategori" required>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                        <?php endforeach; ?>
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