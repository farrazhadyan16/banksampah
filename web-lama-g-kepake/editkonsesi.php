<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan

//ambil data di url
$id = $_GET['id_konsesi'];

//kueri data berdasarkan id
$konsesi = query("SELECT * FROM konsesi WHERE id_konsesi=$id")[0];


if (isset($_POST["submit"])) {

    //cek apakah data berhasil diubah
    if (updatedatakonsesi($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Diubah');
					document.location.href ='konsesi.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Diubah');
					document.location.href ='konsesi.php';
				</script>
				";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMElectric | Edit Konsesi</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/pm.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <!-- Ini Sidebar -->
    <?php include("sidebar.php")?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Edit Data</span>
                <h2>Konsesi</h2>
            </div>
            <div class="user--info">
                <img src="./img/logoPM.png" alt="logo">
            </div>
        </div>

        <!-- Ini card-container -->
        <div class="card--container">
            <h3 class="main--title">Isi Form Berikut</h3>
            <form method="POST" action="">
                <div class="container">
                    <hr>

                    <input type="hidden" name="page" value="design">

                    <!-- Tambahkan input hidden untuk menandai halaman -->
                    <label for="">NO</label><br>
                    <input type="int" placeholder="Masukkan No" name="id_konsesi"
                        value="<?= $konsesi["id_konsesi"] ?>"><br><br>

                    <label for="">JO</label><br>
                    <input type="text" placeholder="Masukkan JO" name="jo" value="<?= $konsesi["jo"] ?>"><br><br>

                    <label for="">WO</label><br>
                    <input type="text" placeholder="Masukkan WO" name="wo" value="<?= $konsesi["wo"] ?>"><br><br>

                    <label for="">NAMA PROJECT</label><br>
                    <input type="text" placeholder="Masukkan Nama Project" name="nama_project"
                        value="<?= $konsesi["nama_project"] ?>"><br><br>

                    <label for="">NAME PANEL</label><br>
                    <input type="text" placeholder="Masukkan Nama Panel" name="nama_panel"
                        value="<?= $konsesi["nama_panel"] ?>"><br><br>

                    <label for="">UNIT</label><br>
                    <input type="int" placeholder="Masukkan Unit" name="unit" value="<?= $konsesi["unit"] ?>"><br><br>

                    <label for="">JENIS</label><br>
                    <input type="text" placeholder="Masukkan Jenis" name="jenis"
                        value="<?= $konsesi["jenis"] ?>"><br><br>

                    <label for="">KONSESI</label><br>
                    <input type="text" placeholder="Masukkan Konsesi" name="konsesi"
                        value="<?= $konsesi["konsesi"] ?>"><br><br>

                    <label for="">JML</label><br>
                    <input type="int" placeholder="Masukkan JML" name="jumlah"
                        value="<?= $konsesi["jumlah"] ?>"><br><br>

                    <label for="">NO LKPJ</label><br>
                    <input type="int" placeholder="Masukkan No LKPJ" name="no_lkpj"
                        value="<?= $konsesi["no_lkpj"] ?>"><br><br>

                    <label for="">STATUS</label><br>
                    <select name="status" id="statdrop">
                        <option value="Open" <?= ($konsesi["status"] == 'Open') ? 'selected' : '' ?>>Open</option>
                        <option value="Close" <?= ($konsesi["status"] == 'Close') ? 'selected' : '' ?>>Close</option>
                    </select>
                    <br><br>

                    <!-- <label for="">STATUS</label><br>
                    <input type="text" placeholder="Masukkan Status" name="status"
                        value="<?= $konsesi["status"] ?>"><br><br> -->

                    <label for="">TGL MATRIAL DATANG</label><br>
                    <input type="date" placeholder="Masukkan Tanggal Datang" name="tgl_matrial_dtg"
                        value="<?= $konsesi["tgl_matrial_dtg"] ?>"><br><br>

                    <label for="">TGL PASANG</label><br>
                    <input type="date" placeholder="Masukkan Tanggal Pasang" name="tgl_pasang"
                        value="<?= $konsesi["tgl_pasang"] ?>"><br><br>

                    <label for="">KETERANGAN</label><br>
                    <input type="text" placeholder="Masukkan Keterangan" name="keterangan"
                        value="<?= $konsesi["keterangan"] ?>"><br><br>



                    <hr>

                    <button type="submit" name="submit" class="inputbtn">Input</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Batas Akhir card-container -->
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>