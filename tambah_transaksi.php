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
    $id_trans = $_POST['id_trans'];
    $user_id = $_POST['user_id'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $kategori_ids = $_POST['kategori_id'];
    $jenis_ids = $_POST['jenis_id'];
    $jumlahs = $_POST['jumlah'];
    $hargas = $_POST['harga'];

    // Mendapatkan nomor urut terakhir
    $id_trans_query = "SELECT nomor FROM transaksi_tb ORDER BY nomor DESC LIMIT 1";
    $result = $conn->query($id_trans_query);
    $last_id = ($result->num_rows > 0) ? $result->fetch_assoc()['nomor'] : 0;
    $new_id = $last_id + 1;

    // Membuat id_trans baru dengan format tertentu
    $id_trans = 'TRANS' . date('Y') . str_pad($new_id, 6, '0', STR_PAD_LEFT);

    // Loop untuk memasukkan setiap baris data
    for ($i = 0; $i < count($kategori_ids); $i++) {
        $kategori_id = $kategori_ids[$i];
        $jenis_id = $jenis_ids[$i];
        $jumlah = $jumlahs[$i];

        // Menghapus awalan "Rp." dan karakter lainnya dari harga
        $harga = str_replace(['Rp. ', '.', ','], '', $hargas[$i]);

        // Menyiapkan query SQL untuk memasukkan data
        $transaksi_query = "INSERT INTO transaksi_tb (nomor, id_trans, user_id, tanggal, waktu, kategori_id, jenis_id, jumlah, harga) 
                            VALUES (NULL, '$id_trans', ?, ?, ?, ?, ?, ?, ?)";

        // Menyiapkan statement
        if ($stmt = $conn->prepare($transaksi_query)) {
            // Mengikat parameter
            $stmt->bind_param("isssiii", $user_id, $tanggal, $waktu, $kategori_id, $jenis_id, $jumlah, $harga);

            // Menjalankan statement
            if ($stmt->execute()) {
                $message = "Transaksi berhasil disimpan dengan ID: $id_trans";
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Menutup statement
            $stmt->close();
        } else {
            $message = "Error preparing statement: " . $conn->error;
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
                        <option value="">-- kategorikk sampah --</option>
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
            updateTotalHarga();
        }

        function validateSearchForm() {
            var searchValue = document.getElementById('search_value').value;
            if (searchValue.trim() === '') {
                alert('NIK tidak boleh kosong.');
                return false; // Mencegah form dikirim
            } else if (searchValue.length !== 16 || isNaN(searchValue)) {
                alert('NIK harus berisi 16 digit angka.');
                return false; // Mencegah form dikirim
            }
            return true; // Memungkinkan form dikirim
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
                        <h2>Transaksi</h2>
                    </div>
                </div>

                <!-- Start of Form Section -->
                <div class="tabular--wrapper">
                    <!-- Search Section -->
                    <form method="POST" action="" onsubmit="return validateSearchForm()">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search_value" id="search_value" class="form-control"
                                    placeholder="Search by NIK" maxlength="16" oninput="validateNIK(this)"
                                    value="<?php echo isset($search_value) ? $search_value : ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="search" class="btn btn-dark w-100">CHECK</button>
                            </div>
                        </div>
                    </form>

                    <!-- User Information Section -->
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
                    <?php } else { ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="text-danger"><?php echo $message; ?></p>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Transaction Form Section
                    <form method="POST" action="">
                        <input type="hidden" name="user_id"
                            value="<?php echo isset($user_data) ? $user_data['id'] : ''; ?>">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="date" name="tanggal" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4">
                                <input type="time" name="waktu" class="form-control" value="<?php echo date('H:i'); ?>">
                            </div>
                        </div> -->
                    <?php
                    if (isset($_POST['submit'])) {
                        // var_dump($_POST);
                        // include 'fungsi.php';
                        $user_id = $_POST['user_id'];
                        $tanggal = $_POST['tanggal'];
                        $waktu = $_POST['waktu'];
                        $kategori_ids = $_POST['kategori_id'];
                        $jenis_ids = $_POST['jenis_id'];
                        $jumlahs = $_POST['jumlah'];
                        $hargas = $_POST['harga'];

                        // Loop untuk memasukkan setiap baris data
                        for ($i = 0; $i < count($kategori_ids); $i++) {
                            $kategori_id = $kategori_ids[$i];
                            $jenis_id = $jenis_ids[$i];
                            $jumlah = $jumlahs[$i];

                            // Menghapus awalan "Rp." dan karakter lainnya dari harga
                            $harga = str_replace(['Rp. ', '.', ','], '', $hargas[$i]);

                            // Menyiapkan query SQL untuk memasukkan data
                            $transaksi_query = "INSERT INTO transaksi_tb (user_id, tanggal, waktu, kategori_id, jenis_id, jumlah, harga) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?)";

                            // Menyiapkan statement
                            if ($stmt = $conn->prepare($transaksi_query)) {
                                // Mengikat parameter
                                $stmt->bind_param("isssiii", $user_id, $tanggal, $waktu, $kategori_id, $jenis_id, $jumlah, $harga);

                                // Menjalankan statement
                                if ($stmt->execute()) {
                                    echo "Transaksi berhasil disimpan.<br>";
                                } else {
                                    echo "Error: " . $stmt->error . "<br>";
                                }
                            } else {
                                echo "Error preparing statement: " . $conn->error . "<br>";
                            }

                            // Menutup statement
                            // $stmt->close();
                        }
                    }

                    // Menutup koneksi
                    $conn->close();

                    ?>

                    <!-- Date and Time Section -->
                    <form method="POST" action="">
                        <?php if (isset($user_data)) { ?>
                            <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="date" name="tanggal" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4">
                                <?php
                                // Set zona waktu ke WIB (UTC+7)
                                date_default_timezone_set('Asia/Jakarta');
                                $current_time = date('H:i');
                                ?>
                                <input type="time" name="waktu" class="form-control"
                                    value="<?php echo $current_time; ?>">
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
                                <!-- <tr>
                                    <td><button class="btn btn-danger" onclick="removeRow(this)">&times;</button></td>
                                    <td>1</td>
                                    <td>
                                        <select name="kategori_id[]" id="kategori_id_1" class="form-control"
                                            onchange="updateJenis(1)">
                                            <option value="">-- kategoriaaa sampah --</option> -->

                                <?php
                                if ($kategori_result->num_rows > 0) {
                                    while ($row = $kategori_result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>


                                <!-- </select>
                                    </td>
                                    <td>
                                        <select name="jenis_id[]" id="jenis_id_1" class="form-control"
                                            onchange="updateHarga(1)">
                                            <option value="">-- jenis sampah --</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah[]" id="jumlah_1" class="form-control"
                                            placeholder="Jumlah" oninput="updateHarga(1)">
                                    </td>
                                    <td>
                                        <input type="text" name="harga[]" id="harga_1" class="form-control" readonly>
                                    </td>
                                </tr> -->
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
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>
</body>

</html>