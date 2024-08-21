<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Dashboard</title>

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

        .header--wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header--title h2 {
            margin: 0;
        }

        .dashboard-cards {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .card {
            flex: 1;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card span {
            font-size: 16px;
        }

        .card-icon i {
            font-size: 24px;
            color: #68A4B4;
        }

        .dashboard-content {
            display: flex;
            gap: 20px;
        }

        .grafik-penyetoran,
        .history {
            flex: 1;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
        }

        .grafik-penyetoran h3,
        .history h3 {
            margin: 0 0 15px;
        }



        .grafik-penyetoran canvas {
            margin-top: 20px;
        }

        .history .date-range {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .history .date-range input {
            padding: 5px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .history .date-range button {
            padding: 5px 10px;
            background-color: #68A4B4;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .no-transactions {
            color: #888;
            font-size: 14px;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container h3 {
            margin-bottom: 5px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-container th,
        .table-container td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table-container th {
            background-color: #68A4B4;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
        }

        .table-container tr:hover {
            background-color: #f1f1f1;
        }

        .table-container tbody tr:last-child td {
            border-bottom: none;
        }
    </style>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jQuery and jQuery UI CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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

            <div class="dashboard-cards">
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
            </div>

            <div class="dashboard-content">
                <div class="grafik-penyetoran">
                    <h3>Grafik Penyetoran</h3>
                    <input type="text" id="startDate" placeholder="Pilih tanggal mulai">
                    <input type="text" id="endDate" placeholder="Pilih tanggal akhir">
                    <button id="filterButton">Filter</button>
                    <canvas id="myChart"></canvas>
                </div>

                <div class="history">
                    <h3>History</h3>
                    <div class="date-range">
                        <input type="text" id="historyStartDate" placeholder="Pilih tanggal mulai">
                        <input type="text" id="historyEndDate" placeholder="Pilih tanggal akhir">
                        <button id="historyFilterButton">Filter</button>
                    </div>
                    <div class="no-transactions" id="historyContent">
                        belum ada transaksi
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
                        <tr>
                            <td>1</td>
                            <td>Plastik</td>
                            <td>Plastik Jenis A</td>
                            <td>Rp. 1,200</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Kertas</td>
                            <td>Kertas Jenis A</td>
                            <td>Rp. 1,200</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Logam</td>
                            <td>Logam Jenis A</td>
                            <td>Rp. 1,200</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Lain-Lain</td>
                            <td>Lainnya Jenis A</td>
                            <td>Rp. 1,200</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Kat XX</td>
                            <td>XX Jenis 1</td>
                            <td>Rp. 1,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>