<?php
include 'header.php';
include 'fungsi.php';

// Logika untuk menghapus admin jika ada parameter `id` pada URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (hapususerById($id) > 0) {
        echo "
            <script>
                alert('Data Berhasil Dihapus');
                document.location.href='admin.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data Gagal Dihapus');
                document.location.href='admin.php';
            </script>
        ";
    }
}

// Cek apakah form pencarian telah disubmit
$search_nik = $_GET['search_nik'] ?? null;
$query_all = getAdmin($search_nik);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | admin</title>
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
                        <span>Halama</span>
                        <h2>admin</h2>
                    </div>
                </div>

                <!-- Ini Tabel -->
                <div class="tabular--wrapper">
                    <div class="search--wrapper">
                        <form method="GET" action="">
                            <input type="text" name="search_nik" placeholder="Cari NIK admin..."
                                value="<?= $search_nik ?>" pattern="\d{16}" maxlength="16"
                                title="NIK harus terdiri dari 16 digit angka" required>
                            <button type="submit" class="inputbtn">Cari</button>
                        </form>
                    </div>
                    <script>
                    document.querySelector('form').addEventListener('submit', function(e) {
                        var nikInput = document.querySelector('input[name="search_nik"]').value;
                        if (nikInput.length !== 16 || !/^\d+$/.test(nikInput)) {
                            alert('NIK harus terdiri dari 16 digit angka');
                            e.preventDefault();
                        }
                    });
                    </script>
                    <div class="row align-items-start">
                        <div class="user--info">
                            <h3 class="main--title">Data Project</h3>
                            <a href="register.php"><button type="button" name="button"
                                    class="inputbtn .border-right">Tambah</button></a>
                        </div>
                    </div>

                    <?php
                    if (isset($_SESSION['message'])) {
                        echo "<h4>" . $_SESSION['message'] . "</h4>";
                        unset($_SESSION['message']);
                    }
                    ?>

                    <div class="table-container">
                        <?php if (empty($query_all)) : ?>
                        <div class="alert alert-warning">
                            <strong>admin tidak ditemukan!</strong> Silakan coba NIK yang lain.
                        </div>
                        <?php else : ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Telp</th>
                                    <th>NIK</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($query_all as $row): ?>
                                <tr>
                                    <td><?= $row["id"]; ?></td>
                                    <td><?= $row["username"]; ?></td>
                                    <td><?= $row["nama"]; ?></td>
                                    <td><?= $row["email"]; ?></td>
                                    <td><?= $row["notelp"]; ?></td>
                                    <td><?= $row["nik"]; ?></td>
                                    <td><?= $row["alamat"]; ?></td>
                                    <td><?= $row["tgl_lahir"]; ?></td>
                                    <td><?= $row["kelamin"]; ?></td>
                                    <td>
                                        <li class="liaksi">
                                            <button type="submit" name="submit"><a
                                                    href="edit_admin.php?id=<?= $row["id"]; ?>"
                                                    class="inputbtn6">Ubah</a></button>
                                        </li>
                                        <li class="liaksi">
                                            <button type="submit" name="submit"><a
                                                    href="admin.php?id=<?= $row["id"]; ?>" class="inputbtn7"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a></button>
                                        </li>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Batas Akhir Tabel -->
            </div>
            <!-- Batas Akhir card-container -->
        </div>
    </div>
    <!-- Batas Akhir Main-Content -->
</body>

</html>