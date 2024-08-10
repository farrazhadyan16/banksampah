<?php
include 'header.php';
include 'fungsi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//cek apakah tombol sudah ditekan
if(isset($_POST['submit']))
{
    $fileName = $_FILES['excel_data']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    $file_ext = strtolower($file_ext);

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['excel_data']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = "0";
        foreach($data as $row)
        {
            if($count > 0)
            {
                $id = $row[0]; 
                $nomor = $row[1]; 
                $date_pbi = $row[2]; 
                $nama_project = $row[3]; 
                $nama_panel = $row[4]; 
                $pbi = $row[5]; 
                $size_panel_height = $row[6]; 
                $size_panel_width = $row[7]; 
                $size_panel_deep = $row[8];
                $type_panel = $row[9]; 
                $unit = $row[10]; 
                $cell = $row[11]; 
                $target_ppc = $row[12];
                $target_eng = $row[13];  

                $query = "INSERT INTO data_monitoring
                (id,nomor,date_pbi,nama_project,nama_panel,pbi,size_panel_height,size_panel_width,size_panel_deep,type_panel,unit,cell,target_ppc,target_eng)
                VALUES
                ('$id', '$nomor', '$date_pbi', '$nama_project', '$nama_panel', '$pbi', '$size_panel_height', 
                '$size_panel_width', '$size_panel_deep', '$type_panel', '$unit', '$cell', '$target_ppc', '$target_eng')";
                
                $result = mysqli_query($koneksi, $query); 
                $msg = True;
            }
            else
            {
                $count = "1";
            }
        }

        if(isset($msg))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: monitoring.php');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: monitoring.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header('Location: ');
        exit(0);
    }
}
?>