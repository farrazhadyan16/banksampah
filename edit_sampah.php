<?php
include 'header.php';
require 'fungsi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query data sampah berdasarkan ID
$sampah = query("SELECT * FROM sampah WHERE id='$id'")[0];

if (isset($_POST["submit"])) {
    // Cek apakah data berhasil diubah
    if (updatedatasampah($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Diubah');
					document.location.href ='sampah.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Diubah');
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
    <title>Bank Sampah | Edit Sampah</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/pm.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome Cdn link -->
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
                    <span>Edit Data</span>
                    <h2>Edit Sampah</h2>
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

                        <input type="hidden" name="id" value="<?= $sampah["id"] ?>">

                        <label for="id_kategori">ID Kategori</label><br>
                        <input type="text" placeholder="Masukkan ID Kategori" name="id_kategori"
                            value="<?= $sampah["id_kategori"] ?>" required><br><br>

                        <label for="jenis">Jenis</label><br>
                        <input type="text" placeholder="Masukkan Jenis Sampah" name="jenis"
                            value="<?= $sampah["jenis"] ?>" required><br><br>

                        <label for="harga_pengepul">Harga Pengepul</label><br>
                        <input type="text" id="harga_pengepul" placeholder="Masukkan Harga Pengepul" name="harga_pusat"
                            value="<?= $sampah["harga_pusat"] ?>" required><br><br>

                        <label for="keuntungan_percent">Persentase Keuntungan</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="keuntungan_percent" placeholder="Masukkan Persentase Keuntungan" name="keuntungan_percent" required aria-describedby="persentaseHelp">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small id="persentaseHelp" class="form-text text-muted">Masukkan persentase keuntungan dari harga pengepul.</small> <br>

                        <label for="keuntungan">Keuntungan (Hasil)</label><br>
                        <input type="text" id="keuntungan" placeholder="Keuntungan" readonly><br><br>

                        <label for="harga_nasabah">Harga Nasabah (Hasil)</label><br>
                        <input type="text" id="harga_nasabah" placeholder="Harga Nasabah" name="harga" value="<?= $sampah["harga"] ?>" readonly><br><br>

                        <label for="jumlah">Jumlah</label><br>
                        <input type="text" placeholder="Masukkan Jumlah" name="jumlah"
                            value="<?= $sampah["jumlah"] ?>" required><br><br>

                        <hr>

                        <button type="submit" name="submit" class="inputbtn">Update</button>
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
            var hargaNasabah = hargaPengepul + keuntungan;
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