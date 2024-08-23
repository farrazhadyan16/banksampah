<?php
// Include the database connection
include 'Koneksi.php';

// Initialize variables
$id_transaksi = '';
$id_sampah = '';
$jumlah_kg = '';
$jumlah_rp = '';
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $id_sampah = $_POST['id_sampah'];
    $jumlah_kg = $_POST['jumlah_kg'];
    $jumlah_rp = $_POST['jumlah_rp'];

    // Insert data into the setor_sampah table
    $query = "INSERT INTO setor_sampah (id_transaksi, id_sampah, jumlah_kg, jumlah_rp) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdd", $id_transaksi, $id_sampah, $jumlah_kg, $jumlah_rp);

    if ($stmt->execute()) {
        $message = "Data berhasil disimpan.";
    } else {
        $message = "Terjadi kesalahan: " . $stmt->error;
    }
}

// Retrieve data for the sampah options
$sampah_query = "SELECT * FROM sampah";
$sampah_result = $conn->query($sampah_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor Sampah</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Setor Sampah</h1>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_transaksi">ID Transaksi:</label>
                <input type="text" name="id_transaksi" id="id_transaksi" required>
            </div>
            <div class="form-group">
                <label for="id_sampah">Jenis Sampah:</label>
                <select name="id_sampah" id="id_sampah" required>
                    <option value="">Pilih Jenis Sampah</option>
                    <?php while ($row = $sampah_result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['jenis']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah_kg">Jumlah (kg):</label>
                <input type="number" name="jumlah_kg" id="jumlah_kg" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="jumlah_rp">Jumlah (Rp):</label>
                <input type="number" name="jumlah_rp" id="jumlah_rp" step="0.01" required>
            </div>
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
