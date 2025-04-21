<?php
    $getData = mysqli_query($connect, "SELECT * FROM produk WHERE ProdukID = '" . $_GET['id'] . "'");
    if (mysqli_num_rows($getData) > 0) {
        $data = mysqli_fetch_array($getData);
    } else {
        echo "<script>alert('Data tidak ditemukan');</script>";
        echo "<script>window.location.href='index.php?page=produk';</script>";
    }
    if (isset($_POST["submit"])) {
    $query = mysqli_query($connect, "UPDATE produk SET NamaProduk = '" . $_POST['nama_produk'] . "', Harga = '" . $_POST['harga'] . "', Stok = '" . $_POST['stok'] . "' WHERE ProdukID = '" . $_GET['id'] . "'");
    if ($query) {
        echo "<script>alert('Data berhasil diedit');</script>";
        echo "<script>window.location.href='index.php?page=produk';</script>";
    } else {
        echo "<script>alert('Data gagal diedit');</script>";
    }
    }
?>

<div class="container shadow mt-5">
    <form action="" method="post">
        <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
            <h2 class="fw-semibold">Edit Produk</h2>
            <a href="index.php?page=produk" class="btn btn-primary">Kembali</a>
        </div>
        <div class="px-4 py-3">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo $data['NamaProduk']?>" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $data['Harga']?>" required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $data['Stok']?>" required>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>