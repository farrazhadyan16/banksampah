<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Jika tombol SUBMIT ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $kategori_ids = $_POST['kategori_id'];
    $id_sampahs = $_POST['id_sampah'];
    $jumlahs = $_POST['jumlah'];
    $hargas = $_POST['harga'];

    // Mendapatkan nomor urut terakhir hanya sekali
    $id_trans_query = "SELECT id FROM jual_sampah ORDER BY id DESC LIMIT 1";
    $result = $conn->query($id_trans_query);
    $last_id = ($result->num_rows > 0) ? $result->fetch_assoc()['id'] : 0;
    $new_id = $last_id + 1;

    // Membuat id_trans baru dengan format tertentu
    $id_trans = 'TRANS' . date('Y') . str_pad($new_id, 6, '0', STR_PAD_LEFT);

    // Total uang yang harus ditambahkan ke saldo
    $total_uang = 0;

    // Loop untuk memasukkan setiap baris data
    for ($i = 0; $i < count($kategori_ids); $i++) {
        $kategori_id = $kategori_ids[$i];
        $id_sampah = $id_sampahs[$i];
        $jumlah = $jumlahs[$i];

        // Menghapus awalan "Rp." dan karakter lainnya dari harga
        $harga = str_replace(['Rp. ', '.', ','], '', $hargas[$i]);

        // Menyiapkan query SQL untuk memasukkan data
        $transaksi_query = "INSERT INTO jual_sampah (id, id_trans, tanggal, waktu, id_kategori, id_sampah, jumlah_kg, jumlah_rp) 
                            VALUES (NULL, '$id_trans', '$tanggal', '$waktu', '$kategori_id', '$id_sampah', '$jumlah', '$harga')";

        // Menjalankan query langsung tanpa bind_param
        if ($conn->query($transaksi_query) === TRUE) {
            $total_uang += $harga; // Menambahkan harga ke total uang
        } else {
            $message = "Error: " . $conn->error;
        }
    }

    // Redirect to nota.php
    header("Location: nota.php?id_trans=$id_trans");
}

// Fetch data kategori
$kategori_query = "SELECT id, name FROM kategori_sampah";
$kategori_result = $conn->query($kategori_query);

// Fetch data jenis dan harga
$jenis_query = "SELECT id, jenis, harga, id_kategori FROM sampah";
$jenis_result = $conn->query($jenis_query);

// Simpan data jenis sampah ke dalam array
$jenis_sampah = [];
if ($jenis_result->num_rows > 0) {
    while ($row = $jenis_result->fetch_assoc()) {
        $jenis_sampah[$row['id']] = [
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
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
    var jenisSampah = <?php echo json_encode($jenis_sampah); ?>;

    function updateHarga(index) {
        var jenisId = document.getElementById('jenis_id_' + index).value;
        var jumlah = document.getElementById('jumlah_' + index).value;
        var harga = jenisSampah[jenisId] ? jenisSampah[jenisId].harga : 0;
        var totalHarga = jumlah * harga;

        document.getElementById('harga_' + index).value = 'Rp. ' + totalHarga.toLocaleString('id-ID');
        updateTotalHarga();
    }

    function updateTotalHarga() {
        var totalHarga = 0;
        var hargaInputs = document.querySelectorAll('input[name="harga[]"]');

        hargaInputs.forEach(function(hargaInput) {
            var harga = parseInt(hargaInput.value.replace(/[Rp.,\s]/g, '')) || 0;
            totalHarga += harga;
        });

        document.getElementById('totalHarga').innerText = 'Rp. ' + totalHarga.toLocaleString('id-ID');
    }

    function updateJenis(index) {
        var kategoriSelect = document.getElementById('kategori_id_' + index);
        var jenisSelect = document.getElementById('jenis_id_' + index);
        var selectedKategori = kategoriSelect.value;

        jenisSelect.innerHTML = '<option value="">-- jenis sampah --</option>';
        for (var id in jenisSampah) {
            if (jenisSampah[id].id_kategori == selectedKategori) {
                var option = document.createElement('option');
                option.value = id;
                option.text = jenisSampah[id].jenis;
                jenisSelect.add(option);
            }
        }
        jenisSelect.value = "";
        updateHarga(index);
    }

    function addRow() {
        var table = document.getElementById('transaksiTable');
        var rowCount = table.rows.length - 1; // Mengurangi 1 untuk tidak menghitung footer
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
                    <select name="id_sampah[]" id="jenis_id_${rowCount}" class="form-control" onchange="updateHarga(${rowCount})">
                        <option value="">-- jenis sampah --</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlah[]" id="jumlah_${rowCount}" class="form-control" placeholder="Jumlah" oninput="updateHarga(${rowCount})">
                </td>
                <td>
                    <input type="text" name="harga[]" id="harga_${rowCount}" class="form-control" readonly>
                </td>
            `;
    }

    function removeRow(button) {
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        updateTotalHarga();
    }
    </script>
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
                        <h2>Jual Sampah</h2>
                    </div>
                    <div class="user--info">
                        <a href="setor_sampah.php"><button type="button" name="button" class="inputbtn">Setor Sampah</button></a>
                        <a href="konversi.php"><button type="button" name="button" class="inputbtn">Konversi Saldo</button></a>
                        <a href="tarik.php"><button type="button" name="button" class="inputbtn">Tarik Saldo</button></a>
                        <a href="jual_sampah.php"><button type="button" name="button" class="inputbtn">Jual Sampah</button></a>
                    </div>
                </div>

                <!-- Date and Time Section -->
                <form method="POST" action="">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <?php
                            // Set zona waktu ke WIB (UTC+7)
                            date_default_timezone_set('Asia/Jakarta');
                            $current_time = date('H:i:s');
                            ?>
                            <input type="time" name="waktu" class="form-control" value="<?php echo $current_time; ?>">
                        </div>
                    </div>

                    <!-- Transaction Table -->
                    <table class="table" id="transaksiTable">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>#</th>
                                <th>Kategori Sampah</th>
                                <th>Jenis Sampah</th>
                                <th>Jumlah (Kg)</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button class="btn btn-danger" onclick="removeRow(this)">&times;</button></td>
                                <td>1</td>
                                <td>
                                    <select name="kategori_id[]" id="kategori_id_1" class="form-control" onchange="updateJenis(1)">
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
                                    <select name="id_sampah[]" id="jenis_id_1" class="form-control" onchange="updateHarga(1)">
                                        <option value="">-- jenis sampah --</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="jumlah[]" id="jumlah_1" class="form-control" placeholder="Jumlah" oninput="updateHarga(1)">
                                </td>
                                <td>
                                    <input type="text" name="harga[]" id="harga_1" class="form-control" readonly>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">Total Harga</td>
                                <td><span id="totalHarga">Rp. 0</span></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="button" class="btn btn-primary mb-4" onclick="addRow()">Tambah Baris</button>
                    <button type="submit" name="submit" class="btn btn-success mb-4">Simpan</button>
                </form>
            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>
</body>

</html>
