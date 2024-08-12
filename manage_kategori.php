<?php
include 'header.php';
require 'fungsi.php';

// Handle Add Category
if (isset($_POST["add_category"])) {
    $name = $_POST['name'];
    
    if (addCategory($name) > 0) {
        echo "<script>
                alert('Kategori berhasil ditambahkan');
                document.location.href = 'manage_kategori.php';
              </script>";
    } else {
        echo "<script>
                alert('Kategori gagal ditambahkan');
              </script>";
    }
}

// Handle Delete Category
if (isset($_POST["delete_category"])) {
    $id = $_POST['id'];
    
    if (deleteCategory($id) > 0) {
        echo "<script>
                alert('Kategori berhasil dihapus');
                document.location.href = 'manage_kategori.php';
              </script>";
    } else {
        echo "<script>
                alert('Kategori gagal dihapus');
              </script>";
    }
}

// Fetch all categories
$categories = query("SELECT * FROM kategori_sampah ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Manage Kategori</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/pm.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <!-- Ini Sidebar -->
    <?php include("sidebar.php") ?>
    <!-- Batas Akhir Sidebar -->

    <!-- Ini Main-Content -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Manage Data</span>
                <h2>Manage Kategori</h2>
            </div>
            <div class="user--info">
                <img src="./img/logoPM.png" alt="logo">
            </div>
        </div>

        <!-- Ini card-container -->
        <div class="card--container">
            <h3 class="main--title">Tambah Kategori</h3>
            <form method="POST" action="">
                <div class="container">
                    <hr>
                    <label for="name">Nama Kategori</label><br>
                    <input type="text" placeholder="Masukkan Nama Kategori" name="name" required><br><br>

                    <button type="submit" name="add_category" class="inputbtn">Tambah</button>
                </div>
            </form>

            <h3 class="main--title">Daftar Kategori</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Created At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category["id"]; ?></td>
                            <td><?= $category["name"]; ?></td>
                            <td><?= $category["created_at"]; ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $category["id"]; ?>">
                                    <button type="submit" name="delete_category" class="inputbtn7">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>