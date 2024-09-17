<?php
include 'header.php'; // Includes header and database connection
include 'fungsi.php'; // Include functions file

// Cek apakah pengguna sudah login
checkSession();

// Mendapatkan username dari session
$username = $_SESSION['username'];
$data = getUserData($koneksi, $username);
$i_user = $data['id']; // Mendapatkan id_user dari data user yang sedang login
$nama_admin = $data['nama']; // Mendapatkan nama admin dari data user yang sedang login

if (!isset($_GET['id_transaksi'])) {
    echo "No transaction ID provided.";
    exit();
}

$id_transaksi = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : '';

if ($id_transaksi) {
    // Fetch transaction details
    $transaksi_query = "SELECT t.*, u1.nik AS nik_penyetor, u1.nama AS nama_penyetor, t.jenis_transaksi
                        FROM transaksi t
                        JOIN user u1 ON t.id_user = u1.id
                        WHERE t.id = ?";
    if ($stmt = $conn->prepare($transaksi_query)) {
        $stmt->bind_param("s", $id_transaksi);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaksi = $result->fetch_assoc();

        if ($transaksi) {
            // Fetch details for items
            if ($transaksi['jenis_transaksi'] == 'setor_sampah') {
                $items_query = "SELECT s.jenis as barang_name, ks.name as kategori_name, ss.jumlah_kg, ss.jumlah_rp, ss.jumlah_emas
                                FROM setor_sampah ss
                                JOIN sampah s ON ss.id_sampah = s.id
                                JOIN kategori_sampah ks ON s.id_kategori = ks.id
                                WHERE ss.id_transaksi = ?";
                if ($items_stmt = $conn->prepare($items_query)) {
                    $items_stmt->bind_param("s", $id_transaksi);
                    $items_stmt->execute();
                    $items_result = $items_stmt->get_result();

                    $items = [];
                    while ($item = $items_result->fetch_assoc()) {
                        $items[] = $item;
                    }
                } else {
                    echo "Error preparing statement for setor_sampah: " . $conn->error;
                }
            } elseif ($transaksi['jenis_transaksi'] == 'pindah_saldo') {
                $pindah_saldo_query = "SELECT ps.jenis_konversi, ps.jumlah, ps.harga_beli_emas, ps.harga_jual_emas, ps.hasil_konversi
                                   FROM pindah_saldo ps
                                   WHERE ps.id_transaksi = ?";
                if ($pindah_stmt = $conn->prepare($pindah_saldo_query)) {
                    $pindah_stmt->bind_param("s", $id_transaksi);
                    $pindah_stmt->execute();
                    $pindah_result = $pindah_stmt->get_result();
                    $pindah_saldo = $pindah_result->fetch_assoc();

                    if ($pindah_saldo) {
                        // Determine which price to display based on jenis_konversi
                        if ($pindah_saldo['jenis_konversi'] == 'konversi_uang') {
                            $harga_emas = $pindah_saldo['harga_beli_emas'];
                        } elseif ($pindah_saldo['jenis_konversi'] == 'konversi_emas') {
                            $harga_emas = $pindah_saldo['harga_jual_emas'];
                        }
                    }
                } else {
                    echo "Error preparing statement for pindah_saldo: " . $conn->error;
                }
            } elseif ($transaksi['jenis_transaksi'] == 'tarik_saldo') {
                $tarik_saldo_query = "SELECT ps.jenis_saldo, ps.jumlah_tarik
                                   FROM tarik_saldo ps
                                   WHERE ps.id_transaksi = ?";
                if ($tarik_stmt = $conn->prepare($tarik_saldo_query)) {
                    $tarik_stmt->bind_param("s", $id_transaksi);
                    $tarik_stmt->execute();
                    $tarik_result = $tarik_stmt->get_result();
                    $tarik_saldo = $tarik_result->fetch_assoc();
                } else {
                    echo "Error preparing statement for tarik_saldo: " . $conn->error;
                }
            } elseif ($transaksi['jenis_transaksi'] == 'jual_sampah') {
                $juals_sampah_query = "SELECT s.jenis as barang_name, ks.name as kategori_name, ss.jumlah_kg, ss.harga_nasabah, ss.jumlah_rp
                                FROM jual_sampah ss
                                JOIN sampah s ON ss.id_sampah = s.id
                                JOIN kategori_sampah ks ON s.id_kategori = ks.id
                                WHERE ss.id_transaksi = ?";
                if ($juals_stmt = $conn->prepare($juals_sampah_query)) {
                    $juals_stmt->bind_param("s", $id_transaksi);
                    $juals_stmt->execute();
                    $juals_result = $juals_stmt->get_result();

                    $juals = [];
                    while ($jual = $juals_result->fetch_assoc()) {
                        $juals[] = $jual;
                    }
                } else {
                    echo "Error preparing statement for jual_saldo: " . $conn->error;
                }
            }
        } else {
            echo "No transaction found with the given ID.";
        }
    } else {
        echo "Error preparing statement for transaction: " . $conn->error;
    }
} else {
    echo "Transaction ID is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .print-button {
            margin-left: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .nota-title {
            text-transform: uppercase;
            font-weight: bold;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table tfoot th {
            font-size: 1.1rem;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-start no-print">
            <!-- <button class="btn btn-success" onclick="window.history.back()">Kembali</button> -->
            <button class="btn btn-info print-button" onclick="window.print()">Print</button>
        </div>

        <div class="text-center mt-4">
            <h1 class="nota-title">Nota Transaksi</h1>
        </div>

        <?php if (!empty($transaksi)): ?>
            <div class="mt-4">
                <p><strong>No Transaksi :</strong> <?php echo htmlspecialchars($transaksi['id']); ?></p>
                <p><strong>Tanggal :</strong> <?php echo htmlspecialchars($transaksi['date']); ?></p>
                <p><strong>NIK Penyetor :</strong> <?php echo htmlspecialchars($transaksi['nik_penyetor']); ?></p>
                <p><strong>Admin :</strong> <?php echo htmlspecialchars($nama_admin); ?></p>
            </div>

            <!-- berikut adalah nota untuk konversi.php -->
            <?php if ($transaksi['jenis_transaksi'] == 'pindah_saldo' && !empty($pindah_saldo)): ?>
                <!-- berikut adalah nota untuk konversi.php -->
                <hr>
                <p><strong>Jenis Konversi :</strong> <?php echo htmlspecialchars($pindah_saldo['jenis_konversi']); ?></p>
                <p><strong>Jumlah :</strong> <?php echo htmlspecialchars($pindah_saldo['jumlah']); ?></p>
                <p><strong>Harga Emas :</strong> <?php echo htmlspecialchars($harga_emas); ?></p>
                <p><strong>Hasil Konversi :</strong> <?php echo htmlspecialchars($pindah_saldo['hasil_konversi']); ?></p>
                <hr>
            <?php endif; ?>


            <!-- berikut adalah nota untuk tarik.php -->
            <?php if ($transaksi['jenis_transaksi'] == 'tarik_saldo' && !empty($tarik_saldo)): ?>
                <!-- berikut adalah nota untuk konversi.php -->
                <hr>
                <p><strong>Jenis Saldo :</strong> <?php echo htmlspecialchars($tarik_saldo['jenis_saldo']); ?></p>
                <p><strong>Jumlah Tarik Saldo:</strong>
                    <?php echo 'Rp ' . number_format($tarik_saldo['jumlah_tarik'], 2, '.', '.'); ?></p>
                <hr>
            <?php endif; ?>

            <!-- berikut adalah nota untuk setor_sampah.php -->
            <?php if ($transaksi['jenis_transaksi'] == 'setor_sampah' && !empty($items)): ?>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori</th>
                            <th>Barang</th>
                            <th>Banyaknya (Kg)</th>
                            <th>Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['kategori_name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['barang_name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['jumlah_kg']); ?></td>
                                    <td><?php echo 'Rp ' . number_format($item['jumlah_rp'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No items found for this transaction.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total Rupiah</th>
                            <th><?php echo 'Rp ' . number_format(array_sum(array_column($items, 'jumlah_rp')), 0, ',', '.'); ?>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Total Emas</th>
                            <th><?php echo number_format($item['jumlah_emas'], 4, '.', '.'); ?> gr

                            </th>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>

            <!-- berikut adalah nota untuk jual_sampah.php -->
            <?php if ($transaksi['jenis_transaksi'] == 'jual_sampah' && !empty($juals)): ?>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori Sampah</th>
                            <th>Jenis Sampah</th>
                            <th>Jumlah</th>
                            <th>Harga Jual</th>
                            <th>Harga Beli</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_selisih = 0; // Initialize total selisih
                        if (!empty($juals)):
                            foreach ($juals as $jual):
                                // Calculate the difference between jumlah_rp and harga_nasabah
                                $selisih = $jual['jumlah_rp'] - $jual['harga_nasabah'];
                                $total_selisih += $selisih; // Sum the selisih for the total
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($jual['kategori_name']); ?></td>
                                    <td><?php echo htmlspecialchars($jual['barang_name']); ?></td>
                                    <td><?php echo htmlspecialchars($jual['jumlah_kg']); ?></td>
                                    <td><?php echo 'Rp ' . number_format($jual['jumlah_rp'], 0, ',', '.'); ?></td>
                                    <td><?php echo 'Rp ' . number_format($jual['harga_nasabah'], 0, ',', '.'); ?></td>
                                    <td><?php echo 'Rp ' . number_format($selisih, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No items found for this transaction.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total Selisih</th>
                            <th><?php echo 'Rp ' . number_format($total_selisih, 0, ',', '.'); ?></th>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>


            <div class="d-flex justify-content-between mt-4">
                <div class="text-center">
                    <p>Tanda Terima</p><br>
                    <p><?php echo htmlspecialchars($transaksi['nama_penyetor']); ?></p> <!-- Menampilkan nama penyetor -->
                </div>
                <div class="text-center">
                    <p>Hormat Kami,</p><br>
                    <p><?php echo htmlspecialchars($nama_admin); ?></p> <!-- Menampilkan nama admin -->
                </div>
            </div>
        <?php else: ?>
            <p class="text-center text-danger mt-4">Invalid or missing transaction details.</p>
        <?php endif; ?>
    </div>
</body>

</html>