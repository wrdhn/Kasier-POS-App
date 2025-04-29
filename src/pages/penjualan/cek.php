<?php
if (!isset($_GET['id'])) {
    header("Location: index.php?page=penjualan");
    exit();
}

$penjualan_id = $_GET['id'];

$query_penjualan = mysqli_query($connect, "SELECT p.*, pl.NamaPelanggan, pl.NomorTelepon, pl.Alamat
                                          FROM penjualan p
                                          JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
                                          WHERE p.PenjualanID = '$penjualan_id'");

if (mysqli_num_rows($query_penjualan) == 0) {
    header("Location: index.php?page=penjualan");
    exit();
}

$penjualan = mysqli_fetch_assoc($query_penjualan);

$query_detail = mysqli_query($connect, "SELECT dp.*, pr.NamaProduk, pr.Harga
                                       FROM detailpenjualan dp
                                       JOIN produk pr ON dp.ProdukID = pr.ProdukID
                                       WHERE dp.PenjualanID = '$penjualan_id'");
?>