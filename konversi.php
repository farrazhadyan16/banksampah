<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Ambil harga emas saat ini dari API
$current_gold_price_buy = getCurrentGoldPricebuy(); // For converting money to gold
$current_gold_price_sell = getCurrentGoldPricesell(); // For converting gold to money

// Jika tombol Submit ditekan untuk konversi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert'])) {
    $user_id = $_POST['user_id'];
    
    // Check if converting money to gold
    if (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'money_to_gold') {
        $jumlah_uang = $_POST['jumlah_uang'];

        // Validasi input
        if (empty($jumlah_uang) || !is_numeric($jumlah_uang)) {
            $message = "Jumlah uang harus diisi dan berupa angka.";
        } else {
            if (convertMoneyToGold($user_id, $jumlah_uang, $current_gold_price_buy) === TRUE) {
                $message = "Konversi uang ke emas berhasil! Saldo emas Anda telah diperbarui.";
            } else {
                $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
            }
        }
    } 
    
    // Check if converting gold to money
    else if (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'gold_to_money') {
        $jumlah_emas = $_POST['jumlah_emas'];

        // Validasi input
        if (empty($jumlah_emas) || !is_numeric($jumlah_emas)) {
            $message = "Jumlah emas harus diisi dan berupa angka.";
        } else {
            if (convertGoldToMoney($user_id, $jumlah_emas, $current_gold_price_sell) === TRUE) {
                $message = "Konversi emas ke uang berhasil! Saldo uang Anda telah diperbarui.";
            } else {
                $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Konversi</title>
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
                        <a href="konversi.php"><button type="button" name="button" class="inputbtn">Konversi
                                Saldo</button></a>
                        <a href="tarik.php"><button type="button" name="button" class="inputbtn">Tarik
                                Saldo</button></a>
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
                        <!-- Conversion Type Selection -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label><input type="radio" name="conversion_type" value="money_to_gold" required>
                                    Konversi Uang ke Emas</label><br>
                                <label><input type="radio" name="conversion_type" value="gold_to_money"> Konversi Emas
                                    ke Uang</label>
                            </div>
                        </div>

                        <!-- Gold Price Section -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input type="text" name="harga_emas" class="form-control" placeholder="Harga Emas"
                                        value="<?php echo $current_gold_price_buy; ?>" readonly>
                                </div>
                                <small class="form-text text-muted">Harga beli emas (saat ini) per gram</small>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input type="text" name="harga_emas" class="form-control" placeholder="Harga Emas"
                                        value="<?php echo $current_gold_price_sell; ?>" readonly>
                                </div>
                                <small class="form-text text-muted">Harga jual emas (saat ini) per gram</small>
                            </div>
                        </div>

                        <!-- Amount Section (Dynamic based on selection) -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div id="money_to_gold_input" style="display: none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="text" name="jumlah_uang" class="form-control"
                                            placeholder="Jumlah Uang">
                                    </div>
                                    <small class="form-text text-muted">Jumlah uang yang ingin dikonversi ke
                                        emas</small>
                                </div>

                                <div id="gold_to_money_input" style="display: none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                        </div>
                                        <input type="text" name="jumlah_emas" class="form-control"
                                            placeholder="Jumlah Emas (gram)">
                                    </div>
                                    <small class="form-text text-muted">Jumlah emas yang ingin dikonversi ke
                                        uang</small>
                                </div>
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

    <script>
    // Show/hide input fields based on conversion type
    const conversionTypeRadios = document.querySelectorAll('input[name="conversion_type"]');
    const moneyToGoldInput = document.getElementById('money_to_gold_input');
    const goldToMoneyInput = document.getElementById('gold_to_money_input');

    conversionTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'money_to_gold') {
                moneyToGoldInput.style.display = 'block';
                goldToMoneyInput.style.display = 'none';
            } else {
                moneyToGoldInput.style.display = 'none';
                goldToMoneyInput.style.display = 'block';
            }
        });
    });
    </script>
</body>

</html>