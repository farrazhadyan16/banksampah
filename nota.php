<?php
include 'header.php'; // Includes header and database connection
include 'fungsi.php'; // Include functions file

$id_transaksi = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : '';

if ($id_transaksi) {
    // Fetch transaction details
    $transaksi_query = "SELECT * FROM transaksi WHERE id = ?";
    if ($stmt = $conn->prepare($transaksi_query)) {
        $stmt->bind_param("s", $id_transaksi);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaksi = $result->fetch_assoc();

        if ($transaksi) {
            // Fetch details for items
            $items_query = "SELECT s.jenis as barang_name, ks.name as kategori_name, ss.jumlah_kg, ss.jumlah_rp
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
                echo "Error preparing statement for items: " . $conn->error;
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
            <a href="setor_sampah.php" class="btn btn-success">Kembali</a>
            <button class="btn btn-info print-button" onclick="window.print()">Print</button>
        </div>

        <div class="text-center mt-4">
            <h1 class="nota-title">Nota Transaksi</h1>
        </div>

        <?php if (!empty($transaksi)): ?>
        <div class="mt-4">
            <p><strong>No Transaksi :</strong> <?php echo htmlspecialchars($transaksi['id']); ?></p>
            <p><strong>Tanggal :</strong> <?php echo htmlspecialchars($transaksi['date']); ?></p>
        </div>

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
                    <th colspan="3" class="text-end">Total</th>
                    <th><?php echo 'Rp ' . number_format(array_sum(array_column($items, 'jumlah_rp')), 0, ',', '.'); ?>
                    </th>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <div class="text-center">
                <p>Tanda Terima</p><br>
                <p>.........................</p>
            </div>
            <div class="text-center">
                <p>Hormat Kami,</p><br>
                <p>.........................</p>
            </div>
        </div>
        <?php else: ?>
        <p class="text-center text-danger mt-4">Invalid or missing transaction details.</p>
        <?php endif; ?>
    </div>
</body>

</html>