<?php
$query = mysqli_query($connect, "DELETE FROM user WHERE UserID = '" . $_GET['id'] . "'");
if ($query) {
    echo "<script>window.location.href='index.php?page=pengguna';</script>";
} else {
    echo "<script>alert('Data gagal dihapus');</script>";
}
?>