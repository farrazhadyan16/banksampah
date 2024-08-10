<?php
include 'fungsi.php' ;
include 'header.php';
// $pengguna = query("SELECT * FROM pengguna");

$query_all = query("SELECT * from konsesi ORDER BY LENGTH(id_konsesi)");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Konsesi</title>
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
                    <h2>Konsesi</h2>
                </div>
                <div class="user--info">
                    <a href="inputkonsesi.php"><button type="button" name="button" class="inputbtn">Input
                            Data</button></a>
                    <!-- <?php if (in_array("admin",$_SESSION['admin_akses'])) { ?>
                    <a href="inputkonsesi.php"><button type="button" name="button" class="inputbtn">Input
                            Data</button></a>
                    <?php } ?> -->
                    <a href="exportkonsesi.php"><button type="button" name="button" class="inputbtn">Export</button></a>
                    <img src="./img/logoPM_high.png" alt="logo">
                </div>
            </div>

            <!-- Ini card-container -->
            <!-- <?php include("card-containe.php") ?> -->
            <!-- Batas Akhir card-container -->

            <!-- Ini Tabel -->
            <div class="tabular--wrapper">
                <h3 class="main--title">Data Konsesi</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JO</th>
                                <th>WO</th>
                                <th>NAMA PROJECT</th>
                                <th>NAMA PANEL</th>
                                <th>UNIT</th>
                                <th>JENIS</th>
                                <th>KONSESI</th>
                                <th>JML</th>
                                <th>NO LKPJ</th>
                                <th>STATUS</th>
                                <th>TGL MATRIAL DATANG</th>
                                <th>TGL PASANG</th>
                                <th>KETERANGAN</th>
                                <th>AKSI</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($query_all as $row): ?>

                            <tr>
                                <td>
                                    <?= $row["id_konsesi"]; ?>
                                </td>
                                <td>
                                    <?= $row["jo"]; ?>
                                </td>
                                <td>
                                    <?= $row["wo"]; ?>
                                </td>
                                <td>
                                    <?= $row["nama_project"]; ?>
                                </td>
                                <td>
                                    <?= $row["nama_panel"]; ?>
                                </td>
                                <td>
                                    <?= $row["unit"]; ?>
                                </td>
                                <td>
                                    <?= $row["jenis"]; ?>
                                </td>
                                <td>
                                    <?= $row["konsesi"]; ?>
                                </td>
                                <td>
                                    <?= $row["jumlah"]; ?>
                                </td>
                                <td>
                                    <?= $row["no_lkpj"]; ?>
                                </td>
                                <td>
                                    <?= $row["status"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_matrial_dtg"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_pasang"]; ?>
                                </td>
                                <td>
                                    <?= $row["keterangan"]; ?>
                                </td>

                                <td>
                                    <li class="liaksi">
                                        <button type="submit" name="submit"><a
                                                href="editkonsesi.php?id_konsesi=<?= $row["id_konsesi"]; ?>"
                                                class="inputbtn2">Ubah</a></button>
                                    </li>
                                    <li class="liaksi">
                                        <button type="submit" name="submit"><a
                                                href="hapuskonsesi.php?id_konsesi=<?= $row["id_konsesi"]; ?>"
                                                class="inputbtn3">Hapus</a></button>
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