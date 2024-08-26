<?php
include 'header.php';
include 'fungsi.php';

// Cek apakah form pencarian telah disubmit
if (isset($_GET['search_nik'])) {
    $search_nik = $_GET['search_nik'];
    // Query untuk mencari nasabah berdasarkan NIK
    $query_all = query("SELECT * FROM user WHERE role = 'nasabah' AND nik LIKE '%$search_nik%' ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");
} else {
    // Query default untuk menampilkan semua nasabah
    $query_all = query("SELECT * FROM user WHERE role = 'nasabah' ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Nasabah</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
                        <h2>Nasabah</h2>
                    </div>
                </div>

                <!-- Ini Tabel -->
                <div class="tabular--wrapper">
                    <div class="search--wrapper">
                        <form method="GET" action="">
                            <input type="text" name="search_nik" placeholder="Cari NIK nasabah..." value="<?= isset($search_nik) ? $search_nik : '' ?>">
                            <button type="submit" class="inputbtn">Cari</button>
                        </form>
                    </div>
                    <div class="row align-items-start">
                        <div class="user--info">
                            <h3 class="main--title">Data Project</h3>
                            <a href="register.php"><button type="button" name="button" class="inputbtn .border-right">Tambah</button></a>
                        </div>
                    </div>

                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "<h4>" . $_SESSION['message'] . "</h4>";
                        unset($_SESSION['message']);
                    }
                    ?>

                    <div class="table-container">
                        <?php if (empty($query_all)) : ?>
                            <div class="alert alert-warning">
                                <strong>Nasabah tidak ditemukan!</strong> Silakan coba NIK yang lain.
                            </div>
                        <?php else : ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>NIK</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($query_all as $row): ?>
                                        <tr>
                                            <td><?= $row["id"]; ?></td>
                                            <td><?= $row["username"]; ?></td>
                                            <td><?= $row["nama"]; ?></td>
                                            <td><?= $row["email"]; ?></td>
                                            <td><?= $row["notelp"]; ?></td>
                                            <td><?= $row["nik"]; ?></td>
                                            <td><?= $row["alamat"]; ?></td>
                                            <td><?= $row["tgl_lahir"]; ?></td>
                                            <td><?= $row["kelamin"]; ?></td>
                                            <td>
                                                <li class="liaksi">
                                                    <button type="submit" name="submit"><a href="edit_nasabah.php?id=<?= $row["id"]; ?>" class="inputbtn6">Ubah</a></button>
                                                </li>
                                                <li class="liaksi">
                                                    <button type="submit" name="submit"><a href="hapus_nasabah.php?id=<?= $row["id"]; ?>" class="inputbtn7">Hapus</a></button>
                                                </li>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Batas Akhir Tabel -->
            </div>
            <!-- Batas Akhir card-container -->
        </div>
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>