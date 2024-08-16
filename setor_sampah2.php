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
        $user_query = "SELECT * FROM user WHERE nik = '$search_value' AND role = 'Nasabah'";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
            $id_user = $user_data['id']; // Menyimpan id_user ke variabel
        } else {
            $message = "User dengan role 'Nasabah' tidak ditemukan.";
        }
    }
}

// Jika tombol SUBMIT ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Periksa apakah id_user telah diisi
    if (isset($_POST['id_user']) && !empty($_POST['id_user'])) {
        $id_user = $_POST['id_user'];
    } else {
        die("Error: ID User tidak ditemukan. Harap lakukan pengecekan NIK terlebih dahulu.");
    }

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
        $jumlah_rp_value_clean = str_replace(['Rp.', ','], '', $jumlah_rp[$i]);

        if ($id_sampah_value && $jumlah_kg_value && $jumlah_rp_value_clean) {
            // Accumulate totals for each id_sampah
            if (!isset($totals[$id_sampah_value])) {
                $totals[$id_sampah_value] = ['jumlah_kg' => 0, 'jumlah_rp' => 0];
            }

            $totals[$id_sampah_value]['jumlah_kg'] += $jumlah_kg_value;
            $totals[$id_sampah_value]['jumlah_rp'] += $jumlah_rp_value_clean;
        }
    }

    // Insert data ke tabel transaksi terlebih dahulu
    $conn->begin_transaction();
try {
    // Masukkan ke tabel transaksi
    $stmt = $conn->prepare("INSERT INTO transaksi (no, id, id_user, jenis_transaksi, date) VALUES (?, ?, ?, 'setor_sampah', ?)");
    
    $id_transaksi_var = $id_transaksi;  // Gunakan variabel untuk bind_param
    $date_var = date('Y-m-d');  // Gunakan variabel untuk bind_param

    foreach ($totals as $id_sampah_value => $total) {
        $stmt_no = $conn->prepare("SELECT IFNULL(MAX(no), 0) + 1 AS next_no FROM setor_sampah WHERE id_sampah = ?");
        $stmt_no->bind_param("s", $id_sampah_value);
        $stmt_no->execute();
        $result = $stmt_no->get_result();
        $row = $result->fetch_assoc();
        $no_var = $row['next_no'];  // Gunakan variabel untuk bind_param

        $stmt->bind_param("ssis", $id_transaksi_var, $no_var, $id_user, $date_var);
        $stmt->execute();
    }

    // Insert ke tabel setor_sampah
    $stmt_setor = $conn->prepare("INSERT INTO setor_sampah (id_transaksi, no, id_sampah, jumlah_kg, jumlah_rp) VALUES (?, ?, ?, ?, ?)");
    foreach ($totals as $id_sampah_value => $total) {
        $stmt_setor->bind_param("ssidd", $id_transaksi_var, $no_var, $id_sampah_value, $total['jumlah_kg'], $total['jumlah_rp']);
        $stmt_setor->execute();
    }

    // Commit transaksi setelah semua berhasil
    $conn->commit();
    $message = "Data setor sampah berhasil disimpan.";
} catch (Exception $e) {
    // Rollback jika terjadi error
    $conn->rollback();
    $message = "Error: " . $e->getMessage();
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
        var rowCount = table.rows.length - 1; // Adjust to ensure new rows are added before the total row
        var row = table.insertRow(rowCount);

        row.innerHTML = `
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">&times;</button></td>
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
                <td><input type="number" name="jumlah_kg[]" id="jumlah_kg_${rowCount}" class="form-control" oninput="updateHarga(${rowCount})"></td>
                <td><input type="text" name="jumlah_rp[]" id="jumlah_rp_${rowCount}" class="form-control" readonly></td>
            `;
    }

    function removeRow(button) {
        var row = button.parentElement.parentElement;
        row.parentElement.removeChild(row);
        updateTotalHarga();
    }
    </script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <!-- Main Content -->
        <div class="main_content">
            <div class="header">
                <h1>Transaksi Setor Sampah</h1>
            </div>
            <div class="info">
                <div class="form-container">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="search_value">Masukkan NIK Nasabah:</label>
                            <input type="text" class="form-control" id="search_value" name="search_value"
                                value="<?php echo isset($search_value) ? $search_value : ''; ?>">
                            <button type="submit" name="search" class="btn btn-primary">CHECK</button>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="id_user">ID Nasabah:</label>
                            <input type="text" class="form-control" id="id_user" name="id_user"
                                value="<?php echo isset($id_user) ? $id_user : ''; ?>" readonly>
                        </div>
                        <table class="table table-bordered" id="transaksiTable">
                            <thead>
                                <tr>
                                    <th>Remove</th>
                                    <th>No</th>
                                    <th>Kategori Sampah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Jumlah (kg)</th>
                                    <th>Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><button type="button" class="btn btn-danger"
                                            onclick="removeRow(this)">&times;</button></td>
                                    <td>1</td>
                                    <td>
                                        <select name="kategori_id[]" id="kategori_id_1" class="form-control"
                                            onchange="updateJenis(1)">
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
                                        <select name="id_sampah[]" id="id_sampah_1" class="form-control"
                                            onchange="updateHarga(1)">
                                            <option value="">-- jenis sampah --</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="jumlah_kg[]" id="jumlah_kg_1" class="form-control"
                                            oninput="updateHarga(1)"></td>
                                    <td><input type="text" name="jumlah_rp[]" id="jumlah_rp_1" class="form-control"
                                            readonly></td>
                                </tr>
                                <!-- Additional rows will be appended here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                    <td id="jumlah_rp_total">Rp. 0</td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" class="btn btn-secondary" onclick="addRow()">Tambah Baris</button>
                        <button type="submit" name="submit" class="btn btn-success">SUBMIT</button>
                    </form>
                    <?php if (!empty($message)) : ?>
                    <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>