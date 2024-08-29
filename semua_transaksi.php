<?php
include 'header.php';
include 'fungsi.php';

// Cek apakah pengguna sudah login
checkSession();

// Mendapatkan username dari session
$username = $_SESSION['username'];

// Query untuk mengambil data transaksi
$query = "
    SELECT 
        t.id AS id, 
        u.username AS username, 
        CASE 
            WHEN ts.id_transaksi IS NOT NULL THEN 
                CASE 
                    WHEN ts.jenis_saldo = 'tarik_emas' THEN 'Tarik Saldo (Emas)'
                    WHEN ts.jenis_saldo = 'tarik_uang' THEN 'Tarik Saldo (Uang)'
                END
            WHEN ps.id_transaksi IS NOT NULL THEN 
                CASE 
                    WHEN ps.jenis_konversi = 'konversi_emas' THEN 'Pindah Saldo (Emas)'
                    WHEN ps.jenis_konversi = 'konversi_uang' THEN 'Pindah Saldo (Uang)'
                END
            WHEN ss.id_transaksi IS NOT NULL THEN 'Setor Sampah'
            WHEN js.id_transaksi IS NOT NULL THEN 'Jual Sampah'
        END AS jenis_transaksi,
        CASE 
            WHEN ts.id_transaksi IS NOT NULL THEN 
                CASE 
                    WHEN ts.jenis_saldo = 'tarik_emas' THEN CONCAT(ts.jumlah_tarik, ' Gram')
                    WHEN ts.jenis_saldo = 'tarik_uang' THEN CONCAT('Rp. ', FORMAT(ts.jumlah_tarik, 2))
                END
            WHEN ps.id_transaksi IS NOT NULL THEN 
                CASE 
                    WHEN ps.jenis_konversi = 'konversi_emas' THEN CONCAT(ps.jumlah, ' Gram')
                    WHEN ps.jenis_konversi = 'konversi_uang' THEN CONCAT('Rp. ', FORMAT(ps.jumlah, 2))
                END
            WHEN ss.id_transaksi IS NOT NULL THEN CONCAT(ss.jumlah_kg, ' KG')
            WHEN js.id_transaksi IS NOT NULL THEN CONCAT(js.jumlah_kg, ' KG')
        END AS jumlah,
        t.date AS date
    FROM 
        transaksi t
    LEFT JOIN 
        tarik_saldo ts ON t.id = ts.id_transaksi
    LEFT JOIN 
        pindah_saldo ps ON t.id = ps.id_transaksi
    LEFT JOIN 
        setor_sampah ss ON t.id = ss.id_transaksi
    LEFT JOIN 
        jual_sampah js ON t.id = js.id_transaksi
    LEFT JOIN 
        user u ON t.id_user = u.id
    ORDER BY 
        t.date DESC, t.time DESC
";




// Eksekusi query
$transaksi_result = query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Daftar Transaksi</title>
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
                        <h2>Daftar Transaksi</h2>
                    </div>
                </div>

                <!-- Start of Transaction Table Section -->
                <div class="tabular--wrapper">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th><?php
                                                        require_once 'header.php'; // Sertakan file header.php yang berisi session start dan koneksi database
                                                        require_once 'fungsi.php'; // Sertakan file fungsi untuk fungsi-fungsi yang diperlukan

                                                        // Cek apakah pengguna sudah login
                                                        checkSession();

                                                        // Mendapatkan username dari session
                                                        $username = $_SESSION['username'];

                                                        // Ambil data pengguna dari database
                                                        $data = getUserData($koneksi, $username);
                                                        $id_user = $data['id']; // Mendapatkan id_user dari data user yang sedang login

                                                        // Query untuk mengambil data kategori dan sampah
                                                        $sql = "SELECT ks.name AS kategori, s.jenis, s.harga 
        FROM sampah s 
        JOIN kategori_sampah ks ON s.id_kategori = ks.id";
                                                        $result = $koneksi->query($sql);

                                                        // Cek jika query berhasil
                                                        if ($result === false) {
                                                            echo "Error: " . $koneksi->error;
                                                            exit;
                                                        }

                                                        // Query untuk mengambil data transaksi, detail transaksi, dan informasi pengguna untuk user yang sedang login
                                                        $sqlTransaksi = "
