<?php
include 'header.php';
include 'fungsi.php';
include 'koneksi.php'; // Gunakan koneksi mysqli yang sudah ada

// Dapatkan jumlah data per halaman dari dropdown, default 10
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Cek apakah form pencarian telah disubmit
$search_nik = isset($_GET['search_nik']) ? $_GET['search_nik'] : null;

// Query untuk menghitung jumlah total data nasabah yang aktif (status = 1)
$total_query = "SELECT COUNT(*) AS total FROM user WHERE role = 'nasabah' AND status = 1";
if ($search_nik) {
    $total_query .= " AND nik LIKE '%$search_nik%'";
}
$total_result = mysqli_query($koneksi, $total_query);
$total_rows = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_rows / $limit);

// Batasi jumlah pagination yang ditampilkan per set (misalnya 10 halaman per set)
$pagination_limit = 10;
$start_page = max(1, $page - floor($pagination_limit / 2));
$end_page = min($total_pages, $start_page + $pagination_limit - 1);

// Jika halaman terakhir dalam set kurang dari batas yang diinginkan, sesuaikan halaman awal
if ($end_page - $start_page < $pagination_limit - 1) {
    $start_page = max(1, $end_page - $pagination_limit + 1);
}

// Query untuk menampilkan nasabah yang aktif (status = 1)
$query = "
    SELECT id, username, nama, email, notelp, nik, no_rek FROM user
    WHERE role = 'nasabah' AND status = 1
";
if ($search_nik) {
    $query .= " AND nik LIKE '%$search_nik%'";
}
$query .= " ORDER BY LENGTH(id), CAST(id AS UNSIGNED) LIMIT $limit OFFSET $offset";
$nasabah_result = mysqli_query($koneksi, $query);

// Handle soft delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "UPDATE user SET status = 0 WHERE id = ?"; // Ubah status menjadi 0 untuk menandai terhapus

    // Prepare the statement
    $stmt = mysqli_prepare($koneksi, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);

    $_SESSION['message'] = "Data berhasil dihapus!";
    header("Location: nasabah.php");
    exit();
}

// Query untuk menampilkan nasabah yang sudah dihapus (status = 0)
$deleted_users_query = "SELECT id, username, nama, email, notelp, nik, no_rek FROM user WHERE role = 'nasabah' AND status = 0";
$deleted_users = mysqli_query($koneksi, $deleted_users_query);

// Handle restore all
if (isset($_GET['restore_all'])) {
    $restore_all_query = "UPDATE user SET status = 1 WHERE role = 'nasabah' AND status = 0";
    mysqli_query($koneksi, $restore_all_query);

    $_SESSION['message'] = "Semua data berhasil dikembalikan!";
    header("Location: nasabah.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Nasabah</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("sidebar.php") ?>

        <!-- Main Content -->
        <div class="main--content">
            <div class="main--content--monitoring">
                <div class="header--wrapper">
                    <div class="header--title">
                        <span>Halaman</span>
                        <h2>Nasabah</h2>
                    </div>
                </div>

                <!-- Tabel Nasabah Aktif -->
                <div class="tabular--wrapper">
                    <div class="search--wrapper">
                        <form method="GET" action="">
                            <input type="text" name="search_nik" placeholder="Cari NIK nasabah..." value="<?= htmlspecialchars($search_nik) ?>" pattern="\d{16}" maxlength="16" title="NIK harus terdiri dari 16 digit angka">
                            <button type="submit" class="inputbtn">Cari</button>

                            <div class="form-group">
                                <label for="limit">Tampilkan:</label>
                                <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
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
                                        <th>No Rek</th>
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
                                            <td><?= htmlspecialchars($row["no_rek"]); ?></td>
                                            <td>
                                                <a href="detail_nasabah.php?id=<?= htmlspecialchars($row["id"]); ?>" class="inputbtn6">Detail</a>
                                                <a href="edit_nasabah.php?id=<?= htmlspecialchars($row["id"]); ?>" class="btn btn-warning">Ubah</a>
                                                <a href="nasabah.php?delete_id=<?= htmlspecialchars($row["id"]); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="pagination-wrapper">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a href="?page=<?= $page - 1; ?>&limit=<?= $limit; ?>&search_nik=<?= urlencode($search_nik); ?>" class="page-link">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                            <a href="?page=<?= $i; ?>&limit=<?= $limit; ?>&search_nik=<?= urlencode($search_nik); ?>" class="page-link"><?= $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($end_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a href="?page=<?= $end_page + 1; ?>&limit=<?= $limit; ?>&search_nik=<?= urlencode($search_nik); ?>" class="page-link">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tabel Nasabah Terhapus -->
                <!-- <h1>Data Nasabah Terhapus</h1> -->

                <?php if (mysqli_num_rows($deleted_users) === 0): ?>
                    <div class="alert alert-warning">
                        <strong>Tidak ada nasabah yang dihapus!</strong>
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
                                <th>No Rek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deleted_users as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["id"]); ?></td>
                                    <td><?= htmlspecialchars($row["username"]); ?></td>
                                    <td><?= htmlspecialchars($row["nama"]); ?></td>
                                    <td><?= htmlspecialchars($row["email"]); ?></td>
                                    <td><?= htmlspecialchars($row["notelp"]); ?></td>
                                    <td><?= htmlspecialchars($row["nik"]); ?></td>
                                    <td><?= htmlspecialchars($row["no_rek"]); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>


                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>