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

// Fetch data kategori dan jenis
$kategori_query = "SELECT id, namaFROM kategori_sampah";
$kategori_result = $conn->query($kategori_query);

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

// Proses penyimpanan data transaksi ke tabel setor_sampah
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $items = json_decode($_POST['items'], true); // Array yang berisi data jenis, jumlah_kg, jumlah_rp

    // Generate id_transaksi baru
    $transaksi_query = "SELECT MAX(id_transaksi) as max_id FROM setor_sampah";
    $transaksi_result = $conn->query($transaksi_query);
    $transaksi_row = $transaksi_result->fetch_assoc();
    $id_transaksi = $transaksi_row['max_id'] + 1;

    foreach ($items as $index => $item) {
        $id_sampah = $item['id_sampah'];
        $jumlah_kg = $item['jumlah_kg'];
        $jumlah_rp = $item['jumlah_rp'];

        // Generate nomor urut untuk setiap jenis sampah yang berbeda
        $no_query = "SELECT MAX(no) as max_no FROM setor_sampah WHERE id_sampah = $id_sampah";
        $no_result = $conn->query($no_query);
        $no_row = $no_result->fetch_assoc();
        $no = $no_row['max_no'] + 1;

        // Insert data ke tabel setor_sampah
        $insert_query = "INSERT INTO setor_sampah (id_transaksi, no, id_sampah, tanggal, waktu, jumlah_kg, jumlah_rp, user_id) 
                         VALUES ('$id_transaksi', '$no', '$id_sampah', '$tanggal', '$waktu', '$jumlah_kg', '$jumlah_rp', '$user_id')";
        if ($conn->query($insert_query) === TRUE) {
            $message = "Transaksi berhasil disimpan!";
        } else {
            $message = "Error: " . $conn->error;
        }
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
    function addRow() {
        const table = document.getElementById('transaksiTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();

        const cell1 = newRow.insertCell(0);
        const cell2 = newRow.insertCell(1);
        const cell3 = newRow.insertCell(2);
        const cell4 = newRow.insertCell(3);
        const cell5 = newRow.insertCell(4);
        const cell6 = newRow.insertCell(5);

        cell1.innerHTML = table.rows.length;
        cell2.innerHTML = `<select name="id_sampah" class="form-control">
                                <?php foreach ($jenis_sampah as $id => $jenis) { ?>
                                    <option value="<?php echo $id; ?>"><?php echo $jenis['jenis']; ?></option>
                                <?php } ?>
                               </select>`;
        cell3.innerHTML =
            '<input type="number" name="jumlah_kg" class="form-control" step="0.01" min="0" onchange="updateTotalHarga()">';
        cell4.innerHTML = '<input type="text" name="jumlah_rp" class="form-control" readonly>';
        cell5.innerHTML = '<button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button>';
    }

    function removeRow(button) {
        const row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        updateTotalHarga();
    }

    function updateTotalHarga() {
        let total = 0;
        const rows = document.querySelectorAll('#transaksiTable tbody tr');

        rows.forEach(row => {
            const harga = parseFloat(row.querySelector('select[name="id_sampah"]').selectedOptions[0].dataset
                .harga);
            const jumlah_kg = parseFloat(row.querySelector('input[name="jumlah_kg"]').value);

            if (!isNaN(harga) && !isNaN(jumlah_kg)) {
                const jumlah_rp = harga * jumlah_kg;
                row.querySelector('input[name="jumlah_rp"]').value = jumlah_rp.toFixed(2);
                total += jumlah_rp;
            }
        });

        document.getElementById('totalHarga').textContent = 'Rp. ' + total.toFixed(2);
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        let items = [];
        const rows = document.querySelectorAll('#transaksiTable tbody tr');

        rows.forEach(row => {
            const id_sampah = row.querySelector('select[name="id_sampah"]').value;
            const jumlah_kg = row.querySelector('input[name="jumlah_kg"]').value;
            const jumlah_rp = row.querySelector('input[name="jumlah_rp"]').value;

            items.push({
                id_sampah: id_sampah,
                jumlah_kg: jumlah_kg,
                jumlah_rp: jumlah_rp
            });
        });

        const formData = new FormData(this);
        formData.append('items', JSON.stringify(items));

        fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
            .then(data => {
                document.querySelector('.tabular--wrapper').innerHTML = data;
            });
    });

    function validateSearchForm() {
        const searchValue = document.getElementById('search_value').value;
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
                                <button type="submit" name="search" class="btn btn-primary">CHECK</button>
                            </div>
                        </div>
                    </form>

                    <!-- User Information Display -->
                    <?php if (isset($user_data)) { ?>
                    <div class="user-info">
                        <h5>Data Nasabah</h5>
                        <p>Nama: <?php echo $user_data['name']; ?></p>
                        <p>NIK: <?php echo $user_data['nik']; ?></p>
                        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                    </div>
                    <?php } ?>

                    <!-- Transaction Form -->
                    <form method="POST" action="">
                        <input type="hidden" name="user_id"
                            value="<?php echo isset($user_data['id']) ? $user_data['id'] : ''; ?>">
                        <div class="form-group">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="waktu">Waktu:</label>
                            <input type="time" name="waktu" class="form-control" required>
                        </div>

                        <table class="table" id="transaksiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Sampah</th>
                                    <th>Jumlah (kg)</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select name="id_sampah" class="form-control">
                                            <?php foreach ($jenis_sampah as $id => $jenis) { ?>
                                            <option value="<?php echo $id; ?>"
                                                data-harga="<?php echo $jenis['harga']; ?>">
                                                <?php echo $jenis['jenis']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="jumlah_kg" class="form-control" step="0.01" min="0"
                                            onchange="updateTotalHarga()"></td>
                                    <td><input type="text" name="jumlah_rp" class="form-control" readonly></td>
                                    <td><button type="button" class="btn btn-danger"
                                            onclick="removeRow(this)">Hapus</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-secondary" onclick="addRow()">Tambah Baris</button>

                        <div class="form-group mt-4">
                            <label for="totalHarga">Total Harga:</label>
                            <p id="totalHarga">Rp. 0.00</p>
                        </div>

                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
    </div>
</body>

</html>