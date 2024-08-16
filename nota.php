<?php
include 'header.php';

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
            <button class="btn btn-success">Kembali</button>
            <button class="btn btn-primary mx-2">Simpan Nota</button>
            <button class="btn btn-info print-button" onclick="window.print()">Print</button>
        </div>

        <div class="text-center mt-4">
            <h1>Nota</h1>
        </div>

        <div class="mt-4">
            <p><strong>No Transaksi :</strong> 101158</p>
            <p><strong>Tanggal :</strong> 2024-02-08</p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Banyaknya</th>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2 Kg</td>
                    <td>Besi Bekasi</td>
                    <td>Rp5.000</td>
                    <td>Rp10.000</td>
                </tr>
                <!-- Additional rows can be added here -->
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th>Rp10.000</th>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>