SELECT 
    t.id AS id_transaksi, 
    t.jenis_transaksi, 
    t.date, 
    t.time, 
    CASE 
        WHEN t.jenis_transaksi = 'tarik_saldo' AND ts.jenis_saldo = 'emas' THEN CONCAT(ts.jumlah_tarik, ' gram')
        WHEN t.jenis_transaksi = 'tarik_saldo' AND ts.jenis_saldo = 'uang' THEN CONCAT('Rp ', FORMAT(ts.jumlah_tarik, 0))
    END AS jumlah_tarik,
    CASE 
        WHEN t.jenis_transaksi = 'setor_sampah' THEN CONCAT(ss.jumlah_kg, ' KG')
    END AS jumlah_sampah,
    CASE 
        WHEN t.jenis_transaksi = 'setor_sampah' THEN CONCAT('Rp ', FORMAT(ss.jumlah_rp, 0))
    END AS nilai_sampah,
    CASE 
        WHEN t.jenis_transaksi = 'pindah_saldo' AND ps.jenis_konversi = 'emas' THEN CONCAT(ps.hasil_konversi, ' gram')
        WHEN t.jenis_transaksi = 'pindah_saldo' AND ps.jenis_konversi = 'uang' THEN CONCAT('Rp ', FORMAT(ps.hasil_konversi, 0))
    END AS hasil_konversi,
    u.id AS id_user, 
    u.username
