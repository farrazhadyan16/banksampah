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
    <title>Bank Sampah | Detail User</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CDN link -->
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
                        <h2>Detail User</h2>
                    </div>
                </div>

                <!-- Ini Tabel -->
                <div class="tabular--wrapper">


                    <!-- Content Row -->
                    <div class="row">
                        <div class="container-xl px-4">
                            <div class="row">
                                <!-- Mengatur kolom lebih kecil dan dekat ke kiri -->
                                <div class="col-md-6">
                                    <!-- Account details section -->
                                    <h4 class="mb-3">Account Details</h4>
                                    <form>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputUsername">Username</label>
                                            <input class="form-control" id="inputUsername" type="text"
                                                value="<?php echo htmlspecialchars($data['username']); ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputNama">Nama</label>
                                            <input class="form-control" id="inputNama" type="text"
                                                value="<?php echo htmlspecialchars($data['nama']); ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputRole">Role</label>
                                            <input class="form-control" id="inputRole" type="text"
                                                value="<?php echo htmlspecialchars($data['role']); ?>" readonly>
                                        </div>
                                    </form>
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

    <!-- Bootstrap JS and dependencies (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>