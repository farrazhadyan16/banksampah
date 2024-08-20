<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

// Get the user's role from the session
$user_role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Sidebar</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
    .menu ul {
        list-style-type: none;
        padding-left: 20px;
    }

    .menu ul li {
        padding: 8px 0;
    }

    .dropdown-content {
        display: none;
        list-style-type: none;
        padding-left: 20px;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown>a:after {
        content: '\f0d7';
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        float: right;
    }
    </style>
</head>

<body>
    <div id="wrapper">

        <div id="content">
            <div class="sidebar">
                <ul class="menu">
                    <li>
                        <a href="index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <?php if ($user_role === 'admin' || $user_role === 'superadmin') { ?>
                    <li>
                        <a href="sampah.php">
                            <i class="fa-solid fa-trash"></i>
                            <span>Sampah</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#">
                            <i class="fa-solid fa-exchange-alt"></i>
                            <span>Transaksi</span>
                        </a>
                        <ul class="dropdown-content">
                            <li><a href="setor_sampah.php">Tambah Transaksi</a></li>
                            <li><a href="semua_transaksi.php">Semua Transaksi</a></li>
                            <li><a href="recap_transaksi.php">Rekap Transaksi</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="admin.php">
                            <i class="fa-solid fa-user-shield"></i>
                            <span>Admin</span>
                        </a>
                    </li>
                    <li>
                        <a href="nasabah.php">
                            <i class="fa-solid fa-user"></i>
                            <span>Nasabah</span>
                        </a>
                    </li>
                    <?php } ?>


                    <li>
                        <a href="detail_user.php">
                            <i class="fa-solid fa-id-card"></i>
                            <span>Detail User</span>
                        </a>
                    </li>

                    <li class="logout">
                        <a href="logout.php">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>