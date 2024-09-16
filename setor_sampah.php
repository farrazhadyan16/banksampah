<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

$current_gold_price_buy = getCurrentGoldPricebuy(); // For converting money to gold
$current_gold_price_sell = getCurrentGoldPricesell(); // For converting gold to money

// Jika tombol CHECK ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    if (empty($search_value)) {
        $message = "NIK tidak boleh kosong.";
    } else {
        $user_query = "SELECT user.*, dompet.uang, dompet.emas FROM user 
                    LEFT JOIN dompet ON user.id = dompet.id_user 
                    WHERE user.nik LIKE '%$search_value%' AND user.role = 'Nasabah'";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();

            // Ambil harga emas terkini
            $current_gold_price_sell = getCurrentGoldPricesell();

            // Hitung jumlah emas yang setara dengan saldo uang
            $gold_equivalent = $user_data['emas'] * $current_gold_price_sell;
        } else {
            $message = "User dengan role 'Nasabah' tidak ditemukan.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $id_user = $_POST['user_id'] ?? '';
    $jenis_transaksi = $_POST['jenis_transaksi'] ?? 'setor_sampah';
    $date = $_POST['tanggal'] . ' ' . $_POST['waktu'];
    $id_kategoris = $_POST['id_kategori'] ?? [];
    $id_jeniss = $_POST['id_jenis'] ?? [];
    $jumlahs = $_POST['jumlah'] ?? [];
    $hargas = $_POST['harga'] ?? [];

    // Generate new ID for transaksi
    $id_trans_query = "SELECT no FROM transaksi ORDER BY no DESC LIMIT 1";
    $result = $conn->query($id_trans_query);
    $last_id = ($result->num_rows > 0) ? $result->fetch_assoc()['no'] : 0;
    $new_id = $last_id + 1;

    // Create new transaksi ID
    $id = 'TRANS' . date('Y') . str_pad($new_id, 6, '0', STR_PAD_LEFT);

    // Set the default timezone to Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');

    // Calculate total amount
    $total_uang = 0;
    $total_emas = 0; // Variable to store total gold converted

    // Insert into transaksi table
    $date = date('Y-m-d'); // Get the current date and time
    $time = date('H:i:s'); // Get the current date and time
    $transaksi_query = "INSERT INTO transaksi (no, id, id_user, jenis_transaksi, date, time) VALUES (NULL, '$id', '$id_user', '$jenis_transaksi', '$date', '$time')";

    if ($conn->query($transaksi_query) === TRUE) {
        // Use the custom $id, not $conn->insert_id, as the id_transaksi
        $id_transaksi = $id;

        // Loop to insert each row into the setor_sampah table
        for ($i = 0; $i < count($id_kategoris); $i++) {
            $id_kategori = $id_kategoris[$i];
            $id_jenis = $id_jeniss[$i];
            $jumlah = $jumlahs[$i];
            $harga = str_replace(['Rp. ', '.', ','], '', $hargas[$i]);
            $total_uang += $harga;

            // Convert money to gold based on current gold price
            $emas_converted = $harga / $current_gold_price_buy;
            $total_emas += $emas_converted;

            // Insert into setor_sampah table
            $setor_sampah_query = "INSERT INTO setor_sampah (no, id_transaksi, id_sampah, jumlah_kg, jumlah_rp, jumlah_emas) 
                VALUES (NULL, '$id_transaksi', '$id_jenis', '$jumlah', '$harga','$emas_converted')";
            // var_dump($setor_sampah_query);
            // die;
            if ($conn->query($setor_sampah_query) === FALSE) {
                $message = "Error: " . $conn->error;
            }

            // Update the jumlah in the sampah table
            $update_sampah_query = "UPDATE sampah SET jumlah = jumlah + $jumlah WHERE id = '$id_jenis'";
            if ($conn->query($update_sampah_query) === FALSE) {
                $message = "Error: " . $conn->error;
            }
        }

        // Update or insert into dompet table
        $dompet_query = "SELECT * FROM dompet WHERE id_user = ?";
        if ($stmt = $conn->prepare($dompet_query)) {
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing record, including both uang and emas columns
                $dompet_update_query = "UPDATE dompet SET  emas = emas + ? WHERE id_user = ?";
                if ($update_stmt = $conn->prepare($dompet_update_query)) {
                    $update_stmt->bind_param("di", $total_emas, $id_user);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            } else {
                // Insert new record if no existing record found
                $dompet_insert_query = "INSERT INTO dompet (id_user, emas) VALUES (, ?, ?)";
                if ($insert_stmt = $conn->prepare($dompet_insert_query)) {
                    $insert_stmt->bind_param("id", $id_user,  $total_emas);
                    $insert_stmt->execute();
                    $insert_stmt->close();
                }
            }

            $stmt->close();
        }



        // Uncomment this line when ready to redirect
        header("Location: nota.php?id_transaksi=$id_transaksi");
        exit;
    } else {
        $message = "Error: " . $conn->error;
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
            var idjenis = document.getElementById('id_jenis_' + index).value;
            var jumlah = document.getElementById('jumlah_' + index).value;
            var harga = jenisSampah[idjenis] ? jenisSampah[idjenis].harga : 0;
            var totalHarga = jumlah * harga;

            // Assuming you have `current_gold_price_buy` as a JavaScript variable, otherwise pass it to JS
            var totalEmas = totalHarga / <?php echo $current_gold_price_buy; ?>; // Convert total price to gold

            document.getElementById('harga_' + index).value = 'Rp. ' + totalHarga.toLocaleString('id-ID');
            document.getElementById('totalEmas').innerText = totalEmas.toFixed(4) + ' g'; // Show total gold in grams
            updateTotalHarga(); // Call function to update total money
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
            var kategoriSelect = document.getElementById('id_kategori_' + index);
            var jenisSelect = document.getElementById('id_jenis_' + index);
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
            var tbody = document.querySelector('#transaksiTable tbody');
            var rowCount = tbody.rows.length + 1; // Adjust row count to account for existing rows in tbody
            var row = tbody.insertRow(); // Add row to tbody instead of the table directly

            row.innerHTML = `
                <td><button class="btn btn-danger" onclick="removeRow(this)">&times;</button></td>
                <td>${rowCount}</td>
                <td>
                    <select name="id_kategori[]" id="id_kategori_${rowCount}" class="form-control" onchange="updateJenis(${rowCount})">
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
                    <select name="id_jenis[]" id="id_jenis_${rowCount}" class="form-control" onchange="updateHarga(${rowCount})">
                        <option value="">-- jenis sampah --</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlah[]" id="jumlah_${rowCount}" class="form-control" placeholder="Jumlah" oninput="updateHarga(${rowCount})" step="0.001">
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

        function getSuggestions() {
            var search_value = document.getElementById("search_value").value;
            if (search_value.length >= 3) { // Minimal input untuk memulai pencarian
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "get_suggestions.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("suggestions").innerHTML = xhr.responseText;
                        document.getElementById("suggestions").style.display = 'block';
                    }
                };
                xhr.send("query=" + search_value);
            } else {
                document.getElementById("suggestions").style.display = 'none';
            }
        }

        function selectSuggestion(nik) {
            document.getElementById("search_value").value = nik;
            document.getElementById("suggestions").style.display = 'none';
        }

        function validateSearchForm() {
            var search_value = document.getElementById("search_value").value;
            if (search_value === "") {
                alert("NIK tidak boleh kosong.");
                return false;
            }
            return true;
        }
    </script>
</head>
<style>
    /* Styling for suggestion box */
    #suggestions {
        position: absolute;
        z-index: 1000;
        background-color: white;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }

    /* Styling for each suggestion item */
    #suggestions div {
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    /* Hover effect */
    #suggestions div:hover {
        background-color: #f0f0f0;
    }

    /* Ensure it doesn't overlap with sidebar */
    .sidebar+#suggestions {
        margin-left: 0;
        position: relative;
    }

    /* Mobile-friendly adjustment */
    @media (max-width: 768px) {
        #suggestions {
            width: 100%;
            left: 0;
        }
    }
</style>

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
                        <!-- <a href="Konversi.php"><button type="button" name="button" class="inputbtn">Konversi
                                Saldo</button></a> -->
                        <a href="tarik.php"><button type="button" name="button" class="inputbtn">Tarik
                                Saldo</button></a>
                        <a href="jual_sampah.php"><button type="button" name="button" class="inputbtn">Jual
                                Sampah</button></a>
                    </div>
                </div>

                <!-- Start of Form Section -->
                <div class="tabular--wrapper">
                    <!-- Search Section -->
                    <form method="POST" action="" onsubmit="return validateSearchForm()">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search_value" id="search_value" class="form-control"
                                    placeholder="Search by NIK or Name" maxlength="16" oninput="getSuggestions()" required>
                                <div id="suggestions" style="display: none; position: absolute; z-index: 1000; background: #fff; border: 1px solid #ccc;">
                                    <!-- Suggestions will be displayed here -->
                                </div>
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
                                <p><strong>ID</strong> : <?php echo $user_data['id']; ?></p>
                                <p><strong>NIK</strong> : <?php echo $user_data['nik']; ?></p>
                                <p><strong>Email</strong> : <?php echo $user_data['email']; ?></p>
                            </div>
                            <div class="col-md-5">
                                <p><strong>Username</strong> : <?php echo $user_data['username']; ?></p>

                                <p><strong>Nama Lengkap</strong> : <?php echo $user_data['nama']; ?></p>
                                <p><strong>Saldo</strong> :
                                    <?php echo number_format($user_data['emas'], 4, '.', '.'); ?> g =
                                    Rp. <?php echo round($gold_equivalent, 2); ?>
                                </p>
                                <!-- <p><strong>Saldo Emas</strong> :
            <?php echo number_format($user_data['emas'], 4, ',', '.'); ?> g</p> -->
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="text-danger"><?php echo $message; ?></p>
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
                                <input type="date" name="tanggal" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <?php
                                // Set zona waktu ke WIB (UTC+7)
                                date_default_timezone_set('Asia/Jakarta');
                                $current_time = date('H:i');
                                ?>
                                <input type="time" name="waktu" class="form-control"
                                    value="<?php echo $current_time; ?>" disabled>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input type="text" name="harga_emas_beli" class="form-control"
                                        placeholder="Harga Emas Beli" value="<?php echo $current_gold_price_buy; ?>"
                                        readonly>
                                </div>
                                <small class="form-text text-muted">Harga beli emas (saat ini) per gram</small>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input type="text" name="harga_emas_jual" class="form-control"
                                        placeholder="Harga Emas Jual" value="<?php echo $current_gold_price_sell; ?>"
                                        readonly>
                                </div>
                                <small class="form-text text-muted">Harga jual emas (saat ini) per gram</small>
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
                                        <select name="id_kategori[]" id="id_kategori_1" class="form-control"
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
                                        <select name="id_jenis[]" id="id_jenis_1" class="form-control"
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
                                <tr>
                                    <th colspan="5" class="text-right">Total Emas:</th>
                                    <th id="totalEmas">0gr</th>
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