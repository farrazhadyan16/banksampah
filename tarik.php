<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Jika tombol Submit ditekan untuk tarik saldo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdraw'])) {
    $user_id = $_POST['user_id'];

    // Check if withdrawing money
    if (isset($_POST['withdraw_type']) && $_POST['withdraw_type'] === 'money') {
        $jumlah_uang = $_POST['jumlah_uang'];

        // Validasi input
        if (empty($jumlah_uang) || !is_numeric($jumlah_uang)) {
            $message = "Jumlah uang harus diisi dan berupa angka.";
        } else {
            if (withdrawMoney($user_id, $jumlah_uang) === TRUE) {
                $message = "Penarikan uang berhasil! Saldo uang Anda telah diperbarui.";
            } else {
                $message = "Terjadi kesalahan saat melakukan penarikan: " . $conn->error;
            }
        }
    } 
    
    // Check if withdrawing gold
    else if (isset($_POST['withdraw_type']) && $_POST['withdraw_type'] === 'gold') {
        $jumlah_emas = $_POST['jumlah_emas'];

        // Validasi input
        if (empty($jumlah_emas) || !is_numeric($jumlah_emas)) {
            $message = "Jumlah emas harus diisi dan berupa angka.";
        } else {
            if (withdrawGold($user_id, $jumlah_emas) === TRUE) {
                $message = "Penarikan emas berhasil! Saldo emas Anda telah diperbarui.";
            } else {
                $message = "Terjadi kesalahan saat melakukan penarikan: " . $conn->error;
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
                                Emas</button></a>
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
                                        <input type="text" name="jumlah_emas" class="form-control"
                                            placeholder="Jumlah Emas (gram)">
                                    </div>
                                    <small class="form-text text-muted">Jumlah emas yang ingin ditarik</small>
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