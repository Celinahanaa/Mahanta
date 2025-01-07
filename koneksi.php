<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Connect to the database
$koneksi = mysqli_connect("localhost:8111", "root", "", "mahanta");

// Check connection
if (mysqli_connect_errno()) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}

?>
