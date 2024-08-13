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

function getProjectIds()
{
    global $conn;

    $query = "SELECT id FROM sampah"; // Replace 'your_project_table' with the actual table name
    $result = mysqli_query($conn, $query);

    $projectIds = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $projectIds[] = $row['id'];
    }

    return $projectIds;
}


//tesss kategori
// function addCategory($name)
// {
//     global $koneksi;
//     $query = "INSERT INTO kategori_sampah (name, created_at) VALUES (?, NOW())";
//     $stmt = mysqli_prepare($koneksi, $query);
//     mysqli_stmt_bind_param($stmt, "s", $name);
//     mysqli_stmt_execute($stmt);
//     return mysqli_stmt_affected_rows($stmt);
// }

// function deleteCategory($id)
// {
//     global $koneksi;
//     $query = "DELETE FROM kategori_sampah WHERE id = ?";
//     $stmt = mysqli_prepare($koneksi, $query);
//     mysqli_stmt_bind_param($stmt, "i", $id);
//     mysqli_stmt_execute($stmt);
//     return mysqli_stmt_affected_rows($stmt);
// }






//hapus nasabah/admin
function hapusNasabah($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM user WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

//hapus sampah
function hapusSampah($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM sampah WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

//edit data nasabah/admin
function updatedatanasabah($data)
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