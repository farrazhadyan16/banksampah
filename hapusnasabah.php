<?php
require 'fungsi.php';

$id = $_GET["id"];

if (hapusNasabah($id) > 0) {
    echo "
            <script>
                alert('Data Berhasil Dihapus');
                document.location.href='nasabah.php';
                </script>
            ";
} else {
    echo "
                <script>
                alert('Data Berhasil Dihapus');
                document.location.href='nasabah.php';
                </script>
                ";
}
