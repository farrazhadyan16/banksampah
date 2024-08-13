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
    $kategori_id = $_POST['kategori_id'];
    $jenis_id = $_POST['jenis_id'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];

    // Simpan transaksi ke database
    $transaksi_query = "INSERT INTO jual_sampah (user_id, tanggal, waktu, kategori_id, jenis_id, jumlah, harga) 
                        VALUES ('$user_id', '$tanggal', '$waktu', '$kategori_id', '$jenis_id', '$jumlah', '$harga')";
    if ($conn->query($transaksi_query) === TRUE) {
        $message = "Transaksi berhasil disimpan.";
    } else {
        $message = "Error: " . $transaksi_query . "<br>" . $conn->error;
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
    // Simpan data jenis sampah di variabel JavaScript
    var jenisSampah = <?php echo json_encode($jenis_sampah); ?>;

    function updateHarga() {
        var jenisId = document.getElementById('jenis_id').value;
        var jumlah = document.getElementById('jumlah').value;
        var harga = jenisSampah[jenisId] ? jenisSampah[jenisId].harga : 0;
        var totalHarga = jumlah * harga;

        // Update harga di input harga
        document.getElementById('harga').value = 'Rp. ' + totalHarga.toLocaleString('id-ID');
    }

    function updateJenis() {
        var kategoriSelect = document.getElementById('kategori_id');
        var jenisSelect = document.getElementById('jenis_id');
        var selectedKategori = kategoriSelect.value;

        // Filter jenis sampah berdasarkan kategori yang dipilih
        jenisSelect.innerHTML = '<option value="">-- jenis sampah --</option>'; // Reset pilihan jenis
        for (var id in jenisSampah) {
            if (jenisSampah[id].id_kategori == selectedKategori) {
                var option = document.createElement('option');
                option.value = id;
                option.text = jenisSampah[id].jenis;
                jenisSelect.add(option);
            }
        }
        // Reset pilihan jenis jika kategori diubah
        jenisSelect.value = "";
        updateHarga();
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
                            <input type="text" name="search_value" class="form-control" placeholder="Search"
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
                            <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="waktu" class="form-control" value="<?php echo date('H:i'); ?>">
                        </div>
                    </div>

                    <!-- Table Section -->
                    <table class="table table-bordered">
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
                                <td><button class="btn btn-danger">&times;</button></td>
                                <td>1</td>
                                <td>
                                    <select name="kategori_id" id="kategori_id" class="form-control"
                                        onchange="updateJenis()">
                                        <option value="">-- kategori sampah --</option>
                                        <?php
                                        if ($kategori_result->num_rows > 0) {
                                            while ($row = $kategori_result->fetch_assoc()) {
                                                echo "<option value='".$row['id']."'>".$row['name']."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="jenis_id" id="jenis_id" class="form-control" onchange="updateHarga()">
                                        <option value="">-- jenis sampah --</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                                        placeholder="Jumlah" oninput="updateHarga()">
                                </td>
                                <td>
                                    <input type="text" name="harga" id="harga" class="form-control" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" name="submit" class="btn btn-success w-100">Simpan Transaksi</button>
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