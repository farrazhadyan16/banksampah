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
    $last_id = ($result->num_rows > 0) ? $result->fetch_assoc()['no'] : 0;
    $new_id = $last_id + 1;

    $id_transaksi = 'TRANS' . date('Y') . str_pad($new_id, 6, '0', STR_PAD_LEFT); // Generate unique transaction ID

    // Check if converting money to gold
    if (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'money_to_gold') {
        $jumlah_uang = $_POST['jumlah_uang'];

        // Validasi input
        if (empty($jumlah_uang) || !is_numeric($jumlah_uang)) {
            $message = "Jumlah uang harus diisi dan berupa angka.";
        } else {
            $hasil_konversi = $jumlah_uang / $current_gold_price_buy; // Calculate the amount of gold
            
            // Convert money to gold
            if (convertMoneyToGold($user_id, $jumlah_uang, $current_gold_price_buy) === TRUE) {
                // Insert into pindah_saldo table
                $insert_query = "INSERT INTO pindah_saldo (no, id_transaksi, jumlah, harga_emas, hasil_konversi) VALUES (NULL,'$id_transaksi','$jumlah', '$harga_emas','$hasil_konversi')";
                // var_dump($insert_query);
                // die;
                if ($stmt = $conn->prepare($insert_query)) {
                    $stmt->bind_param("sdss", $id_transaksi, $jumlah_uang, $current_gold_price_buy, $hasil_konversi);
                    $stmt->execute();
                    $message = "Konversi uang ke emas berhasil! Saldo emas Anda telah diperbarui.";
                } else {
                    $message = "Terjadi kesalahan saat melakukan insert: " . $conn->error;
                }
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
            $hasil_konversi = $jumlah_emas * $current_gold_price_sell; // Calculate the money value of gold
            
            // Convert gold to money
            if (convertGoldToMoney($user_id, $jumlah_emas, $current_gold_price_sell) === TRUE) {
                // Define the missing variables
                $jumlah = $jumlah_emas; // Set jumlah to the amount of gold
                $harga_emas = $current_gold_price_sell; // Set harga_emas to current gold price for selling

                // Insert into pindah_saldo table
                $insert_query = "INSERT INTO pindah_saldo (no, id_transaksi, jumlah, harga_emas, hasil_konversi) VALUES (NULL,'$id_transaksi','$jumlah', '$harga_emas','$hasil_konversi')";
                var_dump($insert_query);
                die;
                if ($stmt = $conn->prepare($insert_query)) {
                    // Correctly bind parameters
                    $stmt->bind_param("sdss", $id_transaksi, $jumlah, $harga_emas, $hasil_konversi);
                    $stmt->execute();
                    $message = "Konversi emas ke uang berhasil! Saldo uang Anda telah diperbarui.";
                } else {
                    $message = "Terjadi kesalahan saat melakukan insert: " . $conn->error;
                }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <?php include("sidebar.php") ?>
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

                <div class="tabular--wrapper">
                    <?php include("search_nik.php") ?>
                    <form method="POST" action="">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label><input type="radio" name="conversion_type" value="money_to_gold" required>
                                    Konversi Uang ke Emas</label><br>
                                <label><input type="radio" name="conversion_type" value="gold_to_money"> Konversi Emas
                                    ke Uang</label>
                            </div>
                        </div>

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

                        <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <button type="submit" name="convert" class="btn btn-primary w-100">Convert</button>
                            </div>
                        </div>

                        <?php if ($message) { ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $message; ?>
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
    // JavaScript to toggle input fields based on selected conversion type
    $('input[name="conversion_type"]').on('change', function() {
        if (this.value === 'money_to_gold') {
            $('#money_to_gold_input').show();
            $('#gold_to_money_input').hide();
        } else {
            $('#money_to_gold_input').hide();
            $('#gold_to_money_input').show();
        }
    });
    </script>
</body>

</html>