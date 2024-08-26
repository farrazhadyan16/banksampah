<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Retrieve the last inserted transaction ID
$query = "SELECT no FROM transaksi ORDER BY no DESC LIMIT 1";
$result = $conn->query($query);

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
    $date = date('Y-m-d'); // Get the current date and time
    $time = date('H:i:s'); // Get the current date and time
    $transaksi_query = "INSERT INTO transaksi (no, id, id_user, jenis_transaksi, date, time) VALUES (NULL, '$id', '$id_user', '$jenis_transaksi', '$date', '$time')";

    if ($conn->query($transaksi_query) === TRUE) {
        // The transaction ID is correctly inserted into the transaksi table
        $id_transaksi = $id; // Use the same ID for the tarik_saldo table
        
        // Validasi jenis penarikan
        if (isset($_POST['withdraw_type']) && ($_POST['withdraw_type'] === 'money' || $_POST['withdraw_type'] === 'gold')) {
            $withdraw_type = $_POST['withdraw_type'];

            // Tentukan jumlah berdasarkan jenis penarikan
            $jumlah_tarik = ($withdraw_type === 'money') ? $_POST['jumlah_uang'] : $_POST['jumlah_emas'];

            // Validasi input
            if (empty($jumlah_tarik) || !is_numeric($jumlah_tarik)) {
                $message = "Jumlah yang ditarik harus diisi dan berupa angka.";
            } else {
                try {
                    // Mulai transaksi
                    $conn->begin_transaction();

                    // Insert data ke tabel tarik_saldo
                    $jenis_saldo = ($withdraw_type === 'money') ? 'tarik_uang' : 'tarik_emas';
                    $stmt = $conn->prepare("INSERT INTO tarik_saldo (no, id_transaksi, jenis_saldo, jumlah_tarik) VALUES (NULL, ?, ?, ?)");
                    if (!$stmt) {
                        throw new Exception("Preparation failed: " . $conn->error);
                    }
                    $stmt->bind_param("ssi", $id_transaksi, $jenis_saldo, $jumlah_tarik);
                    if (!$stmt->execute()) {
                        throw new Exception("Execution failed: " . $stmt->error);
                    }

                    // Update saldo di tabel dompet berdasarkan jenis penarikan
                    if ($withdraw_type === 'money') {
                        $update_saldo_query = "UPDATE dompet SET uang = uang - ? WHERE id_user = ?";
                    } else {
                        $update_saldo_query = "UPDATE dompet SET emas = emas - ? WHERE id_user = ?";
                    }

                    $stmt_update = $conn->prepare($update_saldo_query);
                    if (!$stmt_update) {
                        throw new Exception("Preparation for saldo update failed: " . $conn->error);
                    }
                    $stmt_update->bind_param("di", $jumlah_tarik, $id_user);
                    if (!$stmt_update->execute()) {
                        throw new Exception("Execution for saldo update failed: " . $stmt_update->error);
                    }


                    // Commit transaksi
                    $conn->commit();

                    // Tampilkan pesan sukses
                    $message = "Penarikan " . ($withdraw_type === 'money' ? "uang" : "emas") . " berhasil! Saldo Anda telah diperbarui.";

                    // Close the statements
                    $stmt->close();
                    $stmt_update->close();
                } catch (Exception $e) {
                    // Rollback jika terjadi kesalahan
                    $conn->rollback();
                    $message = "Terjadi kesalahan saat melakukan penarikan: " . $e->getMessage();
                }
            }
        }
    } else {
        $message = "Gagal melakukan penarikan: " . $conn->error;
    }
}
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
                        <a href="konversi.php"><button type="button" name="button" class="inputbtn">Konversi Saldo
                            </button></a>
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
                    <!-- Form Tarik Saldo -->
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
                                </div>

                                <div id="gold_input" style="display: none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                        </div>
                                        <!-- <label for="jumlah_emas">Jumlah Emas (gram)</label> -->
                                        <select name="jumlah_emas" id="jumlah_emas" class="form-control">
                                            <option value=''>Pilih</option>
                                            <option value="0.5">0.5 gram</option>
                                            <option value="1">1 gram</option>
                                            <option value="2">2 gram</option>
                                            <option value="5">5 gram</option>
                                            <option value="10">10 gram</option>
                                            <option value="25">25 gram</option>
                                            <option value="50">50 gram</option>
                                            <option value="100">100 gram</option>
                                            <option value="250">250 gram</option>
                                            <option value="500">500 gram</option>
                                            <option value="1000">1000 gram</option>
                                        </select>
                                        <small class="form-text text-muted">Pilih jumlah emas yang ingin ditarik</small>
                                    </div>
                                    <small class="form-text text-muted">Jumlah emas yang ingin ditarik</small>
                                </div>
                            </div>
                        </div>

                        <!-- Include the hidden input for id_user -->
                        <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="id_user" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>

                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <button type="submit" name="withdraw" class="btn btn-primary w-100">Tarik</button>
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
    // Show/hide input fields based on withdrawal type
    const withdrawTypeRadios = document.querySelectorAll('input[name="withdraw_type"]');
    const moneyInput = document.getElementById('money_input');
    const goldInput = document.getElementById('gold_input');

    withdrawTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'money') {
                moneyInput.style.display = 'block';
                goldInput.style.display = 'none';
            } else {
                moneyInput.style.display = 'none';
                goldInput.style.display = 'block';
            }
        });
    });
    </script>
</body>

</html>