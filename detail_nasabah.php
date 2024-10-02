<?php
include 'header.php';
include 'fungsi.php';
include 'koneksi.php'; // Gunakan koneksi mysqli yang sudah ada

// Dapatkan ID nasabah dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query untuk mendapatkan data nasabah berdasarkan ID
$query = "SELECT * FROM user WHERE id = ? AND role = 'nasabah' AND status = 0";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$row) {
    echo "<p>Nasabah tidak ditemukan!</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nasabah</title>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Detail Nasabah</h2>

        <!-- Tabel Detail Nasabah -->
        <table class="table table-bordered mt-3">
            <tr>
                <th>ID Nasabah</th>
                <td><?= htmlspecialchars($row['id']); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?= htmlspecialchars($row['username']); ?></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><?= htmlspecialchars($row['nama']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($row['email']); ?></td>
            </tr>
            <tr>
                <th>No Telepon</th>
                <td><?= htmlspecialchars($row['notelp']); ?></td>
            </tr>
            <tr>
                <th>NIK</th>
                <td><?= htmlspecialchars($row['nik']); ?></td>
            </tr>
            <tr>
                <th>No Rekening</th>
                <td><?= htmlspecialchars($row['no_rek']); ?></td>
            </tr>
            <tr>
                <th>NIP</th>
                <td><?= htmlspecialchars($row['nip']); ?></td>
            </tr>
            <tr>
                <th>Golongan</th>
                <td><?= htmlspecialchars($row['gol']); ?></td>
            </tr>
            <tr>
                <th>Bidang</th>
                <td><?= htmlspecialchars($row['bidang']); ?></td>
            </tr>
            <tr>
                <th>Frekuensi Menabung</th>
                <td><?= htmlspecialchars($row['frekuensi_menabung']); ?></td>
            </tr>
            <tr>
                <th>Terakhir Menabung</th>
                <td><?= htmlspecialchars($row['terakhir_menabung']); ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= htmlspecialchars($row['alamat']); ?></td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td><?= htmlspecialchars($row['tgl_lahir']); ?></td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td><?= htmlspecialchars($row['kelamin']); ?></td>
            </tr>
        </table>

        <a href="nasabah.php" class="btn btn-secondary">Kembali</a>
        <a href="edit_nasabah.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-warning">Ubah</a>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>