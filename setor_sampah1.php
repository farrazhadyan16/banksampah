<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

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
                        <h2>Setor Sampah</h2>
                    </div>
                    <div class="user--info">
                        <a href="setor_sampah.php"><button type="button" name="button" class="inputbtn">Setor
                                Sampah</button></a>
                        <a href="Konversi.php"><button type="button" name="button" class="inputbtn">Konversi
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
                    <form method="POST" action="" onsubmit="return validateSearchForm()">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search_value" id="search_value" class="form-control"
                                    placeholder="Search by NIK" maxlength="16" oninput="validateNIK(this)" value="">
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
                            <p><strong>id</strong> : </p>
                            <p><strong>NIK</strong> : </p>
                            <p><strong>email</strong> : </p>
                            <p><strong>username</strong> : </p>
                        </div>
                        <div class="col-md-5">
                            <p><strong>nama lengkap</strong> : </p>
                            <p><strong>Saldo Uang</strong> : Rp.
                            </p>
                            <p><strong>Saldo Emas</strong> :
                            </p>
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
                        <input type="hidden" name="user_id" value="">
                        <?php } ?>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="date" name="tanggal" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <?php
                                // Set zona waktu ke WIB (UTC+7)
                                date_default_timezone_set('Asia/Jakarta');
                                $current_time = date('H:i');
                                ?>
                                <input type="time" name="waktu" class="form-control"
                                    value="<?php echo $current_time; ?>" disabled </div>
                            </div>

                            <!-- Table Section -->
                            <table class="table table-bordered" id="transaksiTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Jumlah(KG)</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                if ($kategori_result->num_rows > 0) {
                                    while ($row = $kategori_result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Total Harga:</th>
                                        <th id="totalHarga">Rp. 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="button" class="btn btn-dark mb-3" onclick="addRow()">Tambah Baris</button>
                            <button type="submit" name="submit" class="btn btn-primary mb-3">SUBMIT</button>
                    </form>
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>
</body>

</html>