<?php
include 'header.php';
include 'fungsi.php';

// Dapatkan jumlah data per halaman dari dropdown, default 10
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

// Dapatkan halaman saat ini dari URL, default halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Cek apakah form pencarian telah disubmit
$search_nik = isset($_GET['search_nik']) ? $_GET['search_nik'] : null;
$query_all = getNasabah($search_nik);

// Hitung total data untuk pagination
$total_query = "SELECT COUNT(*) AS total FROM user WHERE role = 'nasabah'";
if ($search_nik) {
    $total_query .= " AND nik LIKE '%$search_nik%'";
}
$total_result = query($total_query);
$total_rows = $total_result[0]['total'];
$total_pages = ceil($total_rows / $limit);

// Modifikasi query untuk pagination
$query = "
    SELECT * FROM user
    WHERE role = 'nasabah'
";
if ($search_nik) {
    $query .= " AND nik LIKE '%$search_nik%'";
}
$query .= " ORDER BY LENGTH(id), CAST(id AS UNSIGNED) LIMIT $limit OFFSET $offset";

// Eksekusi query
$nasabah_result = query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Nasabah</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                        <span>Halaman</span>
                        <h2>Nasabah</h2>
                    </div>
                </div>

                <!-- Ini Tabel -->
                <div class="tabular--wrapper">
                    <div class="search--wrapper">
                        <form method="GET" action="">
                            <input type="text" name="search_nik" placeholder="Cari NIK nasabah..."
                                value="<?= htmlspecialchars($search_nik) ?>" pattern="\d{16}" maxlength="16"
                                title="NIK harus terdiri dari 16 digit angka">
                            <button type="submit" class="inputbtn">Cari</button>

                            <div class="form-group">
                                <label for="limit">Tampilkan:</label>
                                <select name="limit" id="limit" class="form-control">
                                    <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                                    <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                                    <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                                    <option value="40" <?php if ($limit == 40) echo 'selected'; ?>>40</option>
                                    <option value="0" <?php if ($limit == 0) echo 'selected'; ?>>Semua</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="row align-items-start">
                        <div class="user--info">
                            <h3 class="main--title">Data Nasabah</h3>
                            <a href="register.php"><button type="button" class="inputbtn">Tambah</button></a>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info">
                            <?= htmlspecialchars($_SESSION['message']); ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <div class="table-container">
                        <?php if (empty($nasabah_result)): ?>
                            <div class="alert alert-warning">
                                <strong>Nasabah tidak ditemukan!</strong> Silakan coba NIK yang lain.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered">
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
                                    <?php foreach ($nasabah_result as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row["id"]); ?></td>
                                            <td><?= htmlspecialchars($row["username"]); ?></td>
                                            <td><?= htmlspecialchars($row["nama"]); ?></td>
                                            <td><?= htmlspecialchars($row["email"]); ?></td>
                                            <td><?= htmlspecialchars($row["notelp"]); ?></td>
                                            <td><?= htmlspecialchars($row["nik"]); ?></td>
                                            <td><?= htmlspecialchars($row["alamat"]); ?></td>
                                            <td><?= htmlspecialchars($row["tgl_lahir"]); ?></td>
                                            <td><?= htmlspecialchars($row["kelamin"]); ?></td>
                                            <td>
                                                <a href="edit_nasabah.php?id=<?= htmlspecialchars($row["id"]); ?>" class="inputbtn6">Ubah</a>
                                                <a href="nasabah.php?id=<?= htmlspecialchars($row["id"]); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="pagination-wrapper">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item"><a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&search_nik=<?php echo urlencode($search_nik); ?>" class="page-link">Previous</a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                            <a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search_nik=<?php echo urlencode($search_nik); ?>" class="page-link"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item"><a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&search_nik=<?php echo urlencode($search_nik); ?>" class="page-link">Next</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Event listener untuk mengirim form secara otomatis ketika dropdown limit berubah
        document.getElementById('limit').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>

</html>