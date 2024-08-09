<?php
include 'header.php';
include 'fungsi.php';

$query_all = query("SELECT * from data_monitoring ORDER BY LENGTH(id),CAST(id AS UNSIGNED)");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Monitoring</title>
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
                    <h2>Sampah</h2>
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

            <!-- Ini card-container -->
            <!-- <?php include("card-containe.php") ?> -->
            <!-- Batas Akhir card-container -->

            <!-- Ini Tabel -->
            <div class="tabular--wrapper">
                <h3 class="main--title">Data Project</h3>
                <?php
                if(isset($_SESSION['message'])){
                    echo "<h4>".$_SESSION['message']."</h4>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID PROJECT</th>
                                <th>NO JO</th>
                                <th>TGL JO</th>
                                <th>PROYEK</th>
                                <th>KODE GBJ</th>
                                <th>NILAI HARGA</th>
                                <th>PANEL NAME</th>
                                <th>TYPE JENIS</th>
                                <th>TYPE FS/WM</th>
                                <th>QTY UNIT</th>
                                <th>QTY CELL</th>
                                <th>WARNA</th>
                                <th>NOMOR WO</th>
                                <th>NO.SERI</th>
                                <th>HEIGTH</th>
                                <th>WIDTH</th>
                                <th>DEEP</th>
                                <th>MH STD</th>
                                <th>MH AKTUAL</th>
                                <th>TGL.SUBMIT DWG FOR APPROVAL</th>
                                <th>TGL APPROVED</th>
                                <th>TGL RELEASE DWG SOFT COPY</th>
                                <th>TGL RELEASE DWG HARD COPY</th>
                                <th>BREAKDOWN</th>
                                <th>BUSBAR</th>
                                <th>TARGET PPC</th>
                                <th>TARGET ENG</th>
                                <th>DESIGN PIC</th>
                                <th>DESIGN START</th>
                                <th>DESIGN END</th>
                                <th>NESTING PIC </th>
                                <th>NESTING START</th>
                                <th>NESTING END</th>
                                <th>PROGRAM PIC</th>
                                <th>PROGRAM START</th>
                                <th>PROGRAM END</th>
                                <th>CHECKER PIC</th>
                                <th>CHECKER START</th>
                                <th>CHECKER END</th>
                                <th>TGL BOX SELESAI</th>
                                <th>KESEPAKATAN DGN CUST DUE DATE</th>
                                <th>TGL TERBIT WO</th>
                                <th>PLAN START PRODUKSI</th>
                                <th>AKTUAL START PRODUKSI</th>
                                <th>PLAN FG WO</th>
                                <th>AKTUAL FG</th>
                                <th>PROGRES</th>
                                <th>DESKRIPSI PROGRES</th>
                                <th>STATUS</th>
                                <th>DELIVERI NO</th>
                                <th>DELIVERI TGL</th>
                                <th>KETERANGAN</th>
                                <th>AKSI</th>
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
                                    <?= $row["no_jo"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_jo"]; ?>
                                </td>
                                <td>
                                    <?= $row["nama_project"]; ?>
                                </td>
                                <td>
                                    <?= $row["kode_gbj"]; ?>
                                </td>
                                <td>
                                    Rp. <?= $row["nilai_harga"]; ?>
                                </td>
                                <td>
                                    <?= $row["nama_panel"]; ?>
                                </td>
                                <td>
                                    <?= $row["tipe_jenis"]; ?>
                                </td>
                                <td>
                                    <?= $row["tipe_fswm"]; ?>
                                </td>
                                <td>
                                    <?= $row["qty_unit"]; ?>
                                </td>
                                <td>
                                    <?= $row["qty_cell"]; ?>
                                </td>
                                <td>
                                    <?= $row["warna"]; ?>
                                </td>
                                <td>
                                    <?= $row["nomor_wo"]; ?>
                                </td>
                                <td>
                                    <?= $row["nomor_seri"]; ?>
                                </td>
                                <td>
                                    <?= $row["size_panel_height"]; ?>
                                </td>
                                <td>
                                    <?= $row["size_panel_width"]; ?>
                                </td>
                                <td>
                                    <?= $row["size_panel_deep"]; ?>
                                </td>
                                <td>
                                    <?= $row["mh_std"]; ?>
                                </td>
                                <td>
                                    <?= $row["mh_aktual"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_submit_dwg_for_approval"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_approved"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_release_dwg_softcopy"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_release_dwg_hardcopy"]; ?>
                                </td>
                                <td>
                                    <?= $row["breakdown"]; ?>
                                </td>
                                <td>
                                    <?= $row["busbar"]; ?>
                                </td>
                                <td>
                                    <?= $row["target_ppc"]; ?>
                                </td>
                                <td>
                                    <?= $row["target_eng"]; ?>
                                </td>
                                <td>
                                    <?= $row["design_pic"]; ?>
                                </td>
                                <td>
                                    <?= $row["design_start"]; ?>
                                </td>
                                <td>
                                    <?= $row["design_end"]; ?>
                                </td>
                                <td>
                                    <?= $row["nesting_pic"]; ?>
                                </td>
                                <td>
                                    <?= $row["nesting_start"]; ?>
                                </td>
                                <td>
                                    <?= $row["nesting_end"]; ?>
                                </td>
                                <td>
                                    <?= $row["program_pic"]; ?>
                                </td>
                                <td>
                                    <?= $row["program_start"]; ?>
                                </td>
                                <td>
                                    <?= $row["program_end"]; ?>
                                </td>
                                <td>
                                    <?= $row["checker_pic"]; ?>
                                </td>
                                <td>
                                    <?= $row["checker_start"]; ?>
                                </td>
                                <td>
                                    <?= $row["checker_end"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_box_selesai"]; ?>
                                </td>
                                <td>
                                    <?= $row["due_date"]; ?>
                                </td>
                                <td>
                                    <?= $row["tgl_terbit_wo"]; ?>
                                </td>
                                <td>
                                    <?= $row["plan_start_produksi"]; ?>
                                </td>
                                <td>
                                    <?= $row["aktual_start_produksi"]; ?>
                                </td>
                                <td>
                                    <?= $row["plan_fg_wo"]; ?>
                                </td>
                                <td>
                                    <?= $row["aktual_fg_wo"]; ?>
                                </td>
                                <td>
                                    <?= $row["progress"]; ?>%
                                </td>
                                <td>
                                    <?= $row["desc_progress"]; ?>
                                </td>
                                <td>
                                    <?= $row["status"]; ?>
                                </td>
                                <td>
                                    <?= $row["delivery_no"]; ?>
                                </td>
                                <td>
                                    <?= $row["delivery_tgl"]; ?>
                                </td>
                                <td>
                                    <?= $row["keterangan"]; ?>
                                </td>
                                <td>
                                    <li class="liaksi">
                                        <button type="submit" name="submit"><a
                                                href="editmonitoring.php?id=<?= $row["id"]; ?>"
                                                class="inputbtn2">Ubah</a></button>
                                    </li>
                                    <li class="liaksi">
                                        <button type="submit" name="submit"><a
                                                href="hapusmonitoring.php?id=<?= $row["id"]; ?>"
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