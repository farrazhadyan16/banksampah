<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="icon" href="img/PM.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .main--content {
            background-color: #f4f4f4;
        }

        .header--wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .dashboard--cards .card {
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .graph-history--wrapper {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .graph--section,
        .history--section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        #depositChart {
            height: 300px;
        }
    </style>
</head>

<body onload="getData()">
    <div id="wrapper" class="d-flex">
        <!-- Sidebar -->
        <?php include("sidebar.php") ?>
        <!-- End of Sidebar -->

        <!-- Main Content -->
        <div class="main--content">
            <div class="header--wrapper">
                <div class="header--title">
                    <span>Halaman</span>
                    <h2>Dashboard</h2>
                </div>
                <div class="user--info">
                    <!-- Add user info here if needed -->
                </div>
                <img src="./img/logoPM.png" alt="logo" width="100">
            </div>


            <div class="graph-history--wrapper mt-4">
                <div class="graph--section">
                    <h3>Grafik Penyetoran</h3>
                    <canvas id="depositChart"></canvas>
                </div>
                <div class="history--section">
                    <h3>History</h3>
                    <p>Belum ada transaksi</p>
                </div>
            </div>
        </div>
        <!-- End of Main Content -->
    </div>
    <!-- End of Wrapper -->

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Chart.js for graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function getData() {
            // This function can be used to fetch data and update the dashboard.
            console.log('Fetching data...');
        }

        // Sample chart implementation
        const ctx = document.getElementById('depositChart').getContext('2d');
        const depositChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Penyetoran',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false,
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