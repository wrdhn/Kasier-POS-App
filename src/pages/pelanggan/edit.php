<?php
    $getData = mysqli_query($connect, "SELECT * FROM pelanggan WHERE PelangganID = '" . $_GET['id'] . "'");
    if (mysqli_num_rows($getData) > 0) {
        $data = mysqli_fetch_array($getData);
    } else {
        echo "<script>alert('Data tidak ditemukan');</script>";
        echo "<script>window.location.href='index.php?page=pelanggan';</script>";
    }
    if (isset($_POST["submit"])) {
    $query = mysqli_query($connect, "UPDATE pelanggan SET NamaPelanggan = '" . $_POST['nama_pelanggan'] . "', Alamat = '" . $_POST['alamat'] . "', NomorTelepon = '" . $_POST['no_telp'] . "' WHERE PelangganID = '" . $_GET['id'] . "'");
    if ($query) {
        echo "<script>alert('Data berhasil diedit');</script>";
        echo "<script>window.location.href='index.php?page=pelanggan';</script>";
    } else {
        echo "<script>alert('Data gagal diedit');</script>";
    }
    }
?>

<div class="container shadow mt-5 rounded-4">
    <form action="" method="post">
        <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
            <h2 class="fw-semibold">Edit Pelanggan</h2>
            <a href="index.php?page=pelanggan" class="btn btn-primary">Kembali</a>
        </div>
        <div class="px-4 py-3">
            <div class="mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?php echo $data['NamaPelanggan']?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" value="<?php echo $data['Alamat']?>" required><?php echo $data['Alamat']?></textarea>
            </div>
            <div class="mb-3">
                <label for="no_telp" class="form-label">Nomor Telepon</label>
                <input type="tel" class="form-control" id="no_telp" name="no_telp" inputmode="number" value="<?php echo $data['NomorTelepon']?>"required>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>