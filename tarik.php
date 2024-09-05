<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Retrieve the last inserted transaction ID
$query = "SELECT no FROM transaksi ORDER BY no DESC LIMIT 1";
$result = $conn->query($query);

$current_gold_price_buy = getCurrentGoldPricebuy(); // For converting money to gold
$current_gold_price_sell = getCurrentGoldPricesell(); // For converting gold to money


// If NIK has been searched and the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdraw'])) {
    $id_user = $_POST['id_user'];

    if ($result && $result->num_rows > 0) {
        $last_id = $result->fetch_assoc()['no'];
    } else {
        $last_id = 0; // No previous record found
    }

    $new_id = $last_id + 1;
    $id = 'TRANS' . date('Y') . str_pad($new_id, 6, '0', STR_PAD_LEFT); // Generate unique transaction ID

    // Set the default timezone to Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');

    $jenis_transaksi = 'tarik_saldo'; // Set jenis_transaksi
    $date = date('Y-m-d'); // Get the current date
    $time = date('H:i:s'); // Get the current time

    // Fetch user's balance
    $balance_query = "SELECT uang, emas FROM dompet WHERE id_user = ?";
    $stmt_balance = $conn->prepare($balance_query);
    $stmt_balance->bind_param("i", $id_user);
    $stmt_balance->execute();
    $balance_result = $stmt_balance->get_result();
    $user_balance = $balance_result->fetch_assoc();

    if (isset($_POST['withdraw_type']) && ($_POST['withdraw_type'] === 'money' || $_POST['withdraw_type'] === 'gold')) {
        $withdraw_type = $_POST['withdraw_type'];

        // Determine withdrawal amount
        $jumlah_tarik = ($withdraw_type === 'money') ? $_POST['jumlah_uang'] : $_POST['jumlah_emas'];

        // Validate withdrawal amount
        if (empty($jumlah_tarik) || !is_numeric($jumlah_tarik)) {
            $message = "Jumlah yang ditarik harus diisi dan berupa angka.";
        } elseif (($withdraw_type === 'money' && $jumlah_tarik > $user_balance['uang']) ||
                  ($withdraw_type === 'gold' && $jumlah_tarik > $user_balance['emas'])) {
            // Show alert if withdrawal amount exceeds balance
            $message = "Jumlah yang ditarik tidak boleh melebihi saldo " . ($withdraw_type === 'money' ? "uang" : "emas") . " Anda.";
        } elseif ($withdraw_type === 'money' && ($user_balance['uang'] - $jumlah_tarik) < 1000) {
            // Show alert if withdrawal leaves less than 1000 units in money balance
            $message = "Saldo uang tidak boleh kurang dari 1000 setelah penarikan.";
        } else {
            try {
                // Proceed with the transaction if the amount is valid
                $conn->begin_transaction();

                // Insert into transaksi table
                $transaksi_query = "INSERT INTO transaksi (no, id, id_user, jenis_transaksi, date, time) VALUES (NULL, ?, ?, ?, ?, ?)";
                $stmt_transaksi = $conn->prepare($transaksi_query);
                $stmt_transaksi->bind_param("sssss", $id, $id_user, $jenis_transaksi, $date, $time);
                $stmt_transaksi->execute();

                // Insert into tarik_saldo table
                $jenis_saldo = ($withdraw_type === 'money') ? 'tarik_uang' : 'tarik_emas';
                $stmt = $conn->prepare("INSERT INTO tarik_saldo (no, id_transaksi, jenis_saldo, jumlah_tarik) VALUES (NULL, ?, ?, ?)");
                $stmt->bind_param("ssi", $id, $jenis_saldo, $jumlah_tarik);
                $stmt->execute();

                // Update user's balance
                if ($withdraw_type === 'money') {
                    $update_saldo_query = "UPDATE dompet SET uang = uang - ? WHERE id_user = ?";
                } else {
                    $update_saldo_query = "UPDATE dompet SET emas = emas - ? WHERE id_user = ?";
                }

                $stmt_update = $conn->prepare($update_saldo_query);
                $stmt_update->bind_param("di", $jumlah_tarik, $id_user);
                $stmt_update->execute();

                // Commit transaction
                $conn->commit();

                // Display success message
                $message = "Penarikan " . ($withdraw_type === 'money' ? "uang" : "emas") . " berhasil! Saldo Anda telah diperbarui.";

                // Redirect to nota.php
                header("Location: nota.php?id_transaksi=$id");
                exit();
            } catch (Exception $e) {
                // Rollback transaction in case of an error
                $conn->rollback();
                $message = "Terjadi kesalahan saat melakukan penarikan: " . $e->getMessage();
            }
        }
    }
}

