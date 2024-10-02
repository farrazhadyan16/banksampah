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

                        <!-- Persentase Keuntungan dengan Button di Samping -->
                        <label for="keuntungan_percent">Persentase Keuntungan (Default: 20%)</label><br>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="keuntungan_percent" name="keuntungan_percent"
                                value="20" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ubahPersentaseModal">
                                    Ubah Persentase Keuntungan
                                </button>
                            </div>
                        </div>
                        <br>

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

    <!-- Modal untuk mengubah persentase keuntungan -->
    <div class="modal fade" id="ubahPersentaseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Persentase Keuntungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="modal_keuntungan_percent">Masukkan Persentase Keuntungan</label>
                    <input type="number" class="form-control" id="modal_keuntungan_percent" placeholder="Persentase Keuntungan Baru">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveKeuntungan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS dan dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Mengatur persentase keuntungan default
        var persenKeuntungan = 20;

        // Fungsi untuk menghitung harga nasabah dan keuntungan
        function hitungKeuntungan() {
            var hargaPengepul = parseFloat(document.getElementById('harga_pengepul').value) || 0;

            // Hitung keuntungan: harga_pengepul * persenKeuntungan / 100
            var keuntungan = hargaPengepul * persenKeuntungan / 100;
            document.getElementById('keuntungan').value = keuntungan.toFixed(2);

            // Hitung harga nasabah: harga_pengepul - keuntungan
            var hargaNasabah = hargaPengepul - keuntungan;
            document.getElementById('harga_nasabah').value = hargaNasabah.toFixed(2);
        }

        // Event listener untuk input harga pengepul
        document.getElementById('harga_pengepul').addEventListener('input', hitungKeuntungan);

        // Event listener untuk tombol Simpan pada modal
        document.getElementById('saveKeuntungan').addEventListener('click', function() {
            var inputPersen = parseFloat(document.getElementById('modal_keuntungan_percent').value) || 0;

            // Validasi input: Persentase keuntungan harus lebih besar dari 0
            if (inputPersen > 0) {
                persenKeuntungan = inputPersen;
                document.getElementById('keuntungan_percent').value = persenKeuntungan;
                alert('Persentase keuntungan berhasil diubah menjadi ' + persenKeuntungan + '%');
                $('#ubahPersentaseModal').modal('hide');
            } else {
                alert('Persentase keuntungan harus lebih besar dari 0!');
            }
            hitungKeuntungan();
        });
    </script>
</body>

</html>