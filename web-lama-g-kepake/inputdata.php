<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan
if (isset($_POST["submit"])) {

    //cek apakah data berhasil ditambahkan
    if (inputdata($_POST) > 0) {
        echo "
				<script>  
					alert('Data Berhasil Ditambahkan');
					document.location.href ='monitoring.php';
				</script>
				";
    } else {
        echo "
				<script>  
					alert('Data Gagal Ditambahkan');
					document.location.href ='monitoring.php';
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
    <title>PMElectric | Input Project</title>
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
                <span>Input Data</span>
                <h2>Project</h2>
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
                    <label for="">ID PROJECT</label><br>
                    <input type="text" placeholder="Masukkan ID Project" name="id"><br><br>

                    <label for="">NO JO</label><br>
                    <input type="text" placeholder="Masukkan Nomor JO" name="no_jo"><br><br>

                    <label for="">TGL JO</label><br>
                    <input type="date" placeholder="Masukan Tgl JO" name="tgl_jo"><br><br>

                    <label for="">NAMA PROJECT</label><br>
                    <input type="text" placeholder="Masukkan Nama Project" name="nama_project"><br><br>

                    <label for="">KODE GBJ</label><br>
                    <input type="text" placeholder="Masukkan Kode GBJ" name="kode_gbj"><br><br>

                    <label for="">HARGA</label><br>
                    <input type="text" placeholder="Masukkan Harga" name="nilai_harga"><br><br>

                    <label for="">NAMA PANEL</label><br>
                    <input type="text" placeholder="Masukkan Nama Panel" name="nama_panel"><br><br>

                    <label for="">TIPE JENIS</label><br>
                    <input type="text" placeholder="Masukkan Tipe Jenis" name="tipe_jenis"><br><br>

                    <label for="">TIPE FS/WM</label><br>
                    <input type="text" placeholder="Masukkan Tipe FS/WM" name="tipe_fswm"><br><br>

                    <label for="">UNIT</label><br>
                    <input type="text" placeholder="Masukkan Unit" name="qty_unit"><br><br>

                    <label for="">CELL</label><br>
                    <input type="text" placeholder="Masukkan Cell" name="qty_cell"><br><br>

                    <label for="">WARNA</label><br>
                    <input type="text" placeholder="Masukkan Warna" name="warna"><br><br>

                    <label for="">NO WO</label><br>
                    <input type="text" placeholder="Masukkan No WO" name="nomor_wo"><br><br>

                    <label for="">NO SERI</label><br>
                    <input type="text" placeholder="Masukkan No Seri" name="nomor_seri"><br><br>

                    <label for="">HEIGHT</label><br>
                    <input type="text" placeholder="Masukkan Height" name="size_panel_height"><br><br>

                    <label for="">WIDTH</label><br>
                    <input type="text" placeholder="Masukkan Width" name="size_panel_width"><br><br>

                    <label for="">DEEP</label><br>
                    <input type="text" placeholder="Masukkan Deep" name="size_panel_deep"><br><br>

                    <label for="">MH STD</label><br>
                    <input type="date" placeholder="Masukkan MH STD" name="mh_std"><br><br>

                    <label for="">MH AKTUAL</label><br>
                    <input type="date" placeholder="Masukkan MH AKTUAL" name="mh_aktual"><br><br>

                    <label for="">TGL SUMBIT DWG FOR APPROVAL</label><br>
                    <input type="date" placeholder="Masukkan Tgl" name="tgl_submit_dwg_for_approval"><br><br>

                    <label for="">TGL APPROVED</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="tgl_approved"><br><br>

                    <label for="">TGL RELEASE DWG SOFT COPY</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="tgl_release_dwg_softcopy"><br><br>

                    <label for="">TGL RELEASE DWG HARD COPY</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="tgl_release_dwg_hardcopy"><br><br>

                    <label for="">BREAKDOWN</label><br>
                    <input type="date" placeholder="Masukkan Breakdown" name="breakdown"><br><br>

                    <label for="">BUSBAR</label><br>
                    <input type="date" placeholder="Masukkan Busbar" name="busbar"><br><br>
                    
                    <label for="">TARGET PPC</label><br>
                    <input type="date" placeholder="Masukkan Target PPC" name="target_ppc"><br><br>

                    <label for="">TARGET ENG</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="target_eng"><br><br>

                    <label for="">TGL BOX SELESAI</label><br>
                    <input type="date" placeholder="Masukkan Target PPC" name="tgl_box_selesai"><br><br>

                    <label for="">DUE DATE DENGAN CUSTOMER</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="due_date"><br><br>

                    <label for="">TGL TERBIT WO</label><br>
                    <input type="date" placeholder="Masukkan Target PPC" name="tgl_terbit_wo"><br><br>

                    <label for="">PLAN START PRODUKSI</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="plan_start_produksi"><br><br>

                    <label for="">AKTUAL START PRODUKSI</label><br>
                    <input type="date" placeholder="Masukkan Target PPC" name="aktual_start_produksi"><br><br>

                    <label for="">PLAN FG WO</label><br>
                    <input type="date" placeholder="Masukkan Target Eng" name="plan_fg_wo"><br><br>

                    <label for="">PROGRES %</label><br>
                    <input type="text" placeholder="Masukkan Breakdown" name="progress"><br><br>

                    <label for="">DESKRIPSI PROGRES</label><br>
                    <input type="text" placeholder="Masukkan Busbar" name="desc_progress"><br><br>

                    <label for="">STATUS</label><br>
                    <input type="text" placeholder="Masukkan Breakdown" name="status"><br><br>

                    <label for="">NO DELIVERY</label><br>
                    <input type="text" placeholder="Masukkan Busbar" name="delivery_no"><br><br>

                    <label for="">TGL DELIVERY</label><br>
                    <input type="text" placeholder="Masukkan Breakdown" name="delivery_tgl"><br><br>

                    <label for="">KETERANGAN</label><br>
                    <input type="text" placeholder="Masukkan Busbar" name="keterangan"><br><br>
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