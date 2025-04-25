<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE PelangganID = '$id'");
    $data = mysqli_fetch_assoc($query);
    
    if (!$data) {
        echo "<script>alert('Data pelanggan tidak ditemukan'); window.location.href='index.php?page=pelanggan';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=pelanggan';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($connect, $_POST['nama_pelanggan']);
    $alamat = mysqli_real_escape_string($connect, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($connect, $_POST['nomor_telepon']);

    $query = mysqli_query($connect, "UPDATE pelanggan SET NamaPelanggan = '$nama', Alamat = '$alamat', NomorTelepon = '$telepon' WHERE PelangganID = '$id'");
    
    if ($query) {
        echo "<script>alert('Data pelanggan berhasil diperbarui'); window.location.href='index.php?page=pelanggan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($connect) . "');</script>";
    }
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Pelanggan</h5>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= htmlspecialchars($data['NamaPelanggan']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($data['Alamat']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                    <input type="number" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?= htmlspecialchars($data['NomorTelepon']) ?>" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index.php?page=pelanggan" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>