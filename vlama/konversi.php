<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Ambil harga emas saat ini dari API
$current_gold_price_buy = getCurrentGoldPricebuy(); // For converting money to gold
$current_gold_price_sell = getCurrentGoldPricesell(); // For converting gold to money

// Retrieve the last inserted transaction ID
$query = "SELECT no FROM transaksi ORDER BY no DESC LIMIT 1";
$result = $conn->query($query);

// Jika tombol Submit ditekan untuk konversi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert'])) {
    $id_user = $_POST['id_user'];

    // Fetch the user's current balance
    $balance_query = "SELECT uang, emas FROM dompet WHERE id_user = '$id_user'";
    $balance_result = $conn->query($balance_query);

    if ($balance_result && $balance_result->num_rows > 0) {
        $balance_row = $balance_result->fetch_assoc();
        $current_money_balance = $balance_row['uang'];
        $current_gold_balance = $balance_row['emas'];
    } else {
        $message = "Gagal mendapatkan saldo pengguna.";
    }

    // Check if query execution was successful
    if ($result && $result->num_rows > 0) {
        $last_id = $result->fetch_assoc()['no'];
    } else {
        $last_id = 0; // No previous record found
    }

    $new_id = $last_id + 1;
    $id = 'TRANS' . date('Y') . str_pad($new_id, 6, '0', STR_PAD_LEFT); // Generate unique transaction ID
    
    // Set the default timezone to Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');
    // Insert data into the transaksi table
    $jenis_transaksi = 'pindah_saldo'; // Set jenis_transaksi
    $date = date('Y-m-d'); // Get the current date
    $time = date('H:i:s'); // Get the current time
    $transaksi_query = "INSERT INTO transaksi (no, id, id_user, jenis_transaksi, date, time) VALUES (NULL, '$id', '$id_user', '$jenis_transaksi', '$date', '$time')";

    // Initialize a flag to check if an error occurs
    $hasError = false;

    if ($conn->query($transaksi_query) === TRUE) {
        $id_transaksi = $id; // Set $id_transaksi after successful insert
    
        // Check if converting money to gold
        if (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'money_to_gold') {
            $jumlah_uang = $_POST['jumlah_uang'];
    
            // Validate input
            if (empty($jumlah_uang) || !is_numeric($jumlah_uang)) {
                $message = "Jumlah uang harus diisi dan berupa angka.";
                $hasError = true;
            } elseif ($jumlah_uang > $current_money_balance) {
                // Check if user has enough money
                $message = "Saldo uang Anda tidak mencukupi untuk melakukan konversi.";
                $hasError = true;
            } else {
                $hasil_konversi = $jumlah_uang / $current_gold_price_buy; // Calculate amount of gold
    
                // Perform money to gold conversion
                if (convertMoneyToGold($id_user, $jumlah_uang, $current_gold_price_buy) === TRUE) {
                    // Insert data into pindah_saldo
                    
                    $jenis_konversi = 'konversi_uang';
                    $insert_query = "INSERT INTO pindah_saldo (no, id_transaksi, jenis_konversi, jumlah, harga_beli_emas, hasil_konversi) VALUES (NULL, '$id_transaksi','$jenis_konversi', '$jumlah_uang', '$current_gold_price_buy', '$hasil_konversi')";
                    if ($conn->query($insert_query) === TRUE) {
                        $message = "Konversi uang ke emas berhasil! Saldo emas Anda telah diperbarui.";
                    } else {
                        $message = "Terjadi kesalahan saat melakukan insert ke pindah_saldo: " . $conn->error;
                        $hasError = true;
                    }
                    
                } else {
                    $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
                    $hasError = true;
                }
            }
        } elseif (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'gold_to_money') {
            $jumlah_emas = $_POST['jumlah_emas'];
    
            // Validate input
            if (empty($jumlah_emas) || !is_numeric($jumlah_emas)) {
                $message = "Jumlah emas harus diisi dan berupa angka.";
                $hasError = true;
            } elseif ($jumlah_emas > $current_gold_balance) {
                // Check if user has enough gold
                $message = "Saldo emas Anda tidak mencukupi untuk melakukan konversi.";
                $hasError = true;
            } else {
                $hasil_konversi = $jumlah_emas * $current_gold_price_sell; // Calculate money from gold
    
                // Perform gold to money conversion
                if (convertGoldToMoney($id_user, $jumlah_emas, $current_gold_price_sell) === TRUE) {
                    // Insert data into pindah_saldo
                    
                    $jenis_konversi = 'konversi_emas';
                    $insert_query = "INSERT INTO pindah_saldo (no, id_transaksi, jenis_konversi, jumlah, harga_jual_emas, hasil_konversi) VALUES (NULL, '$id_transaksi','$jenis_konversi', '$jumlah_emas', '$current_gold_price_sell', '$hasil_konversi')";
                    if ($conn->query($insert_query) === TRUE) {
                        $message = "Konversi emas ke uang berhasil! Saldo uang Anda telah diperbarui.";
                    } else {
                        $message = "Terjadi kesalahan saat melakukan insert ke pindah_saldo: " . $conn->error;
                        $hasError = true;
                    }
                } else {
                    $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
                    $hasError = true;
                }
            }
        }
        
        // Redirect only if there are no errors
        if (!$hasError) {
            header("Location: nota.php?id_transaksi=$id_transaksi");
            exit; // Ensure no further code is executed
        }
    } else {
        $message = "Gagal memasukkan data ke tabel transaksi: " . $conn->error;
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
                                    <input type="text" name="harga_emas_beli" class="form-control"
                                        placeholder="Harga Emas Beli" value="<?php echo $current_gold_price_buy; ?>"
                                        readonly>
                                </div>
                                <small class="form-text text-muted">Harga beli emas (saat ini) per gram</small>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input type="text" name="harga_emas_jual" class="form-control"
                                        placeholder="Harga Emas Jual" value="<?php echo $current_gold_price_sell; ?>"
                                        readonly>
                                </div>
                                <small class="form-text text-muted">Harga jual emas (saat ini) per gram</small>
                            </div>
                        </div>

                        <div id="money_to_gold_input" class="conversion-input" style="display: none;">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="text" name="jumlah_uang" class="form-control"
                                            placeholder="Jumlah Uang" oninput="updateResult()">
                                    </div>
                                    <small class="form-text text-muted">Jumlah uang yang ingin dikonversi</small>
                                </div>
                            </div>
                        </div>

                        <div id="gold_to_money_input" class="conversion-input" style="display: none;">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-gem"></i></span>
                                        </div>
                                        <input type="text" name="jumlah_emas" class="form-control"
                                            placeholder="Jumlah Emas" oninput="updateResult()">
                                    </div>
                                    <small class="form-text text-muted">Jumlah emas yang ingin dikonversi</small>
                                </div>
                            </div>
                        </div>

                        <!-- Result Display Area -->
                        <div id="conversion_result" class="alert alert-info" style="display: none;"></div>

                        <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="id_user" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <button type="submit" name="convert" class="btn btn-primary">Konversi</button>
                            </div>
                        </div>
                    </form>

                    <?php if ($message != ""): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $message; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <script>
    // JavaScript to show/hide conversion input fields based on selected conversion type
    document.querySelectorAll('input[name="conversion_type"]').forEach((elem) => {
        elem.addEventListener("change", function(event) {
            const value = event.target.value;
            document.getElementById('money_to_gold_input').style.display = value === 'money_to_gold' ?
                'block' : 'none';
            document.getElementById('gold_to_money_input').style.display = value === 'gold_to_money' ?
                'block' : 'none';
            document.getElementById('conversion_result').style.display =
                'none'; // Hide result when switching conversion type
        });
    });

    function updateResult() {
        const conversionType = document.querySelector('input[name="conversion_type"]:checked');
        let result = '';
        let inputAmount;

        if (conversionType) {
            if (conversionType.value === 'money_to_gold') {
                inputAmount = parseFloat(document.querySelector('input[name="jumlah_uang"]').value);
                if (!isNaN(inputAmount)) {
                    const goldPrice = <?php echo $current_gold_price_buy; ?>;
                    const goldAmount = inputAmount / goldPrice;
                    result = `Hasil konversi: ${goldAmount.toFixed(7)} gram emas`;
                }
            } else if (conversionType.value === 'gold_to_money') {
                inputAmount = parseFloat(document.querySelector('input[name="jumlah_emas"]').value);
                if (!isNaN(inputAmount)) {
                    const goldPrice = <?php echo $current_gold_price_sell; ?>;
                    const moneyAmount = inputAmount * goldPrice;
                    result = `Hasil konversi: Rp ${moneyAmount.toFixed(2)}`;
                }
            }
        }

        const resultDiv = document.getElementById('conversion_result');
        resultDiv.textContent = result;
        resultDiv.style.display = result ? 'block' : 'none';
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>