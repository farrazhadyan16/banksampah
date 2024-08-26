<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $items = $_POST['items']; // Assuming 'items' is an array of the form data for each row
    $tanggal = date('Y-m-d');
    $waktu = date('H:i:s');

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Insert into transaksi
        $jenis_transaksi = 'setor_sampah';
        $total_jumlah_kg = 0;
        $total_jumlah_rp = 0;

        foreach ($items as $item) {
            $id_sampah = $item['id_sampah'];
            $jumlah_kg = $item['jumlah_kg'];
            $jumlah_rp = $item['jumlah_rp'];

            // Insert into setor_sampah
            $query_setor = "INSERT INTO setor_sampah (id_user, id_sampah, jumlah_kg, jumlah_rp, tanggal, waktu) 
                            VALUES ('$user_id', '$id_sampah', '$jumlah_kg', '$jumlah_rp', '$tanggal', '$waktu')";
            $conn->query($query_setor);

            // Update total jumlah_kg dan jumlah_rp
            $total_jumlah_kg += $jumlah_kg;
            $total_jumlah_rp += $jumlah_rp;
        }

        // Insert ke transaksi
        $query_transaksi = "INSERT INTO transaksi (id_user, jenis_transaksi, tanggal, waktu, jumlah_kg, jumlah_rp) 
                            VALUES ('$user_id', '$jenis_transaksi', '$tanggal', '$waktu', '$total_jumlah_kg', '$total_jumlah_rp')";
        $conn->query($query_transaksi);

        // Commit transaksi
        $conn->commit();

        $message = "Transaksi berhasil disimpan.";

    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        $message = "Terjadi kesalahan: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Transaksi</title>
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
                        <h2>Setor Sampah</h2>
                    </div>
                    <div class="user--info">
                        <a href="setor_sampah.php"><button type="button" name="button" class="inputbtn">Setor
                                Sampah</button></a>
                        <a href="Konversi.php"><button type="button" name="button" class="inputbtn">Konversi
                                Saldo</button></a>
                        <a href="tarik.php"><button type="button" name="button" class="inputbtn">Tarik
                                Saldo</button></a>
                        <a href="jual_sampah.php"><button type="button" name="button" class="inputbtn">Jual
                                Sampah</button></a>
                    </div>
                </div>

                <!-- Start of Form Section -->
                <div class="tabular--wrapper">
                    <!-- Search Section -->
                    <?php include("search_nik.php") ?>

                    <!-- Form Tarik Saldo -->
                    <form method="POST" action="">
                        <input type="hidden" name="user_id"
                            value="<?php echo isset($user_data['id']) ? $user_data['id'] : ''; ?>">
                        <!-- Table Section -->
                        <table class="table table-bordered" id="transaksiTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Jumlah(KG)</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <!-- Rows will be dynamically added here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total Harga:</th>
                                    <th id="totalHarga">Rp. 0</th>
                                </tr>
                            </tfoot>
                        </table>

                        <button type="button" class="btn btn-dark mb-3" onclick="addRow()">Tambah Baris</button>
                        <button type="submit" name="submit" class="btn btn-primary mb-3">SUBMIT</button>
                    </form>

                    <!-- Success/Error Message -->
                    <?php if (!empty($message)) { ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="text-success"><?php echo $message; ?></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>

    <script>
    // Function to add a new row to the table
    function addRow() {
        const table = document.getElementById('transaksiTable').getElementsByTagName('tbody')[0];
        const rowCount = table.rows.length;
        const row = table.insertRow(rowCount);

        row.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><select type="text" name="items[${rowCount}][no]" class="form-control" required></td>
                <td><select type="text" name="items[${rowCount}][kategori]" class="form-control" required></td>
                <td><select type="text" name="items[${rowCount}][jenis]" class="form-control" required></td>
                <td><input type="number" name="items[${rowCount}][jumlah_kg]" class="form-control jumlah_kg" required oninput="calculateTotal()"></td>
                <td><input type="number" name="items[${rowCount}][jumlah_rp]" class="form-control jumlah_rp" required oninput="calculateTotal()"></td>
            `;
    }

    // Function to calculate the total price
    function calculateTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#transaksiTable tbody tr');
        rows.forEach(row => {
            const jumlah_rp = parseFloat(row.querySelector('.jumlah_rp').value) || 0;
            total += jumlah_rp;
        });

        document.getElementById('totalHarga').innerText = `Rp. ${total.toLocaleString('id-ID')}`;
    }
    </script>
</body>

</html>