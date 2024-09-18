<?php
include 'header.php';
require 'fungsi.php';

// Fetch all categories from the database
$categories = getCategories();

if (isset($_POST["submit"])) {
    handleAddWaste($_POST);
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
    <div id="wrapper">

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
                        <input type="text" id="harga_pengepul" placeholder="Masukkan Harga Pengepul"
                            name="harga_pengepul" required><br><br>

                        <label for="keuntungan_percent">Persentase Keuntungan</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="keuntungan_percent"
                                placeholder="Masukkan Persentase Keuntungan" name="keuntungan_percent" required
                                aria-describedby="persentaseHelp">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small id="persentaseHelp" class="form-text text-muted">Masukkan persentase keuntungan dari
                            harga pengepul.</small> <br>

                        <!-- Tambahkan label untuk menampilkan keuntungan -->
                        <label for="keuntungan">Keuntungan (Hasil)</label><br>
                        <input type="text" id="keuntungan" placeholder="Keuntungan" readonly><br><br>

                        <label for="harga_nasabah">Harga Nasabah (Hasil)</label><br>
                        <input type="text" id="harga_nasabah" placeholder="Harga Nasabah" name="harga_nasabah"
                            readonly><br><br>

                        <label for="kategori">Kategori</label><br>
                        <select class="form-control" id="kategori" name="kategori" required>
                            <option value=''>Pilih</option>
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
    <script>
    // Menghitung harga nasabah dan keuntungan dari harga pengepul dan persentase keuntungan
    document.getElementById('keuntungan_percent').addEventListener('input', function() {
        var hargaPengepul = parseFloat(document.getElementById('harga_pengepul').value) || 0;
        var persenKeuntungan = parseFloat(this.value) || 0;

        // Hitung keuntungan: harga_pengepul * persenKeuntungan / 100
        var keuntungan = hargaPengepul * persenKeuntungan / 100;
        document.getElementById('keuntungan').value = keuntungan.toFixed(2);

        // Hitung harga nasabah: harga_pengepul + keuntungan
        var hargaNasabah = hargaPengepul - keuntungan;
        document.getElementById('harga_nasabah').value = hargaNasabah.toFixed(2);
    });

    document.getElementById('harga_pengepul').addEventListener('input', function() {
        var persenKeuntungan = parseFloat(document.getElementById('keuntungan_percent').value) || 0;
        var hargaPengepul = parseFloat(this.value) || 0;

        // Hitung keuntungan: harga_pengepul * persenKeuntungan / 100
        var keuntungan = hargaPengepul * persenKeuntungan / 100;
        document.getElementById('keuntungan').value = keuntungan.toFixed(2);

        // Hitung harga nasabah setiap kali harga pengepul diubah
        var hargaNasabah = hargaPengepul + keuntungan;
        document.getElementById('harga_nasabah').value = hargaNasabah.toFixed(2);
    });
    </script>
</body>

</html>