// Fetch user's balance
$balance_query = "SELECT uang, emas FROM dompet WHERE id_user = ?";
$stmt_balance = $conn->prepare($balance_query);
$stmt_balance->bind_param("i", $id_user);
$stmt_balance->execute();
$balance_result = $stmt_balance->get_result();
$user_balance = $balance_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Tarik Saldo</title>
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
                        <h2>Tarik Saldo</h2>
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

                    <!-- Form Tarik Saldo -->
                    <?php if (isset($user_data) && !is_null($user_data)) { ?>
                    <form method="POST" action="">
                        <!-- Withdrawal Type Selection -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label><input type="radio" name="withdraw_type" value="money" required> Tarik
                                    Uang</label><br>
                                <label><input type="radio" name="withdraw_type" value="gold"> Tarik Emas</label>
                            </div>
                        </div>

                        <!-- Amount Section (Dynamic based on selection) -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div id="money_input" style="display: none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="text" name="jumlah_uang" class="form-control"
                                            placeholder="Jumlah Uang">
                                    </div>
                                    <small class="form-text text-muted">Jumlah uang yang ingin ditarik</small>
                                    <p id="sisa_saldo_uang" class="text-info"></p> <!-- Remaining money balance -->
                                </div>

                                <div id="gold_input" style="display: none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                        </div>
                                        <!-- <label for="jumlah_emas">Jumlah Emas (gram)</label> -->
                                        <select name="jumlah_emas" class="form-control">
                                            <option value="0.5">0.5 gram</option>
                                            <option value="1">1 gram</option>
                                            <option value="2">2 gram</option>
                                            <option value="5">5 gram</option>
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">Jumlah emas yang ingin ditarik</small>
                                    <p id="sisa_saldo_emas" class="text-info"></p> <!-- Remaining gold balance -->
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Field for User ID -->
                        <input type="hidden" name="id_user" value="<?php echo $user_data['id']; ?>">

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-8">
                                <button type="submit" name="withdraw" class="btn btn-success">Tarik</button>
                            </div>
                        </div>
                    </form>
                    <?php } else { ?>
                    <p> Silakan cari Data nasabah dengan NIK. </p>
                    <?php } ?>

                    <!-- Display any error or success messages -->
                    <?php if ($message) { ?>
                    <div class="alert alert-info">
                        <?php echo $message; ?>
                    </div>
                    <?php } ?>
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Akhir Main Content -->
    </div>

    <!-- Script for Dynamic Input Display -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const moneyRadio = document.querySelector('input[name="withdraw_type"][value="money"]');
        const goldRadio = document.querySelector('input[name="withdraw_type"][value="gold"]');
        const moneyInput = document.getElementById('money_input');
        const goldInput = document.getElementById('gold_input');
        const jumlahUang = document.getElementById('jumlah_uang');
        const jumlahEmas = document.getElementById('jumlah_emas');
        const sisaSaldoUang = document.getElementById('sisa_saldo_uang');
        const sisaSaldoEmas = document.getElementById('sisa_saldo_emas');


        moneyRadio.addEventListener('change', function() {
            if (moneyRadio.checked) {
                moneyInput.style.display = 'block';
                goldInput.style.display = 'none';
            }
        });

        goldRadio.addEventListener('change', function() {
            if (goldRadio.checked) {
                goldInput.style.display = 'block';
                moneyInput.style.display = 'none';
            }
        });

        // Update remaining balance for money
        jumlahUang.addEventListener('input', function() {
            const tarikUang = parseFloat(jumlahUang.value) || 0;
            const remainingMoney = userMoneyBalance - tarikUang;
            sisaSaldoUang.textContent =
                `Sisa saldo uang: ${remainingMoney.toLocaleString('id-ID')} units`;
        });

        // Update remaining balance for gold
        jumlahEmas.addEventListener('change', function() {
            const tarikEmas = parseFloat(jumlahEmas.value) || 0;
            const remainingGold = userGoldBalance - tarikEmas;
            sisaSaldoEmas.textContent = `Sisa saldo emas: ${remainingGold.toFixed(2)} gram`;
        });
    });
    </script>
</body>

</html>