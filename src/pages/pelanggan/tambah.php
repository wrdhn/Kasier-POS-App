<?php
if (isset($_POST['submit'])) {
    $query = mysqli_query($connect, "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) VALUES ('" . $_POST['nama_pelanggan'] . "', '" . $_POST['alamat'] . "', '" . $_POST['no_telp'] . "')");
    if ($query) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
        echo "<script>window.location.href='index.php?page=pelanggan';</script>";
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
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required></textarea>
            </div>
            <div class="mb-3">
                <label for="no_telp" class="form-label">Nomor Telepon</label>
                <input type="tel" class="form-control" id="no_telp" name="no_telp" inputmode="number" value="+62 "required>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>