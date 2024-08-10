<?php
include 'header.php';
include 'fungsi.php';

$query_all = query("SELECT * FROM user WHERE role in ('admin','superadmin') ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Admin</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
                    <h2>Admin</h2>
                </div>
                <div class="user--info">
                    <a href="inputdata.php"><button type="button" name="button" class="inputbtn">Input
                            Project</button></a>
                    <a href="inputdesign.php"><button type="button" name="button" class="inputbtn">Input
                            Design</button></a>
                    <a href="inputnesting.php"><button type="button" name="button" class="inputbtn">Input
                            Nesting</button></a>
                    <a href="inputprogram.php"><button type="button" name="button" class="inputbtn">Input
                            Program</button></a>
                    <a href="inputchecker.php"><button type="button" name="button" class="inputbtn">Input
                            Checker</button></a>
                    <a href="exportmonitoring.php"><button type="button" name="button"
                            class="inputbtn">Export</button></a>
                    <img src="./img/logoPM_high.png" alt="logo">
                </div>
            </div>

            <!-- Ini Tabel -->
            <div class="tabular--wrapper">
                <div class="row align-items-start">
                    <div class="user--info">
                        <h3 class="main--title">Data Project</h3>
                        <a href="register.php"><button type="button" name="button"
                                class="inputbtn .border-right">Tambah</button></a>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<h4>" . $_SESSION['message'] . "</h4>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="table-container">
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
                                <td>
                                    <?= $row["id"]; ?>
                                </td>
                                <td>
                                    <?= $row["username"]; ?>
                                </td>
                                <td>
                                    <?= $row["nama"]; ?>
                                </td>
                                <td>
                                    <?= $row["email"]; ?>
                                </td>
                                <td>
                                    <?= $row["notelp"]; ?>
                                </td>
                                <td>
                                    Rp. <?= $row["nik"]; ?>
                                </td>
                                <td>
                                    <?= $row["alamat"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_lahir"]; ?>
                                </td>
                                <td>
                                    <?= $row["kelamin"]; ?>
                                </td>


                                <td>
                                    <li class="liaksi">
                                        <button type="submit" name="submit"><a
                                                href="edit-nasabah.php?id=<?= $row["id"]; ?>"
                                                class="inputbtn6">Ubah</a></button>
                                    </li>
                                    <li class="liaksi">
                                        <button type="submit" name="submit"><a
                                                href="hapus-nasabah.php?id=<?= $row["id"]; ?>"
                                                class="inputbtn7">Hapus</a></button>
                                    </li>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- Batas Akhir Tabel -->
            <!-- <form method="GET" action="tambah_pengguna.php">
            <button type="submit" name="submit" class="inputbtn1">Tambah Data</button>
        </form> -->
        </div>
        <!-- Batas Akhir card-container -->
    </div>
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>