<?php
session_start();
include 'database.php';
if(!isset($_SESSION['admin_username'])){
    header("location:index.php");
}

?>