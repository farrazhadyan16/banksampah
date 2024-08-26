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
    $date = date('Y-m-d'); // Get the current date and time
    $time = date('H:i:s'); // Get the current date and time
    $transaksi_query = "INSERT INTO transaksi (no, id, id_user, jenis_transaksi, date, time) VALUES (NULL, '$id', '$id_user', '$jenis_transaksi', '$date', '$time')";

    if ($conn->query($transaksi_query) === TRUE) {
        $id_transaksi = $id; // Tetapkan $id_transaksi setelah insert berhasil
    
        // Periksa apakah konversi uang ke emas
        if (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'money_to_gold') {
            $jumlah_uang = $_POST['jumlah_uang'];
    
            // Validasi input
            if (empty($jumlah_uang) || !is_numeric($jumlah_uang)) {
                $message = "Jumlah uang harus diisi dan berupa angka.";
            } else {
                $hasil_konversi = $jumlah_uang / $current_gold_price_buy; // Hitung jumlah emas yang diperoleh
    
                // Lakukan konversi uang ke emas
                if (convertMoneyToGold($id_user, $jumlah_uang, $current_gold_price_buy) === TRUE) {
                    // Masukkan data ke tabel pindah_saldo
                    
                    $jenis_konversi = 'konversi_uang';
                    $insert_query = "INSERT INTO pindah_saldo (no, id_transaksi, jenis_konversi, jumlah, harga_beli_emas, hasil_konversi) VALUES (NULL, '$id_transaksi','$jenis_konversi', '$jumlah_uang', '$current_gold_price_buy', '$hasil_konversi')";
                    if ($conn->query($insert_query) === TRUE) {
                        $message = "Konversi uang ke emas berhasil! Saldo emas Anda telah diperbarui.";
                    } else {
                        $message = "Terjadi kesalahan saat melakukan insert ke pindah_saldo: " . $conn->error;
                    }
                    
                } else {
                    $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
                }
            }
        } elseif (isset($_POST['conversion_type']) && $_POST['conversion_type'] === 'gold_to_money') {
            $jumlah_emas = $_POST['jumlah_emas'];
    
            // Validasi input
            if (empty($jumlah_emas) || !is_numeric($jumlah_emas)) {
                $message = "Jumlah emas harus diisi dan berupa angka.";
            } else {
                $hasil_konversi = $jumlah_emas * $current_gold_price_sell; // Hitung nilai uang dari emas
    
                // Lakukan konversi emas ke uang
                if (convertGoldToMoney($id_user, $jumlah_emas, $current_gold_price_sell) === TRUE) {
                    // Masukkan data ke tabel pindah_saldo
                    
                    $jenis_konversi = 'konversi_emas';
                    $insert_query = "INSERT INTO pindah_saldo (no, id_transaksi, jenis_konversi, jumlah, harga_jual_emas, hasil_konversi) VALUES (NULL, '$id_transaksi','$jenis_konversi', '$jumlah_emas', '$current_gold_price_sell', '$hasil_konversi')";
                    if ($conn->query($insert_query) === TRUE) {
                        $message = "Konversi emas ke uang berhasil! Saldo uang Anda telah diperbarui.";
                    } else {
                        $message = "Terjadi kesalahan saat melakukan insert ke pindah_saldo: " . $conn->error;
                    }
                    // var_dump($insert_query);
                    // die;
                } else {
                    $message = "Terjadi kesalahan saat melakukan konversi: " . $conn->error;
                }
            }
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
                                            placeholder="Jumlah Emas">
                                    </div>
                                    <small class="form-text text-muted">Jumlah emas yang ingin dikonversi ke
                                        uang</small>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="id_user" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>


                        <div class="row mb-4">
                            <div class="col-md-8">
                                <button type="submit" name="convert" class="btn btn-primary">Konversi</button>
                            </div>
                        </div>
                    </form>
                    <?php if ($message != "") { ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('input[name="conversion_type"]').change(function() {
            if ($(this).val() === 'money_to_gold') {
                $('#money_to_gold_input').show();
                $('#gold_to_money_input').hide();
            } else if ($(this).val() === 'gold_to_money') {
                $('#money_to_gold_input').hide();
                $('#gold_to_money_input').show();
            }
        });
    });
    </script>
</body>

</html>