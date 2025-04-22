<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $query = mysqli_query($connect, "SELECT * FROM produk WHERE ProdukID = '$id'");
    $data = mysqli_fetch_assoc($query);
    
    if (!$data) {
        echo "<script>alert('Data produk tidak ditemukan'); window.location.href='index.php?page=produk';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=produk';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $nama_produk = mysqli_real_escape_string($connect, $_POST['nama_produk']);
    $harga = mysqli_real_escape_string($connect, $_POST['harga']);
    $stok = mysqli_real_escape_string($connect, $_POST['stok']);

    $query = mysqli_query($connect, "UPDATE produk SET NamaProduk = '$nama_produk', Harga = '$harga', Stok = '$stok' WHERE ProdukID = '$id'");
    
    if ($query) {
        echo "<script>alert('Data produk berhasil diperbarui'); window.location.href='index.php?page=produk';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($connect) . "');</script>";
    }
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Produk</h5>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($data['NamaProduk']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($data['Harga']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= htmlspecialchars($data['Stok']) ?>" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index.php?page=produk" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>