<?php
include 'header.php';
include 'fungsi.php';

// Check if 'id' is set in the URL and call the delete function
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    if (hapusSampah($id) > 0) {
        echo "
            <script>
                alert('Data Berhasil Dihapus');
                document.location.href='sampah.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data Gagal Dihapus');
                document.location.href='sampah.php';
            </script>
        ";
    }
}

// Call the new function to retrieve sampah data
$query_all = getSampahData();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Sampah</title>
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
                        <h2>Sampah</h2>
                    </div>
                </div>

                <!-- Ini Tabel -->
                <div class="tabular--wrapper">
                    <div class="row align-items-start">
                        <div class="user--info">
                            <h3 class="main--title">Data Sampah</h3>
                            <a href="tambah_sampah.php"><button type="button" name="button"
                                    class="inputbtn .border-right">Tambah</button></a>
                            <a href="manage_kategori.php"><button type="button" name="button"
                                    class="inputbtn .border-right">Manage Kategori</button></a>
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
                                    <th>ID</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Harga Nasabah</th>
                                    <th>Harga Pengepul</th>
                                    <th>Jumlah (KG)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($query_all as $row): ?>
                                <tr>
                                    <td><?= $row["id"]; ?></td>
                                    <td><?= $row["kategori_name"]; ?></td>
                                    <td><?= $row["jenis"]; ?></td>
                                    <td>Rp. <?= number_format($row["harga"], 0, ',', '.'); ?></td>
                                    <td>Rp. <?= number_format($row["harga_pusat"], 0, ',', '.'); ?></td>
                                    <td><?= $row["jumlah"]; ?> KG</td>
                                    <td>
                                        <li class="liaksi">
                                            <button type="submit" name="submit">
                                                <a href="edit_sampah.php?id=<?= $row["id"]; ?>"
                                                    class="inputbtn6">Ubah</a>
                                            </button>
                                        </li>
                                        <li class="liaksi">
                                            <button type="submit" name="submit">
                                                <a href="sampah.php?id=<?= $row["id"]; ?>"
                                                    class="inputbtn7">Hapus</a>
                                            </button>
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
            </div>
        </div>
        <!-- Batas Akhir Main-Content -->
    </div>
</body>

</html>
