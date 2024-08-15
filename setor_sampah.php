<?php
// Include file fungsi.php untuk koneksi ke database
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Jika tombol CHECK ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    if (empty($search_value)) {
        $message = "NIK tidak boleh kosong.";
    } else {
        $user_query = "SELECT * FROM user WHERE nik LIKE '%$search_value%' AND role = 'Nasabah'";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
        } else {
            $message = "User dengan role 'Nasabah' tidak ditemukan.";
        }
    }
}

// Jika tombol SUBMIT ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id_sampah = $_POST['id_sampah'];
    $jumlah_kg = $_POST['jumlah_kg'];
    $jumlah_rp = $_POST['jumlah_rp'];

    // Generate id_transaksi unik
    $id_transaksi = uniqid('trx_');

    // Initialize array to keep track of total amounts by id_sampah
    $totals = [];

    for ($i = 0; $i < count($id_sampah); $i++) {
        $id_sampah_value = $id_sampah[$i];
        $jumlah_kg_value = $jumlah_kg[$i];
        $jumlah_rp_value = str_replace(['Rp.', ','], '', $jumlah_rp[$i]);

        if ($id_sampah_value && $jumlah_kg_value && $jumlah_rp_value) {
            // Accumulate totals for each id_sampah
            if (!isset($totals[$id_sampah_value])) {
                $totals[$id_sampah_value] = ['jumlah_kg' => 0, 'jumlah_rp' => 0];
            }

            $totals[$id_sampah_value]['jumlah_kg'] += $jumlah_kg_value;
            $totals[$id_sampah_value]['jumlah_rp'] += $jumlah_rp_value;
        }
    }

    // Insert data ke tabel setor_sampah
    foreach ($totals as $id_sampah_value => $total) {
        $setor_query = "INSERT INTO setor_sampah (id_transaksi, id_sampah, jumlah_kg, jumlah_rp) 
                        VALUES ('$id_transaksi', '$id_sampah_value', '{$total['jumlah_kg']}', '{$total['jumlah_rp']}')";

        if ($conn->query($setor_query) === TRUE) {
            $message = "Data setor sampah berhasil disimpan.";
        } else {
            $message = "Error: " . $setor_query . "\n" . $conn->error;
        }
    }
}

// Fetch data kategori
$kategori_query = "SELECT id, name FROM kategori_sampah";
$kategori_result = $conn->query($kategori_query);

// Fetch data jenis dan harga
$sampah_query = "SELECT id, jenis, harga, id_kategori FROM sampah";
$sampah_result = $conn->query($sampah_query);

