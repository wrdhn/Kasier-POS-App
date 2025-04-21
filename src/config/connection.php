<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'kasir';

$connect = mysqli_connect($host, $user, $password, $db);

if (!$connect) {
    die ("Koneksi Gagal : " .mysqli_connect_error());
}
?>