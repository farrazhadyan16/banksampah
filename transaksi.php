<?php
include 'header.php';
include 'fungsi.php';
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
    <!-- Ini Sidebar -->
    <?php include("sidebar.php") ?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="main--content--monitoring">
            <div class="header--wrapper">
                <div class="header--title">
                    <span>Halaman</span>
                    <h2>Transaksi</h2>
                </div>
            </div>

            <!-- Start of Form Section -->
            <div class="tabular--wrapper">
                <!-- Search Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search" value="bagaskoro">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-dark w-100">CHECK</button>
                    </div>
                </div>

                <!-- User Information Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>id</strong> : 154140010022</p>
                        <p><strong>email</strong> : elkoro424@gmail.com</p>
                        <p><strong>username</strong> : bagaselokoro</p>
                        <p><strong>nama lengkap</strong> : bagaskoro</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Saldo Uang</strong> : Rp. 0.00</p>
                        <p><strong>Saldo Emas</strong> : 0.0000 g</p>
                    </div>
                </div>

                <!-- Date and Time Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="date" class="form-control" value="2024-08-12">
                    </div>
                    <div class="col-md-4">
                        <input type="time" class="form-control" value="11:13">
                    </div>
                </div>

                <!-- Table Section -->
                <table class="table table-bordered">
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
                        <tr>
                            <td><button class="btn btn-danger">&times;</button></td>
                            <td>1</td>
                            <td>
                                <select class="form-control">
                                    <option>-- kategori sampah --</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option>-- jenis sampah --</option>
                                </select>
                            </td>
                            <td><input type="number" class="form-control" value="0"></td>
                            <td><input type="text" class="form-control" value="Rp. 0" readonly></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-info text-white">
                            <td colspan="5">Total harga</td>
                            <td>Rp. 0</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Add Button Section -->
                <div class="row mb-4">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary">Tambah</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success">SUBMIT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Form Section -->
    </div>
    <!-- Batas Akhir Main-Content -->

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>