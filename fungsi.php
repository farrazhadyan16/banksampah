<?php
$conn = mysqli_connect("localhost", "root", "", "db_pm_old");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

//detail_user.php
function checkSession() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}

function getUserData($koneksi, $username) {
    $query = "SELECT username, nama, role FROM user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        die("Error fetching data: " . mysqli_error($koneksi));
    }
}

//hapus user
function hapusUser($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM user WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

//edit data nasabah/admin
function updateDataUser($data)
{
    global $conn;
    $id = htmlspecialchars($data["id"]);
    $username = htmlspecialchars($data["username"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $notelp = htmlspecialchars($data["notelp"]);
    $nik = htmlspecialchars($data["nik"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $tgl_lahir = htmlspecialchars($data["tgl_lahir"]);
    $kelamin = htmlspecialchars($data["kelamin"]);

    $query = "UPDATE user SET username='$username',nama='$nama',email='$email',notelp='$notelp',nik='$nik',alamat='$alamat',tgl_lahir='$tgl_lahir',kelamin='$kelamin' WHERE id='$id'";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function getUserById($id) {
    $query = "SELECT * FROM user WHERE id=$id";
    return query($query)[0];
}

function hapususerById($id) {
    return hapususer($id);
}

//admin.php
function getAdmin($search_nik = null) {
    if ($search_nik) {
        return query("SELECT * FROM user WHERE role in ('admin','superadmin') AND nik LIKE '%$search_nik%' ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");
    } else {
        return query("SELECT * FROM user WHERE role in ('admin','superadmin') ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");
    }
}

//nasabah.php (cari nik dan hapus nasabah)
function getNasabah($search_nik = null) {
    if ($search_nik) {
        return query("SELECT * FROM user WHERE role = 'nasabah' AND nik LIKE '%$search_nik%' ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");
    } else {
        return query("SELECT * FROM user WHERE role = 'nasabah' ORDER BY LENGTH(id), CAST(id AS UNSIGNED)");
    }
}

//edit_nasabah.php
// Function to get user data by ID
// Function to handle the form submission
function handleNasabahUpdate($postData) {
    if (updateDataUser($postData) > 0) {
        echo "
            <script>  
                alert('Data Berhasil Diperbarui');
                document.location.href ='nasabah.php';
            </script>
        ";
    } else {
        echo "
            <script>  
                alert('Data Gagal Diperbarui');
                document.location.href ='nasabah.php';
            </script>
        ";
    }
}

//edit_admin.php
function handleAdminUpdate($postData) {
    if (updateDataUser($postData) > 0) {
        echo "
            <script>  
                alert('Data Berhasil Diperbarui');
                document.location.href ='admin.php';
            </script>
        ";
    } else {
        echo "
            <script>  
                alert('Data Gagal Diperbarui');
                document.location.href ='admin.php';
            </script>
        ";
    }
}



//hapus sampah
function hapusSampah($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM sampah WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

//edit sampah
function updatedatasampah($data)
{
    global $conn;
    $id = htmlspecialchars($data["id"]);
    $id_kategori = htmlspecialchars($data["id_kategori"]);
    $jenis = htmlspecialchars($data["jenis"]);
    $harga = htmlspecialchars($data["harga"]);
    $harga_pusat = htmlspecialchars($data["harga_pusat"]);
    $jumlah = htmlspecialchars($data["jumlah"]);

    $query = "UPDATE sampah SET id_kategori='$id_kategori',jenis='$jenis',harga='$harga',harga_pusat='$harga_pusat',jumlah='$jumlah' WHERE id='$id'";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function addCategory($name)
{
    global $conn;

    // Query untuk mendapatkan ID terakhir yang dimulai dengan 'KS'
    $lastId = query("SELECT id FROM kategori_sampah WHERE id LIKE 'KS%' ORDER BY id DESC LIMIT 1");
    if ($lastId) {
        // Ambil angka dari ID terakhir, tambahkan 1, dan format ulang ID baru
        $newId = 'KS' . str_pad((int) substr($lastId[0]['id'], 2) + 1, 2, '0', STR_PAD_LEFT);
    } else {
        // Jika tidak ada ID sebelumnya, mulai dengan KS01
        $newId = 'KS01';
    }

    $query = "INSERT INTO kategori_sampah (id, name) VALUES ('$newId', '$name')";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function deleteCategory($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM kategori_sampah WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

//tambah_sampah.php
function addWaste($jenis, $harga_pengepul, $harga_nasabah, $id_kategori)
{
    global $conn;

    // Query untuk mendapatkan ID terakhir yang dimulai dengan 'S'
    $lastId = query("SELECT id FROM sampah WHERE id LIKE 'S%' ORDER BY id DESC LIMIT 1");
    if ($lastId) {
        // Ambil angka dari ID terakhir, tambahkan 1, dan format ulang ID baru
        $newId = 'S' . str_pad((int) substr($lastId[0]['id'], 1) + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // Jika tidak ada ID sebelumnya, mulai dengan S001
        $newId = 'S001';
    }

    $query = "INSERT INTO sampah (id, jenis, harga, harga_pusat, id_kategori) 
              VALUES ('$newId', '$jenis', '$harga_pengepul', '$harga_nasabah', '$id_kategori')";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}
function getCategories() {
    return query("SELECT * FROM kategori_sampah ORDER BY name ASC");
}

function handleAddWaste($postData) {
    $jenis = $postData['jenis'];
    $harga_pengepul = $postData['harga_pengepul'];
    $harga_nasabah = $postData['harga_nasabah'];
    $kategori = $postData['kategori'];

    if (addWaste($jenis, $harga_pengepul, $harga_nasabah, $kategori) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan');
                document.location.href = 'sampah.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan');
              </script>";
    }
}

//sampah.php
function getSampahData() {
    return query("
        SELECT 
            sampah.id, 
            kategori_sampah.name AS kategori_name, 
            sampah.jenis, 
            sampah.harga, 
            sampah.harga_pusat, 
            sampah.jumlah 
        FROM sampah 
        JOIN kategori_sampah ON sampah.id_kategori = kategori_sampah.id 
        ORDER BY LENGTH(sampah.id), CAST(sampah.id AS UNSIGNED)
    ");
}

//get api emas untuk konvert ke emas
// Function to get the current gold price from the API
function getCurrentGoldPricebuy() {
    $api_url = "https://logam-mulia-api.vercel.app/prices/sakumas";
    $response = file_get_contents($api_url);
    $gold_data = json_decode($response, true);
    return $gold_data['data'][0]['buy']; // Harga beli emas per gram dalam IDR
}

//get api emas untuk konvert ke rupiah
// Function to get the current gold price from the API
function getCurrentGoldPricesell() {
    $api_url = "https://logam-mulia-api.vercel.app/prices/sakumas";
    $response = file_get_contents($api_url);
    $gold_data = json_decode($response, true);
    return $gold_data['data'][0]['sell']; // Harga beli emas per gram dalam IDR
}
//konversi duit ke emas
// Function to convert money to gold and update the user's wallet
function convertMoneyToGold($user_id, $jumlah_uang, $current_gold_price) {
    global $conn;
    $jumlah_emas = $jumlah_uang / $current_gold_price;

    $update_query = "UPDATE dompet 
                     SET uang = uang - $jumlah_uang, emas = emas + $jumlah_emas 
                     WHERE id_user = $user_id";
    return $conn->query($update_query);
}

//konversi emas ke duit
// Function to convert money to gold and update the user's wallet
function convertGoldToMoney($user_id, $jumlah_emas, $current_gold_price) {
    global $conn;
    $jumlah_uang = $jumlah_emas * $current_gold_price;

    $update_query = "UPDATE dompet 
                     SET uang = uang + $jumlah_uang, emas = emas - $jumlah_emas 
                     WHERE id_user = $user_id";
    return $conn->query($update_query);
}

// Fungsi untuk menarik uang
// Fungsi untuk menarik uang
function withdrawMoney($id_user, $jumlah_uang) {
    global $conn;

    // Cek saldo pengguna dari tabel dompet
    $query = "SELECT uang FROM dompet WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Penanganan kesalahan
    }
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Validasi saldo uang
    if ($user['uang'] < $jumlah_uang) {
        return false; // Saldo tidak cukup
    }

    // Kurangi saldo uang pengguna
    $new_saldo = $user['uang'] - $jumlah_uang;
    $update_query = "UPDATE dompet SET uang = ? WHERE id_user = ?";
    $update_stmt = $conn->prepare($update_query);
    if (!$update_stmt) {
        die("Prepare failed: " . $conn->error); // Penanganan kesalahan
    }
    $update_stmt->bind_param("di", $new_saldo, $id_user);

    return $update_stmt->execute();
}

// Fungsi untuk menarik emas
function withdrawGold($id_user, $jumlah_emas) {
    global $conn;

    // Cek saldo pengguna dari tabel dompet
    $query = "SELECT emas FROM dompet WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Penanganan kesalahan
    }
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Validasi saldo emas
    if ($user['emas'] < $jumlah_emas) {
        return false; // Saldo tidak cukup
    }

    // Kurangi saldo emas pengguna
    $new_saldo = $user['emas'] - $jumlah_emas;
    $update_query = "UPDATE dompet SET emas = ? WHERE id_user = ?";
    $update_stmt = $conn->prepare($update_query);
    if (!$update_stmt) {
        die("Prepare failed: " . $conn->error); // Penanganan kesalahan
    }
    $update_stmt->bind_param("di", $new_saldo, $id_user);

    return $update_stmt->execute();
}