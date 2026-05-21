<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$db   = "harum_laundry"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Terputus: " . mysqli_connect_error());
}
?>