<?php
require_once 'header.php'; // Sertakan file header.php yang berisi session start dan koneksi database
require_once 'fungsi.php'; // Include the functions

// Check if the user is logged in
checkSession();

// Get the username from the session
$username = $_SESSION['username'];

// Fetch user data from the database
$data = getUserData($koneksi, $username);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Detail User </title>
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
                            <h3 class="main--title">Data Project</h3>
                        </div>
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Profile</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <div class="container-xl px-4 mt-4">
                            <div class="row">

                                <div class="col">
                                    <!-- Account details card-->
                                    <div class="card mb-4">
                                        <div class="card-header">Account Details</div>
                                        <div class="card-body">
                                            <?php

                                        ?>
                                            <form>
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="inputUsername">Username</label>
                                                    <input class="form-control" id="inputUsername" type="text"
                                                        value="<?php echo $data['username']; ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="inputNama">Nama</label>
                                                    <input class="form-control" id="inputNama" type="text"
                                                        value="<?php echo $data['nama']; ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small mb-1" for="inputRole">Role</label>
                                                    <input class="form-control" id="inputRole" type="text"
                                                        value="<?php echo $data['role']; ?>" readonly>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Content Row -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("footer.php") ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

</body>

</html>