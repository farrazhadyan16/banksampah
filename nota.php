<?php
include 'header.php';
include 'fungsi.php'; // Ensure you have the database connection

$id_trans = isset($_GET['id_trans']) ? $_GET['id_trans'] : '';

// Fetch transaction details
$transaksi_query = "SELECT * FROM transaksi_tb WHERE id_trans = ?";
if ($stmt = $conn->prepare($transaksi_query)) {
    $stmt->bind_param("s", $id_trans);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaksi = $result->fetch_assoc();

    // Fetch details for items
    $items_query = "SELECT t.*, k.name as kategori_name 
                    FROM transaksi_tb t 
                    JOIN kategori_sampah k ON t.kategori_id = k.id 
                    WHERE t.id_trans = ?";
    $items_stmt = $conn->prepare($items_query);
    $items_stmt->bind_param("s", $id_trans);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }
} else {
    echo "Error preparing statement: " . $conn->error;
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-start no-print">
            <button class="btn btn-success" onclick="window.history.back()">Kembali</button>
            <button class="btn btn-info print-button" onclick="window.print()">Print</button>
        </div>

        <div class="text-center mt-4">
            <h1>Nota</h1>
        </div>

        <div class="mt-4">
            <p><strong>No Transaksi :</strong> <?php echo htmlspecialchars($transaksi['id_trans']); ?></p>
            <p><strong>Tanggal :</strong> <?php echo htmlspecialchars($transaksi['tanggal']); ?></p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Barang</th>
                    <th>Banyaknya</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['kategori_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['jenis_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['jumlah']) . ' Kg'; ?></td>
                        <td><?php echo 'Rp ' . number_format($item['harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th><?php echo 'Rp ' . number_format(array_sum(array_map(function ($item) {
                            return $item['harga'];
                        }, $items)), 0, ',', '.'); ?></th>
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
    </div>
</body>

</html>