<?php 
session_start(); 
require 'koneksi.php'; 
$_SESSION = [];
session_unset(); 
session_destroy();
header("Location: index.php");
exit(); 
?>