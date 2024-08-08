<?php
    require 'fungsi.php';

    
    $id = $_GET["id_konsesi"];

    if(hapusKonsesi($id)>0){
        echo "
            <script>
                alert('Data Berhasil Dihapus');
                document.location.href='konsesi.php';
                </script>
            ";
        }
        else{
            echo "
                <script>
                alert('Data Berhasil Dihapus');
                document.location.href='konsesi.php';
                </script>
                ";
        }
?>