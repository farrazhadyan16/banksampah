<?php
include 'header.php';
include 'fungsi.php';

// Variabel untuk menyimpan pesan atau error
$message = "";

// Jika tombol CHECK ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    if (empty($search_value)) {
        $message = "NIK tidak boleh kosong.";
    } else {
        $user_query = "SELECT user.*, dompet.uang, dompet.emas FROM user 
                    LEFT JOIN dompet ON user.id = dompet.id_user 
                    WHERE user.nik LIKE '%$search_value%' AND user.role = 'Nasabah'";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
        } else {
            $message = "User dengan role 'Nasabah' tidak ditemukan.";
        }
    }
}

// Jika tombol Submit ditekan untuk penarikan emas
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdraw'])) {
    $user_id = $_POST['user_id'];
    $jumlah_emas = $_POST['emas'];

    // Validasi input
    if (empty($jumlah_emas) || !is_numeric($jumlah_emas) || $jumlah_emas <= 0) {
        $message = "Jumlah emas harus diisi dan berupa angka positif.";
    } else {
        // Cek apakah saldo emas mencukupi
        if ($jumlah_emas > $user_data['emas']) {
            $message = "Saldo emas Anda tidak mencukupi.";
        } else {
            // Update saldo emas
            $update_query = "UPDATE dompet 
                             SET emas = emas - $jumlah_emas 
                             WHERE id_user = $user_id";
            
            if ($conn->query($update_query) === TRUE) {
                $message = "Penarikan emas berhasil! Saldo emas Anda telah diperbarui.";
            } else {
                $message = "Terjadi kesalahan saat melakukan penarikan: " . $conn->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah | Penarikan Emas</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./img/PM.ico">
    <!-- Font Awesome Cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("sidebar.php") ?>
        <!-- Akhir Sidebar -->

        <!-- Main Content -->
        <div class="main--content">
            <div class="main--content--monitoring">
                <div class="header--wrapper">
                    <div class="header--title">
                        <span>Halaman</span>
                        <h2>Penarikan Emas</h2>
                    </div>
                    <div class="user--info">
                        <a href="setor_sampah.php"><button type="button" name="button" class="inputbtn">Setor
                                Sampah</button></a>
                        <a href="konversi_emas.php"><button type="button" name="button" class="inputbtn">Konversi
                                Emas</button></a>
                        <a href="konversi_rupiah.php"><button type="button" name="button" class="inputbtn">Konversi
                                Rupiah</button></a>
                        <a href="tarik_emas.php"><button type="button" name="button" class="inputbtn">Tarik
                                Emas</button></a>
                        <a href="tarik_rupiah.php"><button type="button" name="button" class="inputbtn">Tarik
                                Rupiah</button></a>
                        <a href="jual_sampah.php"><button type="button" name="button" class="inputbtn">Jual
                                Sampah</button></a>
                    </div>
                </div>

                <!-- Start of Form Section -->
                <div class="tabular--wrapper">
                    <!-- Search Section -->
                    <form method="POST" action="">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <input type="text" name="search_value" id="search_value" class="form-control"
                                    placeholder="Search by NIK" maxlength="16" oninput="validateNIK(this)"
                                    value="<?php echo isset($search_value) ? $search_value : ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="search" class="btn btn-dark w-100">CHECK</button>
                            </div>
                        </div>
                    </form>

                    <!-- User Information Section -->
                    <?php if (isset($user_data)) { ?>
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <p><strong>ID</strong> : <?php echo $user_data['id']; ?></p>
                            <p><strong>NIK</strong> : <?php echo $user_data['nik']; ?></p>
                            <p><strong>Email</strong> : <?php echo $user_data['email']; ?></p>
                            <p><strong>Username</strong> : <?php echo $user_data['username']; ?></p>
                        </div>
                        <div class="col-md-5">
                            <p><strong>Nama Lengkap</strong> : <?php echo $user_data['nama']; ?></p>
                            <p><strong>Saldo Uang</strong> : Rp.
                                <?php echo number_format($user_data['uang'], 2, ',', '.'); ?></p>
                            <p><strong>Saldo Emas</strong> :
                                <?php echo number_format($user_data['emas'], 4, ',', '.'); ?> g</p>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="text-danger"><?php echo $message; ?></p>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Penarikan Emas Section -->
                    <form method="POST" action="">
                        <?php if (isset($user_data)) { ?>
                        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
                        <?php } ?>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    </div>
                                    <input type="text" name="jumlah_emas" class="form-control"
                                        placeholder="Jumlah Emas (gram)">
                                </div>
                                <small class="form-text text-muted">Jumlah emas yang ingin ditarik</small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <button type="submit" name="withdraw" class="btn btn-primary w-100">Tarik</button>
                            </div>
                        </div>

                        <!-- Success/Error Message -->
                        <?php if (!empty($message)) { ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p class="text-success"><?php echo $message; ?></p>
                            </div>
                        </div>
                        <?php } ?>
                    </form>
                </div>
                <!-- End of Form Section -->
            </div>
        </div>
        <!-- Akhir Main Content -->
    </div>
</body>

</html>