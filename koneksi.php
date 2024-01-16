<?php

$host = "localhost";
$user = "root"; 
$pass = "admin"; 
$dbname = "gis"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


