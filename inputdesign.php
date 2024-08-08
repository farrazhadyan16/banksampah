<?php
include 'header.php';
require 'fungsi.php';
//cek apakah tombol sudah ditekan
if (isset($_POST["submit"])) {

    //cek apakah data berhasil ditambahkan
    if (updateDesign($_POST) > 0) {
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
    <title>PMElectric | Design</title>
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
                <h2>Design</h2>
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

                    <label for="id">ID Project</label><br>
                    <!-- Fetch IDs from the database -->
                    <select name="id" id="statdrop">
                        <?php
                    // Assuming you have a function to retrieve IDs from the database
                    $projectIds = getProjectIds(); // You need to implement this function

                    // Loop through the IDs and create options for the dropdown
                    foreach ($projectIds as $projectId) {
                        echo "<option value=\"$projectId\">$projectId</option>";
                    }
                    ?>
                    </select><br><br>

                    <label for="">ID PIC</label><br>
                    <input type="text" placeholder="Masukkan PIC" name="design_pic"><br><br>

                    <label for="">Start</label><br>
                    <input type="date" placeholder="Masukan Waktu Start" name="design_start"><br><br>

                    <label for="">END</label><br>
                    <input type="date" placeholder="Masukan Waktu End" name="design_end"><br><br>

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