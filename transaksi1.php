<?php
// Include file fungsi.php untuk koneksi ke database
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Jika tombol CHECK ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    $user_query = "SELECT * FROM user WHERE (username LIKE '%$search_value%' OR nama LIKE '%$search_value%') AND role = 'Nasabah'";
    $user_result = $conn->query($user_query);

    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
    } else {
        $message = "User dengan role 'Nasabah' tidak ditemukan.";
    }
}

// Jika tombol SUBMIT ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $kategori_ids = $_POST['kategori_id'];
    $jenis_ids = $_POST['jenis_id'];
    $jumlahs = $_POST['jumlah'];
    $hargas = $_POST['harga'];

    for ($i = 0; $i < count($kategori_ids); $i++) {
        $kategori_id = $kategori_ids[$i];
        $jenis_id = $jenis_ids[$i];
        $jumlah = $jumlahs[$i];
        $harga = str_replace(['Rp.', ','], '', $hargas[$i]);

        if ($kategori_id && $jenis_id && $jumlah && $harga) {
            $transaksi_query = "INSERT INTO jual_sampah (user_id, tanggal, waktu, kategori_id, jenis_id, jumlah, harga) 
                                VALUES ('$user_id', '$tanggal', '$waktu', '$kategori_id', '$jenis_id', '$jumlah', '$harga')";
            if ($conn->query($transaksi_query) === TRUE) {
                $message = "Transaksi berhasil disimpan.";
            } else {
                $message = "Error: " . $transaksi_query . "<br>" . $conn->error;
            }
        }
    }
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
            var rowCount = table.rows.length;
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
                    <select name="jenis_id[]" id="jenis_id_${rowCount}" class="form-control" onchange="updateHarga(${rowCount})">
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
        }
    </script>
</head>

<body>
    <!-- Ini Sidebar -->
    <?php include("sidebar.php") ?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="main--content--monitoring">
            <div class="header--wrapper">
                <div class="header--title">
                    <span>Halaman</span>
                    <h2>Transaksi</h2>
                </div>
            </div>

            <!-- Start of Form Section -->
            <div class="tabular--wrapper">
                <!-- Search Section -->
                <form method="POST" action="">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search_value" class="form-control" placeholder="Search" value="<?php echo isset($search_value) ? $search_value : ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="search" class="btn btn-dark w-100">CHECK</button>
                        </div>
                    </div>
                </form>

                <!-- User Information Section -->
                <?php if (isset($user_data)) { ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>id</strong> : <?php echo $user_data['id']; ?></p>
                            <p><strong>email</strong> : <?php echo $user_data['email']; ?></p>
                            <p><strong>username</strong> : <?php echo $user_data['username']; ?></p>
                            <p><strong>nama lengkap</strong> : <?php echo $user_data['nama']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Saldo Uang</strong> : Rp. 0.00</p>
                            <p><strong>Saldo Emas</strong> : 0.0000 g</p>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p><?php echo $message; ?></p>
                        </div>
                    </div>
                <?php } ?>

                <!-- Date and Time Section -->
                <form method="POST" action="">
                    <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                    <?php } ?>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" <?php echo isset($user_data) ? '' : 'disabled'; ?>>
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="waktu" class="form-control" value="<?php echo date('H:i'); ?>" <?php echo isset($user_data) ? '' : 'disabled'; ?>>
                        </div>
                    </div>

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
                        <tbody>
                            <tr>
                                <td><button class="btn btn-danger" onclick="removeRow(this)">&times;</button></td>
                                <td>1</td>
                                <td>
                                    <select name="kategori_id[]" id="kategori_id_1" class="form-control" onchange="updateJenis(1)" <?php echo isset($user_data) ? '' : 'disabled'; ?>>
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
                                    <select name="jenis_id[]" id="jenis_id_1" class="form-control" onchange="updateHarga(1)" <?php echo isset($user_data) ? '' : 'disabled'; ?>>
                                        <option value="">-- jenis sampah --</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="jumlah[]" id="jumlah_1" class="form-control" placeholder="Jumlah" oninput="updateHarga(1)" <?php echo isset($user_data) ? '' : 'disabled'; ?>>
                                </td>
                                <td>
                                    <input type="text" name="harga[]" id="harga_1" class="form-control" readonly <?php echo isset($user_data) ? '' : 'disabled'; ?>>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary w-100" onclick="addRow()" <?php echo isset($user_data) ? '' : 'disabled'; ?>>Tambah Baris</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="submit" class="btn btn-success w-100" <?php echo isset($user_data) ? '' : 'disabled'; ?>>Simpan Transaksi</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End of Form Section -->

        </div>
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>