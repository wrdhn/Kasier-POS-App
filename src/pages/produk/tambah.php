<?php
if (isset($_POST['submit'])) {
    $query = mysqli_query($connect, "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES ('" . $_POST['nama_produk'] . "', '" . $_POST['harga'] . "', '" . $_POST['stok'] . "')");
    if ($query) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
        echo "<script>window.location.href='index.php?page=produk';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');</script>";
    }
}
?>

<div class="container shadow mt-5 rounded-4">
    <form action="" method="post">
        <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
            <h2 class="fw-semibold">Tambah Produk</h2>
            <a href="index.php?page=produk" class="btn btn-primary">Kembali</a>
        </div>
        <div class="px-4 py-3">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" required>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>