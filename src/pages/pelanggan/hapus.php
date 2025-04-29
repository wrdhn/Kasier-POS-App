<?php
$query = mysqli_query($connect, "DELETE FROM pelanggan WHERE PelangganID = '" . $_GET['id'] . "'");
if ($query) {
    echo "<script>window.location.href='index.php?page=pelanggan';</script>";
} else {
    echo "<script>alert('Data gagal dihapus');</script>";
}
?>