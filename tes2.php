 <!-- <div class="dropdown-button" onclick="toggleDropdown()">Pilih ID Project</div>
                    <!-- Dropdown Content -->
 <div class="dropdown-content" id="dropdownContent">
     <select name="id" id="idProject">
         <?php
    // Ambil data ID Project dari tabel project
    $conn = mysqli_connect("localhost", "root", "", "db_pm_old"); // Ganti dengan detail koneksi Anda
    $query = "SELECT id FROM data_monitoring";
    $result = mysqli_query($conn, $query);

    // Tampilkan sebagai opsi dropdown
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id]'] . "</option>";

    }

    // Tutup koneksi
    mysqli_close($conn);
    ?> -->