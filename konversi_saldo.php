<?php
// Include file fungsi.php untuk koneksi ke database
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Jika tombol CHECK ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    if (empty($search_value)) {
        $message = "NIK tidak boleh kosong.";
    } else {
        $user_query = "SELECT user.*, dompet.uang FROM user 
                    LEFT JOIN dompet ON user.id = dompet.id_user 
                    WHERE user.nik LIKE '%$search_value%' AND user.role = 'Nasabah'";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
        } else {
            $message = "User dengan role 'Nasabah' tidak ditemukan.";
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
        <!-- Ini Sidebar -->
        <?php include("sidebar.php") ?>
        <!-- Batas Akhir Sidebar -->

        <!-- Ini Main-Content -->
        <div class="main--content">
            <div class="main--content--monitoring">
                <div class="header--wrapper">
                    <div class="header--title">
                        <span>Halaman</span>
                        <h2>Konversi Saldo</h2>
                    </div>
                    <div class="user--info">
                        <a href="setor_sampah.php"><button type="button" name="button" class="inputbtn">Setor Sampah</button></a>
                        <a href="konversi_saldo.php"><button type="button" name="button" class="inputbtn">Konversi Saldo</button></a>
                        <a href="inputnesting.php"><button type="button" name="button" class="inputbtn">Tarik Saldo</button></a>
                        <a href="inputprogram.php"><button type="button" name="button" class="inputbtn">Jual Sampah</button></a>
                    </div>
                </div>

                <!-- Start of Form Section -->
                <div class="tabular--wrapper">
                    <!-- Search Section -->
                    <form method="POST" action="" onsubmit="return validateSearchForm()">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search_value" id="search_value" class="form-control"
                                    placeholder="Search by NIK" maxlength="16" oninput="validateNIK(this)"
                                    value="<?php echo isset($search_value) ? $search_value : ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="search" class="btn btn-dark w-100">CHECK</button>
                            </div>
                        </div>
                    </form>

                    <!-- User Information Section -->
                    <?php if (isset($user_data)) { ?>
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <p><strong>id</strong> : <?php echo $user_data['id']; ?></p>
                                <p><strong>NIK</strong> : <?php echo $user_data['nik']; ?></p>
                                <p><strong>email</strong> : <?php echo $user_data['email']; ?></p>
                                <p><strong>username</strong> : <?php echo $user_data['username']; ?></p>
                            </div>
                            <div class="col-md-5">
                                <p><strong>nama lengkap</strong> : <?php echo $user_data['nama']; ?></p>
                                <p><strong>Saldo Uang</strong> : Rp. <?php echo number_format($user_data['uang'], 2, ',', '.'); ?></p>
                                <p><strong>Saldo Emas</strong> : 0.0000 g</p>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="text-danger"><?php echo $message; ?></p>
                            </div>
                        </div>
                    <?php } ?>


                    <!-- Date and Time Section -->
                    <form method="POST" action="">
                        <?php if (isset($user_data)) { ?>
                            <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="date" name="tanggal" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4">
                                <?php
                                // Set zona waktu ke WIB (UTC+7)
                                date_default_timezone_set('Asia/Jakarta');
                                $current_time = date('H:i');
                                ?>
                                <input type="time" name="waktu" class="form-control"
                                    value="<?php echo $current_time; ?>">
                            </div>
                        </div>

                        <!-- Table Section -->

                    </form>

                    <!-- Add this right after the time input element -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                </div>
                                <input type="text" name="harga_emas" class="form-control" placeholder="contoh: 900000">
                            </div>
                            <small class="form-text text-muted">Harga emas (saat ini)</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                </div>
                                <input type="text" name="jumlah_uang" class="form-control" placeholder="contoh: 10000">
                            </div>
                            <small class="form-text text-muted">Jumlah uang</small>
                        </div>
                    </div>

                    <!-- Add the Submit button -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </div>

                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>
</body>

</html>