<?php
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
SELECT t.id AS id_transaksi, t.jenis_transaksi, t.date, t.time, 
       ts.jenis_saldo, ts.jumlah_tarik, 
       ss.id_sampah, ss.jumlah_kg, ss.jumlah_rp,
       ps.jumlah, ps.hasil_konversi, ps.jenis_konversi, 
       u.id AS id_user, u.username
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
$stmt = $koneksi->prepare($sqlMonthlySetorSampah);
$stmt->bind_param("i", $id_user); // Bind the user ID to the query
$stmt->execute();
$resultMonthlySetorSampah = $stmt->get_result();

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

// Close the statement
$stmt->close();
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

            <!-- <div class="dashboard-cards">
                <div class="card">
                    <span>Kertas</span>
                    <span>0.0 Kg</span>
                </div>
                <div class="card">
                    <span>Logam</span>
                    <span>0.0 Kg</span>
                </div>
                <div class="card">
                    <span>Plastik</span>
                    <span>0.0 Kg</span>
                </div>
                <div class="card">
                    <span>Lain-Lain</span>
                    <span>0.0 Kg</span>
                </div>
            </div> -->

            <div class="dashboard-content">
                <div class="grafik-penyetoran">
                    <h3>Grafik Setor Sampah Bulanan</h3>
                    <canvas id="setorSampahChart"></canvas>
                </div>

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
                                    echo "<span class='transaction-amount' style='color: red;'>- Rp. " . number_format($row['jumlah_tarik'], 2, ',', '.') . "</span>";
                                    echo "</div>";
                                } elseif ($row['jenis_transaksi'] == 'setor_sampah') {
                                    echo "<div class='transaction-body'>";
                                    echo "<span class='transaction-detail'>Sampah: " . ucfirst($row['id_sampah']) . " (" . $row['jumlah_kg'] . " Kg)</span>";
                                    echo "<span class='transaction-amount' style='color: #28a745;'>+ Rp. " . number_format($row['jumlah_rp'], 2, ',', '.') . "</span>";
                                    echo "</div>";
                                } elseif ($row['jenis_transaksi'] == 'pindah_saldo') {
                                    echo "<div class='transaction-body'>";
                                    echo "<span class='transaction-detail'>Jumlah: Rp. " . number_format($row['jumlah'], 2, ',', '.') . "</span>";
                                    echo "<span class='transaction-amount' style='color: #1E90FF;'>" . number_format($row['hasil_konversi'], 4, ',', '.') . " | " . $row['jenis_konversi'] . "</span>";
                                    echo "</div>";
                                }

                                echo "</div>";
                            }
                        } else {
                            echo "<p>Tidak ada transaksi ditemukan.</p>";
                        }
                        ?>
                    </div>
                </div>




            </div>

            <div class="additional-info">
                <div class="user-card">
                    <div class="user-card-header">
                        <span class="wifi-icon"><i class="fas fa-wifi"></i></span>
                        <span class="account-number"><?php echo $data['nik']; ?></span>
                    </div>
                    <div class="user-details">
                        <p>Username: <?php echo $data['username']; ?></p>
                    </div>

                    <div class="user-balance">
                        <div class="balance-card">
                            <span>Tunai</span>
                            <span>Rp <?php echo number_format($data['uang'], 2, ',', '.'); ?></span>
                        </div>
                        <div class="balance-card">
                            <span>Emas</span>
                            <span><?php echo number_format($data['emas'], 4, ',', '.'); ?> g</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="table-container">
                <h3>Jenis-jenis Sampah</h3>
                <p>*harga dapat berubah sewaktu-waktu</p>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row['kategori'] . "</td>";
                                echo "<td>" . $row['jenis'] . "</td>";
                                echo "<td>Rp. " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var ctx = document.getElementById('setorSampahChart').getContext('2d');
        var setorSampahChart = new Chart(ctx, {
            type: 'bar', // or 'line', 'pie', etc.
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                        label: 'Total Kg',
                        data: <?php echo json_encode($totalKg); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Rp',
                        data: <?php echo json_encode($totalRp); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
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