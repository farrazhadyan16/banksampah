<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WiseWallet</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/logo.png">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="sidebar">
        <!-- <div class="logo"></div> -->
        <ul class="menu">
            <li>
                <a href="main_admin.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="monitoring.php">
                    <i class="fa-solid fa-desktop"></i>
                    <span>Sampah</span>
                </a>
            </li>
            <li>
                <a href="monitoring.php">
                    <i class="fa-solid fa-desktop"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li>
                <a href="monitoring.php">
                    <i class="fa-solid fa-desktop"></i>
                    <span>Admin</span>
                </a>
            </li>
            <li>
                <a href="monitoring.php">
                    <i class="fa-solid fa-desktop"></i>
                    <span>Nasabah</span>
                </a>
            </li>
            <!-- <li>
                <a href="tesphp.php">
                    <i class="fa-solid fa-desktop"></i>
                    <span>tes</span>
                </a>
            </li> -->
            <!-- <?php if (in_array("admin",$_SESSION['admin_akses'])) { ?>
            <li>
                <a href="importdata.php">
                    <i class="fa-solid fa-upload"></i>
                    <span>Import Project</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array("admin",$_SESSION['admin_akses'])) { ?>
            <li>
                <a href="inputdata.php">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Input Project</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array("design",$_SESSION['admin_akses'])) { ?>
            <li>
                <a href="inputdesign.php">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Input Design</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array("nesting",$_SESSION['admin_akses'])) { ?>
            <li>
                <a href="inputnesting.php">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Input Nesting</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array("program",$_SESSION['admin_akses'])) { ?>
            <li>
                <a href="inputprogram.php">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Input Program</span>
                </a>
            </li>
            <?php } ?>
            <?php if (in_array("checker",$_SESSION['admin_akses'])) { ?>
            <li>
                <a href="inputchecker.php">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Input Checker</span>
                </a>
            </li>
            <?php } ?> -->
            <li>
                <a href="konsesi.php">
                    <i class="fas fa-window-maximize"></i>
                    <span>Konsesi</span>
                </a>
            </li>
            <!-- <li>
                <a href="inputkonsesi.php">
                    <i class="fas fa-user"></i>
                    <span>Input Konsesi</span>
                </a>
            </li> -->
            <li class="logout">
                <a href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</body>

</html>