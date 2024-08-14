<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Sidebar</title>
    <link rel="stylesheet" href="./css/style.css">
    <!-- Font Awesome CDN for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                    <li>
                        <a href="sampah.php">
                            <i class="fa-solid fa-trash"></i>
                            <span>Sampah</span>
                        </a>
                    </li>
                    <li>
                        <a href="transaksi.php">
                            <i class="fa-solid fa-exchange-alt"></i>
                            <span>Transaksi</span>
                        </a>
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