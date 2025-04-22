<?php
// tambah_penjualan.php
$query_pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");
$query_produk = mysqli_query($connect, "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");

if (isset($_POST['submit'])) {
    $pelanggan_id = $_POST['pelanggan_id'];
    $tanggal_penjualan = $_POST['tanggal_penjualan'];
    $total_harga = $_POST['total_harga'];
    
    mysqli_begin_transaction($connect);
    
    try {
        // Insert into penjualan table
        $query_insert_penjualan = mysqli_query($connect, 
            "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
             VALUES ('$tanggal_penjualan', '$total_harga', '$pelanggan_id')"
        );
        
        if (!$query_insert_penjualan) {
            throw new Exception("Gagal menambahkan penjualan: " . mysqli_error($connect));
        }
        
        $penjualan_id = mysqli_insert_id($connect);
        
        $produk_ids = $_POST['produk_id'];
        $jumlah_produk = $_POST['jumlah'];
        $subtotals = $_POST['subtotal'];
        
        for ($i = 0; $i < count($produk_ids); $i++) {
            if (!empty($produk_ids[$i]) && $jumlah_produk[$i] > 0) {
                $produk_id = $produk_ids[$i];
                $jumlah = $jumlah_produk[$i];
                $subtotal = $subtotals[$i];
                
                // Insert into detailpenjualan table
                $query_insert_detail = mysqli_query($connect, 
                    "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                     VALUES ('$penjualan_id', '$produk_id', '$jumlah', '$subtotal')"
                );
                
                if (!$query_insert_detail) {
                    throw new Exception("Gagal menambahkan detail penjualan: " . mysqli_error($connect));
                }
                
                // Update stok produk
                $query_update_stok = mysqli_query($connect, 
                    "UPDATE produk SET Stok = Stok - $jumlah 
                     WHERE ProdukID = '$produk_id'"
                );
                
                if (!$query_update_stok) {
                    throw new Exception("Gagal mengupdate stok: " . mysqli_error($connect));
                }
            }
        }
        
        mysqli_commit($connect);
        
        // Redirect ke halaman pembayaran
        header("Location: index.php?page=pembayaran&id=$penjualan_id");
        exit();
        
    } catch (Exception $e) {
        mysqli_rollback($connect);
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Tambah Penjualan Baru</h2>
        <a href="index.php?page=penjualan" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <form method="post" action="" id="formPenjualan">
        <!-- Informasi Penjualan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pelanggan</label>
                            <select class="form-select" name="pelanggan_id" id="pelanggan_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php while ($pelanggan = mysqli_fetch_assoc($query_pelanggan)) : ?>
                                    <option value="<?= $pelanggan['PelangganID'] ?>">
                                        <?= $pelanggan['NamaPelanggan'] ?> - <?= $pelanggan['NomorTelepon'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_penjualan" class="form-label">Tanggal Penjualan</label>
                            <input type="date" class="form-control" name="tanggal_penjualan" id="tanggal_penjualan" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Produk -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Produk</h5>
                <button type="button" class="btn btn-primary btn-sm" id="btnTambahProduk">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
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
                                    <button type="button" class="btn btn-danger btn-sm hapus-produk">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Total Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total Harga</label>
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
            <button type="submit" name="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded');
    
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    
    function hitungSubtotal(row) {
        const harga = parseFloat(row.querySelector('.harga-hidden').value) || 0;
        const jumlah = parseInt(row.querySelector('.jumlah-produk').value) || 0;
        const subtotal = harga * jumlah;
        
        row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
        row.querySelector('.subtotal-hidden').value = subtotal;
        
        hitungTotal();
    }
    
    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-hidden').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        document.getElementById('total_display').value = formatRupiah(total);
        document.getElementById('total_harga').value = total;
    }
    
    function handleProdukSelect(select) {
        const row = select.closest('.produk-row');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            const harga = option.dataset.harga;
            const stok = option.dataset.stok;
            
            row.querySelector('.harga-produk').value = formatRupiah(harga);
            row.querySelector('.harga-hidden').value = harga;
            row.querySelector('.stok-info').textContent = `Tersedia: ${stok}`;
            
            const jumlahInput = row.querySelector('.jumlah-produk');
            jumlahInput.max = stok;
            
            if (parseInt(jumlahInput.value) > parseInt(stok)) {
                jumlahInput.value = stok;
            }
            
            hitungSubtotal(row);
        } else {
            row.querySelector('.harga-produk').value = '';
            row.querySelector('.harga-hidden').value = '';
            row.querySelector('.stok-info').textContent = '';
            row.querySelector('.subtotal-display').value = '';
            row.querySelector('.subtotal-hidden').value = '';
            hitungTotal();
        }
    }
    
    // Event listener untuk produk select yang sudah ada
    document.querySelectorAll('.produk-select').forEach(select => {
        select.addEventListener('change', function() {
            handleProdukSelect(this);
        });
    });
    
    // Event listener untuk jumlah produk yang sudah ada
    document.querySelectorAll('.jumlah-produk').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.produk-row');
            const stok = parseInt(row.querySelector('.produk-select').selectedOptions[0]?.dataset.stok || 0);
            
            if (parseInt(this.value) > stok) {
                this.value = stok;
                alert(`Stok maksimal: ${stok}`);
            }
            
            hitungSubtotal(row);
        });
    });
    
    // Event listener untuk hapus produk yang sudah ada
    document.querySelectorAll('.hapus-produk').forEach(button => {
        button.addEventListener('click', function() {
            if (document.querySelectorAll('.produk-row').length > 1) {
                this.closest('.produk-row').remove();
                hitungTotal();
            } else {
                alert('Minimal harus ada 1 produk!');
            }
        });
    });
    
    // Tambah produk baru
    const btnTambahProduk = document.getElementById('btnTambahProduk');
    if (btnTambahProduk) {
        btnTambahProduk.addEventListener('click', function() {
            const container = document.getElementById('produkContainer');
            const firstRow = document.querySelector('.produk-row');
            const newRow = firstRow.cloneNode(true);
            
            // Reset values di row baru
            newRow.querySelector('.produk-select').selectedIndex = 0;
            newRow.querySelector('.harga-produk').value = '';
            newRow.querySelector('.harga-hidden').value = '';
            newRow.querySelector('.jumlah-produk').value = 1;
            newRow.querySelector('.subtotal-display').value = '';
            newRow.querySelector('.subtotal-hidden').value = '';
            newRow.querySelector('.stok-info').textContent = '';
            
            // Add event listeners ke row baru
            newRow.querySelector('.produk-select').addEventListener('change', function() {
                handleProdukSelect(this);
            });
            
            newRow.querySelector('.jumlah-produk').addEventListener('input', function() {
                const row = this.closest('.produk-row');
                const stok = parseInt(row.querySelector('.produk-select').selectedOptions[0]?.dataset.stok || 0);
                
                if (parseInt(this.value) > stok) {
                    this.value = stok;
                    alert(`Stok maksimal: ${stok}`);
                }
                
                hitungSubtotal(row);
            });
            
            newRow.querySelector('.hapus-produk').addEventListener('click', function() {
                if (document.querySelectorAll('.produk-row').length > 1) {
                    this.closest('.produk-row').remove();
                    hitungTotal();
                } else {
                    alert('Minimal harus ada 1 produk!');
                }
            });
            
            container.appendChild(newRow);
        });
    }
    
    // Validasi form sebelum submit
    const form = document.getElementById('formPenjualan');
    if (form) {
        form.addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.produk-row');
            const total = parseFloat(document.getElementById('total_harga').value) || 0;
            
            if (rows.length === 0) {
                e.preventDefault();
                alert('Harap tambahkan minimal 1 produk!');
                return;
            }
            
            if (total <= 0) {
                e.preventDefault();
                alert('Total harga harus lebih dari 0!');
                return;
            }
            
            // Cek produk duplikat
            const productIds = [];
            let hasDuplicate = false;
            
            rows.forEach(row => {
                const select = row.querySelector('.produk-select');
                const productId = select.value;
                
                if (productId && productIds.includes(productId)) {
                    hasDuplicate = true;
                } else if (productId) {
                    productIds.push(productId);
                }
            });
            
            if (hasDuplicate) {
                e.preventDefault();
                alert('Terdapat produk duplikat! Harap gabungkan jumlahnya dalam satu baris.');
                return;
            }
        });
    }
});
</script>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">