FROM transaksi t
LEFT JOIN tarik_saldo ts ON t.id = ts.id_transaksi
LEFT JOIN setor_sampah ss ON t.id = ss.id_transaksi
LEFT JOIN pindah_saldo ps ON t.id = ps.id_transaksi 
LEFT JOIN user u ON t.id_user = u.id
WHERE u.id = ?
ORDER BY t.time DESC";

                                                        // Prepare statement untuk menghindari SQL Injection
                                                        $stmtTransaksi = $koneksi->prepare($sqlTransaksi);
                                                        if (!$stmtTransaksi) {
                                                            die("Prepare statement failed: " . $koneksi->error);
                                                        }

                                                        // Bind parameter untuk id_user
                                                        $stmtTransaksi->bind_param("i", $id_user);
                                                        $stmtTransaksi->execute();
                                                        $resultTransaksi = $stmtTransaksi->get_result();

                                                        // Query to get the sum of waste deposits (in kg and Rp) per month for the logged-in user
                                                        $sqlMonthlySetorSampah = "
    SELECT 
        DATE_FORMAT(t.date, '%Y-%m') AS month,
        SUM(ss.jumlah_kg) AS total_kg,
        SUM(ss.jumlah_rp) AS total_rp
    FROM 
        transaksi t
    JOIN 
        setor_sampah ss ON t.id = ss.id_transaksi
    WHERE 
        t.jenis_transaksi = 'setor_sampah' AND t.id_user = ?
    GROUP BY 
        month
    ORDER BY 
        month ASC";

                                                        // Prepare and execute the query
                                                        $stmtMonthlySetorSampah = $koneksi->prepare($sqlMonthlySetorSampah);
                                                        if (!$stmtMonthlySetorSampah) {
                                                            die("Prepare statement failed: " . $koneksi->error);
                                                        }
                                                        $stmtMonthlySetorSampah->bind_param("i", $id_user);
                                                        $stmtMonthlySetorSampah->execute();
                                                        $resultMonthlySetorSampah = $stmtMonthlySetorSampah->get_result();

                                                        // Initialize arrays to hold the data
                                                        $months = [];
                                                        $totalKg = [];
                                                        $totalRp = [];

                                                        // Fetch the data and populate the arrays
                                                        if ($resultMonthlySetorSampah->num_rows > 0) {
                                                            while ($row = $resultMonthlySetorSampah->fetch_assoc()) {
                                                                $months[] = $row['month'];
                                                                $totalKg[] = $row['total_kg'];
                                                                $totalRp[] = $row['total_rp'];
                                                            }
                                                        }

                                                        // Close the statements
                                                        $stmtTransaksi->close();
                                                        $stmtMonthlySetorSampah->close();
                                                        ?>

                                <!DOCTYPE html>
                                <html lang="en">

                                <head>
                                    <meta charset="UTF-8">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <title>PMElectric | Dashboard</title>

                                    <!-- Font Awesome CDN -->
                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
                                    <!-- Chart.js CDN -->
                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <!-- jQuery and jQuery UI CDN -->
                                    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                                    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                                    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                                    <!-- CSS -->
                                    <link rel="stylesheet" href="./css/style.css">

                                    <!-- CSS -->
                                    <style>
                                        body {
                                            font-family: 'Arial', sans-serif;
                                            background-color: #f4f4f4;
                                            margin: 0;
                                            padding: 0;
                                        }

                                        #wrapper {
                                            display: flex;
                                        }

                                        .main--content {
                                            width: 100%;
                                            padding: 20px;
                                            background-color: #fff;
                                        }

                                        .transaction-list {
                                            max-height: 520px;
                                            overflow-y: auto;
                                            border: 1px solid #ddd;
                                            padding: 10px;
                                            background-color: #fff;
                                            border-radius: 5px;
                                        }

                                        .transaction-item {
                                            margin-bottom: 10px;
                                            padding: 10px;
                                            border-bottom: 1px solid #eee;
                                        }
                                    </style>
                                </head>

                                <body>
                                    <div id="wrapper">
                                        <!-- Sidebar -->
                                        <?php include("sidebar.php") ?>

                                        <!-- Main Content -->
                                        <div class="main--content">
                                            <div class="header--wrapper">
                                                <div class="header--title">
                                                    <span>Halaman</span>
                                                    <h2>Dashboard</h2>
                                                </div>
                                                <div class="user--info">
                                                    <img src="./img/logoPM.png" alt="logo">
                                                </div>
                                            </div>

                                            <!-- Grafik Setor Sampah Bulanan -->
                                            <div class="dashboard-content">
                                                <div class="grafik-penyetoran">
                                                    <h3>Grafik Setor Sampah Bulanan</h3>
                                                    <canvas id="setorSampahChart"></canvas>
                                                </div>

                                                <!-- Riwayat Transaksi -->
                                                <div class="history">
                                                    <h3>Riwayat Transaksi</h3>
                                                    <div class="transaction-list">
                                                        <?php
                                                        if ($resultTransaksi->num_rows > 0) {
                                                            while ($row = $resultTransaksi->fetch_assoc()) {
                                                                echo "<div class='transaction-item'>";
                                                                echo "<div class='transaction-header'>";
                                                                echo "<span class='transaction-type'>" . ucfirst($row['jenis_transaksi']) . "</span>";
                                                                echo "<span class='transaction-date'>" . date('d M Y', strtotime($row['date'])) . " | " . date('H:i:s', strtotime($row['time'])) . "</span>";
                                                                echo "</div>";

                                                                if ($row['jenis_transaksi'] == 'tarik_saldo') {
                                                                    echo "<div class='transaction-body'>";
                                                                    echo "<span class='transaction-detail'>Jenis Saldo: " . ucfirst($row['jenis_saldo']) . "</span>";
                                                                    echo "<span class='transaction-amount' style='color: red;'>" . $row['jumlah_tarik'] . "</span>";
                                                                    echo "</div>";
                                                                } elseif ($row['jenis_transaksi'] == 'setor_sampah') {
                                                                    echo "<div class='transaction-body'>";
                                                                    echo "<span class='transaction-detail'>Sampah: " . ucfirst($row['id_sampah']) . " (" . $row['jumlah_sampah'] . ")</span>";
                                                                    echo "<span class='transaction-amount' style='color: #28a745;'>+ " . $row['nilai_sampah'] . "</span>";
                                                                    echo "</div>";
                                                                } elseif ($row['jenis_transaksi'] == 'pindah_saldo') {
                                                                    echo "<div class='transaction-body'>";
                                                                    echo "<span class='transaction-detail'>Jenis Konversi: " . ucfirst($row['jenis_konversi']) . "</span>";
                                                                    echo "<span class='transaction-amount'>" . $row['hasil_konversi'] . "</span>";
                                                                    echo "</div>";
                                                                }
                                                                echo "</div>";
                                                            }
                                                        } else {
                                                            echo "<p>Tidak ada transaksi untuk ditampilkan.</p>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart.js untuk Grafik -->
                                    <script>
                                        // Data grafik setor sampah bulanan
                                        const ctx = document.getElementById('setorSampahChart').getContext('2d');
                                        const setorSampahChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: <?php echo json_encode($months); ?>,
                                                datasets: [{
                                                    label: 'Total KG',
                                                    data: <?php echo json_encode($totalKg); ?>,
                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                    borderWidth: 1
                                                }, {
                                                    label: 'Total Rp',
                                                    data: <?php echo json_encode($totalRp); ?>,
                                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                    borderColor: 'rgba(255, 99, 132, 1)',
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    </script>
                                </body>

                                </html>

                                <th>Username</th>
                                <th>Jenis Transaksi</th>
                                <th>Jumlah </th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($transaksi_result) > 0): ?>
                                <?php foreach ($transaksi_result as $row): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['jenis_transaksi']; ?></td>
                                        <td><?php echo $row['jumlah']; ?></td>
                                        <td><?php echo $row['date']; ?></td>
                                        <td>
                                            <a href="cetak_nota.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Cetak</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada transaksi ditemukan.</td>
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

</body>

</html>