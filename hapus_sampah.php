<?php
require 'fungsi.php';

$id = $_GET["id"];

if (hapusSampah($id) > 0) {
    echo "
            <script>
                alert('Data Berhasil Dihapus');
                document.location.href='sampah.php';
                </script>
            ";
} else {
    echo "
                <script>
                alert('Data Berhasil Gagal Dihapus');
                document.location.href='sampah.php';
                </script>
                ";
}