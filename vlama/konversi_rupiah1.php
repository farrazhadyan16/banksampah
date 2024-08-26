<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Ambil harga emas saat ini dari API
$current_gold_price = getCurrentGoldPricesell();

// Jika tombol Submit ditekan untuk konversi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert'])) {
    $user_id = $_POST['user_id'];
    $jumlah_emas = $_POST['jumlah_emas'];

    // Validasi input
    if (empty($jumlah_emas) || !is_numeric($jumlah_emas)) {
        $message = "Jumlah uang harus diisi dan berupa angka.";
    } else {
        if (convertGoldToMoney($user_id, $jumlah_emas, $current_gold_price) === TRUE) {
            $message = "Konversi berhasil! Saldo emas Anda telah diperbarui.";
        } else {
            $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Transaksi</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("sidebar.php") ?>
        <!-- Akhir Sidebar -->

        <!-- Main Content -->
        <div class="main--content">
            <div class="main--content--monitoring">
                <div class="header--wrapper">
                    <div class="header--title">
                        <span>Halaman</span>
                        <h2>Konversi Emas</h2>
                    </div>
                    <div class="user--info">
                        <a href="setor_sampah.php"><button type="button" name="button" class="inputbtn">Setor
                                Sampah</button></a>
                        <a href="konversi_emas.php"><button type="button" name="button" class="inputbtn">Konversi
                                Emas</button></a>
                        <a href="konversi_rupiah.php"><button type="button" name="button" class="inputbtn">Konversi
                                Rupiah</button></a>
                        <a href="tarik_emas.php"><button type="button" name="button" class="inputbtn">Tarik
                                Emas</button></a>
                        <a href="tarik_rupiah.php"><button type="button" name="button" class="inputbtn">Tarik
                                Rupiah</button></a>
                        <a href="jual_sampah.php"><button type="button" name="button" class="inputbtn">Jual
                                Sampah</button></a>
                    </div>
                </div>

                <!-- Start of Form Section -->
                <div class="tabular--wrapper">
                    <!-- Search Section -->
                    <?php include("search_nik.php") ?>
                    <!-- Date and Time Section -->
                    <form method="POST" action="">
                        <!-- Gold Price Section -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input type="text" name="harga_emas" class="form-control" placeholder="Harga Emas"
                                        value="<?php echo $current_gold_price; ?>" readonly>
                                </div>
                                <small class="form-text text-muted">Harga Jual emas (saat ini) per gram</small>
                            </div>
                        </div>

                        <!-- Amount of Gold Section -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    </div>
                                    <input type="text" name="jumlah_emas" class="form-control"
                                        placeholder="Jumlah Emas (gram)">
                                </div>
                                <small class="form-text text-muted">Jumlah emas yang ingin dikonversi ke uang</small>
                            </div>
                        </div>

                        <!-- Include the hidden input for user_id -->
                        <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>

                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <button type="submit" name="convert" class="btn btn-primary w-100">Convert</button>
                            </div>
                        </div>

                        <!-- Success/Error Message -->
                        <?php if (!empty($message)) { ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="text-success"><?php echo $message; ?></p>
                            </div>
                        </div>
                        <?php } ?>
                    </form>
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Akhir Main Content -->
    </div>
</body>

</html>