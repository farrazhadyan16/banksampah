<?php
include 'header.php';

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

            // Ambil harga emas terkini
            $current_gold_price_sell = getCurrentGoldPricesell();

             // Hitung jumlah emas yang setara dengan saldo uang
             $gold_equivalent = $user_data['emas'] * $current_gold_price_sell;
             
        } else {
            $message = "User dengan role 'Nasabah' tidak ditemukan.";
        }
    }
}

?>

<!-- Search Section -->
<form method="POST" action="">
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" name="search_value" id="search_value" class="form-control" placeholder="Search by NIK"
                maxlength="16" oninput="validateNIK(this)"
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
    </div>
    <div class="col-md-5">
        <p><strong>Username</strong> : <?php echo $user_data['username']; ?></p>

        <p><strong>Nama Lengkap</strong> : <?php echo $user_data['nama']; ?></p>
        <p><strong>Saldo</strong> :
            <?php echo number_format($user_data['emas'], 4, '.', '.'); ?> g =
            Rp. <?php echo round($gold_equivalent, 2); ?>
        </p>
        <!-- <p><strong>Saldo Emas</strong> :
            <?php echo number_format($user_data['emas'], 4, ',', '.'); ?> g</p> -->
    </div>
</div>
<?php } else { ?>
<div class="row mb-4">
    <div class="col-md-12">
        <p class="text-danger"><?php echo $message; ?></p>
    </div>
</div>
<?php } ?>
<?php if (isset($user_data)) { ?>
<input type="hidden" name="id_user" value="<?php echo $user_data['id']; ?>">
<?php } ?>
<div class="row mb-4">
    <div class="col-md-4">
        <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" disabled>
    </div>
    <div class="col-md-4">
        <?php
                                // Set zona waktu ke WIB (UTC+7)
                                date_default_timezone_set('Asia/Jakarta');
                                $current_time = date('H:i');
                                ?>
        <input type="time" name="waktu" class="form-control" value="<?php echo $current_time; ?>" disabled>
    </div>
</div>
<script>
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally

    var formData = new FormData(this);

    fetch('search_nik.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var userInfoDiv = document.getElementById('userInfo');
            userInfoDiv.innerHTML = ''; // Clear previous user info

            if (data.error) {
                userInfoDiv.innerHTML = `<p class="text-danger">${data.error}</p>`;
            } else {
                var userData = data.data;
                userInfoDiv.innerHTML = `
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <p><strong>ID</strong>: ${userData.id}</p>
                            <p><strong>NIK</strong>: ${userData.nik}</p>
                            <p><strong>Email</strong>: ${userData.email}</p>
                            <p><strong>Username</strong>: ${userData.username}</p>
                        </div>
                        <div class="col-md-5">
                            <p><strong>Nama Lengkap</strong>: ${userData.nama}</p>
                            <p><strong>Saldo Uang</strong>: Rp. ${parseFloat(userData.uang).toLocaleString('id-ID')}</p>
                            <p><strong>Saldo Emas</strong>: ${parseFloat(userData.emas).toFixed(4)} g</p>
                        </div>
                    </div>`;
                document.querySelector('input[name="id_user"]').value = userData
                    .id; // Set hidden id_user input
            }
        })
        .catch(error => console.error('Error:', error));
});
</script>