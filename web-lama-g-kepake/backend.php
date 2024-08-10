<?php

include 'database.php';


$functionName = htmlspecialchars($_GET['functionName']);

    switch ($functionName){
        case 'getData':
            getData();
            break;
        
        case 'getDataLain':
            getDataLain();
            break;

        default:
            break;    
    }

    function getData(){

        global $koneksi;
        $data=[];
        $query = mysqli_query($koneksi, "SELECT * from data_monitoring");

        while($row = mysqli_fetch_assoc($query)){
            $data[]=$row; 
        }
        

        echo json_encode($data);
    }