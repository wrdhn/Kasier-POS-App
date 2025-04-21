<?php
$query = mysqli_query($connect, "DELETE FROM penjualan WHERE PenjualanID = '" . $_GET['id'] . "'");
if ($query) {
    echo "<script>alert('Data berhasil dihapus');</script>";
    echo "<script>window.location.href='index.php?page=penjualan';</script>";
} else {
    echo "<script>alert('Data gagal dihapus');</script>";
}
?>