// Simpan data jenis sampah ke dalam array
$sampah_data = [];
if ($sampah_result->num_rows > 0) {
    while ($row = $sampah_result->fetch_assoc()) {
        $sampah_data[$row['id']] = [
            'jenis' => $row['jenis'],
            'harga' => $row['harga'],
            'id_kategori' => $row['id_kategori']
        ];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        var sampahData = <?php echo json_encode($sampah_data); ?>;

        function updateHarga(index) {
            var idSampah = document.getElementById('id_sampah_' + index).value;
            var jumlahKg = document.getElementById('jumlah_kg_' + index).value;
            var harga = sampahData[idSampah] ? sampahData[idSampah].harga : 0;
            var totalHarga = jumlahKg * harga;

            document.getElementById('jumlah_rp_' + index).value = 'Rp. ' + totalHarga.toLocaleString('id-ID');
            updateTotalHarga();
        }

        function updateTotalHarga() {
            var totalHarga = 0;
            var jumlahRpInputs = document.querySelectorAll('input[name="jumlah_rp[]"]');

            jumlahRpInputs.forEach(function(jumlahRpInput) {
                var harga = parseInt(jumlahRpInput.value.replace(/[Rp.,\s]/g, '')) || 0;
                totalHarga += harga;
            });

            document.getElementById('jumlah_rp_total').innerText = 'Rp. ' + totalHarga.toLocaleString('id-ID');
        }

        function updateJenis(index) {
            var kategoriSelect = document.getElementById('kategori_id_' + index);
            var idSampahSelect = document.getElementById('id_sampah_' + index);
            var selectedKategori = kategoriSelect.value;

            idSampahSelect.innerHTML = '<option value="">-- jenis sampah --</option>';
            for (var id in sampahData) {
                if (sampahData[id].id_kategori == selectedKategori) {
                    var option = document.createElement('option');
                    option.value = id;
                    option.text = sampahData[id].jenis;
                    idSampahSelect.add(option);
                }
            }
            idSampahSelect.value = "";
            updateHarga(index);
        }

        function addRow() {
            var table = document.getElementById('transaksiTable');
            var rowCount = table.rows.length - 1;
            var row = table.insertRow(rowCount);

            row.innerHTML = `
                <td><button class="btn btn-danger" onclick="removeRow(this)">&times;</button></td>
                <td>${rowCount}</td>
                <td>
                    <select name="kategori_id[]" id="kategori_id_${rowCount}" class="form-control" onchange="updateJenis(${rowCount})">
                        <option value="">-- kategori sampah --</option>
                        <?php
                        if ($kategori_result->num_rows > 0) {
                            while ($row = $kategori_result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="id_sampah[]" id="id_sampah_${rowCount}" class="form-control" onchange="updateHarga(${rowCount})">
                        <option value="">-- jenis sampah --</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlah_kg[]" id="jumlah_kg_${rowCount}" class="form-control" placeholder="Jumlah (kg)" oninput="updateHarga(${rowCount})">
                </td>
                <td>
                    <input type="text" name="jumlah_rp[]" id="jumlah_rp_${rowCount}" class="form-control" readonly>
                </td>
            `;
        }

        function removeRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateTotalHarga();
        }

        function validateSearchForm() {
            var searchValue = document.getElementById('search_value').value;
            if (searchValue.trim() === '') {
                alert('NIK tidak boleh kosong.');
                return false;
            } else if (searchValue.length !== 16 || isNaN(searchValue)) {
                alert('NIK harus berisi 16 digit angka.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div id="wrapper">
        <?php include("sidebar.php") ?>
        <
        <div class="main--content">
            <div class="main--content--monitoring">
                <div class="header--wrapper">
                    <div class="header--title">
                        <span>Halaman</span>
                        <h2>Transaksi</h2>
                    </div>
                </div>
                <div class="tabular--wrapper">
                    <form method="POST" action="" onsubmit="return validateSearchForm()">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search_value" id="search_value" class="form-control" placeholder="Search by NIK" maxlength="16">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="search" class="btn btn-dark w-100">CHECK</button>
                            </div>
                        </div>
                    </form>

                    <?php if (isset($user_data)) { ?>
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <p><strong>id</strong> : <?php echo $user_data['id']; ?></p>
                            <p><strong>NIK</strong> : <?php echo $user_data['nik']; ?></p>
                            <p><strong>email</strong> : <?php echo $user_data['email']; ?></p>
                            <p><strong>username</strong> : <?php echo $user_data['username']; ?></p>
                        </div>
                        <div class="col-md-5">
                            <p><strong>nama lengkap</strong> : <?php echo $user_data['nama']; ?></p>
                            <p><strong>Saldo Uang</strong> : Rp. 0.00</p>
                            <p><strong>Saldo Emas</strong> : 0.0000 g</p>
                        </div>
                    </div>
                    <?php } ?>

                    <form method="POST" action="">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="tanggal">Tanggal:</label>
                                <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="waktu">Waktu:</label>
                                <input type="time" name="waktu" class="form-control" value="<?php echo date('H:i'); ?>">
                            </div>
                        </div>

                        <table id="transaksiTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Kategori Sampah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Jumlah (kg)</th>
                                    <th>Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row pertama akan di generate secara dinamis melalui addRow() -->
                            </tbody>
                        </table>
                        
                        <button type="button" class="btn btn-success" onclick="addRow()">Tambah Baris</button>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5>Total Jumlah: <span id="jumlah_rp_total">Rp. 0</span></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" name="submit" class="btn btn-primary">SIMPAN TRANSAKSI</button>
                            </div>
                        </div>
                    </form>
                    <?php if (isset($message)) { echo '<p>' . $message . '</p>'; } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
