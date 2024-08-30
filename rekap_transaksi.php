<?php
include 'header.php';
include 'fungsi.php';

// Cek apakah pengguna sudah login
checkSession();

// Mendapatkan username dari session
$username = $_SESSION['username'];

// Get filter parameters from the GET request
$fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : '';
$toDate = isset($_GET['toDate']) ? $_GET['toDate'] : '';
$urutan = isset($_GET['urutan']) ? $_GET['urutan'] : 'terbaru';
$jenisTransaksi = isset($_GET['jenisTransaksi']) ? $_GET['jenisTransaksi'] : '';

// Build the query with filters
$query = "
    SELECT 
        DATE_FORMAT(t.date, '%Y-%m') AS bulan,
        COALESCE(SUM(CASE WHEN t.jenis_transaksi = 'setor_sampah' THEN ss.jumlah_rp END), 0) AS total_setor,
        COALESCE(SUM(CASE WHEN t.jenis_transaksi = 'jual_sampah' THEN js.jumlah_rp END), 0) AS total_jual,
        COALESCE(SUM(CASE WHEN t.jenis_transaksi = 'setor_sampah' THEN ss.jumlah_kg END), 0) AS total_kg_setor,
        COALESCE(SUM(CASE WHEN t.jenis_transaksi = 'jual_sampah' THEN js.jumlah_kg END), 0) AS total_kg_jual
    FROM transaksi t
    LEFT JOIN setor_sampah ss ON t.id = ss.id_transaksi AND t.jenis_transaksi = 'setor_sampah'
    LEFT JOIN jual_sampah js ON t.id = js.id_transaksi AND t.jenis_transaksi = 'jual_sampah'
    WHERE 1 = 1";

// Apply date filters
if (!empty($fromDate)) {
    $query .= " AND t.date >= '$fromDate'";
}
if (!empty($toDate)) {
    $query .= " AND t.date <= '$toDate'";
}

// Apply transaction type filter
if (!empty($jenisTransaksi)) {
    $query .= " AND t.jenis_transaksi = '$jenisTransaksi'";
}

// Order the results based on user selection
$query .= " GROUP BY bulan";
$query .= ($urutan == 'terlama') ? " ORDER BY bulan ASC" : " ORDER BY bulan DESC";

$transaksi_result = mysqli_query($conn, $query);

if (!$transaksi_result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Rekap Transaksi Bulanan</title>
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
                        <h2>Rekap Transaksi Bulanan</h2>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterTransaksiModal">
                        Filter Transaksi
                    </button>
                </div>

                <!-- Filter Modal -->
                <div class="modal fade" id="filterTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="filterTransaksiModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterTransaksiModalLabel">Filter Transaksi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="GET" action="">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="fromDate">From</label>
                                        <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="toDate">To</label>
                                        <input type="date" class="form-control" id="toDate" name="toDate" value="<?php echo $toDate; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="urutan">Urutan</label>
                                        <select class="form-control" id="urutan" name="urutan">
                                            <option value="terbaru" <?php if ($urutan == 'terbaru') echo 'selected'; ?>>Terbaru</option>
                                            <option value="terlama" <?php if ($urutan == 'terlama') echo 'selected'; ?>>Terlama</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenisTransaksi">Jenis transaksi</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenisTransaksi" id="setorSampah" value="setor_sampah" <?php if ($jenisTransaksi == 'setor_sampah') echo 'checked'; ?>>
                                            <label class="form-check-label" for="setorSampah">Setor sampah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenisTransaksi" id="jualSampah" value="jual_sampah" <?php if ($jenisTransaksi == 'jual_sampah') echo 'checked'; ?>>
                                            <label class="form-check-label" for="jualSampah">Jual sampah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenisTransaksi" id="konversiSaldo" value="konversi_saldo" <?php if ($jenisTransaksi == 'konversi_saldo') echo 'checked'; ?>>
                                            <label class="form-check-label" for="konversiSaldo">Konversi saldo</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenisTransaksi" id="tarikSaldo" value="tarik_saldo" <?php if ($jenisTransaksi == 'tarik_saldo') echo 'checked'; ?>>
                                            <label class="form-check-label" for="tarikSaldo">Tarik saldo</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenisTransaksi" id="semuaJenis" value="" <?php if (empty($jenisTransaksi)) echo 'checked'; ?>>
                                            <label class="form-check-label" for="semuaJenis">Semua jenis</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">OK</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Start of Transaction Table Section -->
                <div class="tabular--wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Total Setor Sampah (Rp)</th>
                                <th>Total Jual Sampah (Rp)</th>
                                <th>Total Kg Setor Sampah</th>
                                <th>Total Kg Jual Sampah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($transaksi_result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($transaksi_result)): ?>
                                    <tr>
                                        <td><?php echo $row['bulan']; ?></td>
                                        <td><?php echo number_format($row['total_setor'], 2); ?></td>
                                        <td><?php echo number_format($row['total_jual'], 2); ?></td>
                                        <td><?php echo number_format($row['total_kg_setor'], 2); ?></td>
                                        <td><?php echo number_format($row['total_kg_jual'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No transactions found for the selected filters.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- End of Transaction Table Section -->

            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>