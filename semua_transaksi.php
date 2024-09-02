<?php
include 'header.php';
include 'fungsi.php';

// Cek apakah pengguna sudah login
checkSession();

// Mendapatkan username dari session
$username = $_SESSION['username'];

// Mendapatkan parameter pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Memodifikasi query untuk menambahkan pencarian
$query = "
    SELECT 
        t.id AS id, 
        u.username AS username, 
        GROUP_CONCAT(DISTINCT 
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
            END 
        SEPARATOR ', ') AS jenis_transaksi,
        GROUP_CONCAT(DISTINCT 
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
            END 
        SEPARATOR ' | ') AS jumlah,
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
    WHERE 
        t.id LIKE '%$search%' OR 
        u.username LIKE '%$search%' OR 
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
        END LIKE '%$search%'
    GROUP BY 
        t.id, t.date, u.username
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
                        <h2>Rekap Transaksi</h2>
                    </div>
                </div>

                <!-- Start of Transaction Table Section -->
                <div class="tabular--wrapper">
                    <!-- Form Pencarian -->
                    <form method="GET" action="">
                        <div class="form-group">
                            <label for="search">Cari Transaksi:</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Masukkan ID Transaksi, Username, atau Jenis Transaksi"
                                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <button type="submit" class="inputbtn">Cari</button>

                        </div>
                    </form>
                    <!-- End of Form Pencarian -->

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Username</th>
                                <th>Jenis Transaksi</th>
                                <th>Jumlah (KG)</th>
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
                                    <button class="btn btn-info print-button" onclick="window.print()">Print</button>
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