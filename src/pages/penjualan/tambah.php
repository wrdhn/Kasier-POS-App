<?php
$query_pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");

$query_produk = mysqli_query($connect, "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");

if (isset($_POST['submit'])) {
    $pelanggan_id = $_POST['pelanggan_id'];
    $tanggal_penjualan = $_POST['tanggal_penjualan'];
    $total_harga = $_POST['total_harga'];
    
    $query_insert_penjualan = mysqli_query($connect, 
        "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
         VALUES ('$tanggal_penjualan', '$total_harga', '$pelanggan_id')"
    );
    
    if ($query_insert_penjualan) {
        $penjualan_id = mysqli_insert_id($connect);
        
        $produk_ids = $_POST['produk_id'];
        $jumlah_produk = $_POST['jumlah'];
        $subtotals = $_POST['subtotal'];
        
        $success = true;
        
        for ($i = 0; $i < count($produk_ids); $i++) {
            if (!empty($produk_ids[$i]) && $jumlah_produk[$i] > 0) {
                $produk_id = $produk_ids[$i];
                $jumlah = $jumlah_produk[$i];
                $subtotal = $subtotals[$i];
                
                $query_insert_detail = mysqli_query($connect, 
                    "INSERT INTO detail_penjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                     VALUES ('$penjualan_id', '$produk_id', '$jumlah', '$subtotal')"
                );
                
                $query_update_stok = mysqli_query($connect, 
                    "UPDATE produk SET Stok = Stok - $jumlah 
                     WHERE ProdukID = '$produk_id'"
                );
                
                if (!$query_insert_detail || !$query_update_stok) {
                    $success = false;
                    break;
                }
            }
        }
        
        if ($success) {
            echo "<script>
                    alert('Penjualan berhasil ditambahkan');
                    window.location.href = 'index.php?page=penjualan';
                  </script>";
        } else {
            echo "<script>alert('Gagal menambahkan detail penjualan');</script>";
        }
    } else {
        echo "<script>alert('Gagal menambahkan penjualan: " . mysqli_error($connect) . "');</script>";
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between">
        <h2 class="mb-4">Tambah Penjualan Baru</h2>
        <div>
            <a href="index.php?page=penjualan" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

        </div>
    </div>
    
    <form method="post" action="" id="formPenjualan">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Informasi Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pelanggan_id" class="form-label">Pelanggan</label>
                        <select class="form-select" name="pelanggan_id" id="pelanggan_id" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php while ($pelanggan = mysqli_fetch_assoc($query_pelanggan)) : ?>
                                <option value="<?= $pelanggan['PelangganID'] ?>"><?= $pelanggan['NamaPelanggan'] ?> - <?= $pelanggan['NomorTelepon'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_penjualan" class="form-label">Tanggal Penjualan</label>
                        <input type="date" class="form-control" name="tanggal_penjualan" id="tanggal_penjualan" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Detail Produk</h5>
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahProduk">
                    <i class="bi bi-plus"></i> Tambah Produk
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableProduk">
                        <thead class="table-light">
                            <tr>
                                <th width="40%">Produk</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">Subtotal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="produkContainer">
                            <tr class="produk-row">
                                <td>
                                    <select class="form-select produk-select" name="produk_id[]" required>
                                        <option value="">-- Pilih Produk --</option>
                                        <?php 
                                        // Reset pointer untuk mengulang hasil query
                                        mysqli_data_seek($query_produk, 0);
                                        while ($produk = mysqli_fetch_assoc($query_produk)) : 
                                        ?>
                                            <option value="<?= $produk['ProdukID'] ?>" 
                                                    data-harga="<?= $produk['Harga'] ?>"
                                                    data-stok="<?= $produk['Stok'] ?>">
                                                <?= $produk['NamaProduk'] ?> (Stok: <?= $produk['Stok'] ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control harga-produk" readonly>
                                    <input type="hidden" name="harga[]" class="harga-hidden">
                                </td>
                                <td>
                                    <input type="number" class="form-control jumlah-produk" name="jumlah[]" min="1" value="1" required>
                                    <small class="text-muted stok-info"></small>
                                </td>
                                <td>
                                    <input type="text" class="form-control subtotal-display" readonly>
                                    <input type="hidden" name="subtotal[]" class="subtotal-hidden">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm hapus-produk">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Total Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" id="total_display" readonly>
                                <input type="hidden" name="total_harga" id="total_harga" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end mb-4">
            <a href="index.php?page=penjualan" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" name="submit" class="btn btn-primary">Simpan Penjualan</button>
        </div>
    </form>
</div>

<script src="./assets/js/tambahPenjualan.js"></script>
