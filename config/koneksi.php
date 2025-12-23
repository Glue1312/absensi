<?php
$hostname	= "localhost"; //bawaan
$username	= "root"; //bawaan
$password	= ""; //kosong
$database	= "absensi"; //nama database yang akan dikoneksikan

$connect	= new mysqli($hostname, $username, $password, $database); //query koneksi
if (!$connect) die("Koneksi gagal");
?>
