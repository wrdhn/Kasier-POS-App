<?php
if (isset($_POST['submit'])) {
    $query = mysqli_query($connect, "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) VALUES ('" . $_POST['tanggal_penjualan'] . "', '" . $_POST['total_harga'] . "', '" . $_POST['pelanggan'] . "')");
    if ($query) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
        echo "<script>window.location.href='index.php?page=penjualan';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');</script>";
    }
}
?>

<div class="container shadow mt-5 rounded-4">
    <form action="" method="post">
        <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
            <h2 class="fw-semibold">Tambah Penjualan</h2>
            <a href="index.php?page=pelanggan" class="btn btn-primary">Kembali</a>
        </div>
        <div class="px-4 py-3">
            <div class="mb-3">
                <label for="tanggal_penjualan" class="form-label">Tanggal Penjualan</label>
                <input type="date" class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" required>
            </div>
            <div class="mb-3">
                <label for="total_harga" class="form-label">Total Harga</label>
                <input type="number" class="form-control" id="total_harga" name="total_harga" required>
            </div>
            <div class="mb-3">
                <label for="pelanggan" class="form-label">Pelanggan</label>
                <select name="pelanggan" id="pelanggan" class="form-select" required>
                    <option value="" disabled selected>Pilih Pelanggan</option>
                    <?php
                    $getPelanggan = mysqli_query($connect, "SELECT * FROM pelanggan");
                    while ($data = mysqli_fetch_array($getPelanggan)) {
                        echo "<option value='" . $data['PelangganID'] . "'>" . $data['NamaPelanggan'] . "</option>";
                    }
                    ?>

                </select>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>