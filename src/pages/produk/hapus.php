<?php
$query = mysqli_query($connect, "DELETE FROM produk WHERE ProdukID = '" . $_GET['id'] . "'");
if ($query) {
    echo "<script>window.location.href='index.php?page=produk';</script>";
} else {
    echo "<script>alert('Data gagal dihapus');</script>";
}
?>