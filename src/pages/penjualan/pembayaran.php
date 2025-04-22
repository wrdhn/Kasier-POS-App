<?php
// Cek apakah ID penjualan tersedia
if (!isset($_GET['id'])) {
    header("Location: index.php?page=penjualan");
    exit();
}

$penjualan_id = $_GET['id'];

// Ambil data penjualan
$query_penjualan = mysqli_query($connect, "SELECT p.*, pl.NamaPelanggan, pl.NomorTelepon 
                                            FROM penjualan p 
                                            JOIN pelanggan pl ON p.PelangganID = pl.PelangganID 
                                            WHERE p.PenjualanID = '$penjualan_id'");

if (mysqli_num_rows($query_penjualan) == 0) {
    header("Location: index.php?page=penjualan");
    exit();
}

$penjualan = mysqli_fetch_assoc($query_penjualan);

// Ambil detail penjualan
$query_detail = mysqli_query($connect, "SELECT dp.*, pr.NamaProduk, pr.Harga 
                                        FROM detailpenjualan dp 
                                        JOIN produk pr ON dp.ProdukID = pr.ProdukID 
                                        WHERE dp.PenjualanID = '$penjualan_id'");

// Process payment form submission
if (isset($_POST['proses_pembayaran'])) {
    $total_harga = $_POST['total_harga'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $kembalian = $_POST['kembalian'];
    
    // Update status pembayaran jika diperlukan (bisa ditambahkan kolom status di tabel penjualan)
    // mysqli_query($connect, "UPDATE penjualan SET StatusPembayaran = 'Lunas' WHERE PenjualanID = '$penjualan_id'");
    
    // Redirect ke halaman invoice/struk
    echo "<script>
        alert('Pembayaran berhasil diproses!');
        window.location.href = 'index.php?page=struk&id=$penjualan_id';
    </script>";
    exit();
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Proses Pembayaran</h2>
        <a href="index.php?page=penjualan" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Penjualan
        </a>
    </div>
    
    <div class="row">
        <!-- Informasi Penjualan -->
        <div class="col-md-5">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Penjualan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%">ID Penjualan</td>
                            <td width="5%">:</td>
                            <td><strong>#<?= $penjualan_id ?></strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td><?= date('d/m/Y', strtotime($penjualan['TanggalPenjualan'])) ?></td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td>:</td>
                            <td><?= $penjualan['NamaPelanggan'] ?></td>
                        </tr>
                        <tr>
                            <td>No. Telepon</td>
                            <td>:</td>
                            <td><?= $penjualan['NomorTelepon'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Detail Pembelian</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            while ($detail = mysqli_fetch_assoc($query_detail)) : 
                                $total += $detail['Subtotal'];
                            ?>
                                <tr>
                                    <td><?= $detail['NamaProduk'] ?></td>
                                    <td class="text-end">Rp <?= number_format($detail['Harga'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $detail['JumlahProduk'] ?></td>
                                    <td class="text-end">Rp <?= number_format($detail['Subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3">Total</th>
                                <th class="text-end">Rp <?= number_format($penjualan['TotalHarga'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Form Pembayaran -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Proses Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form id="formPembayaran" method="post" action="">
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Total Pembayaran</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control form-control-lg fw-bold bg-light" value="<?= number_format($penjualan['TotalHarga'], 0, ',', '.') ?>" readonly>
                                    <input type="hidden" name="total_harga" id="total_harga" value="<?= $penjualan['TotalHarga'] ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="jumlah_bayar_display" class="col-sm-4 col-form-label">Jumlah Bayar</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control form-control-lg" id="jumlah_bayar_display" placeholder="0" autofocus required>
                                    <input type="hidden" name="jumlah_bayar" id="jumlah_bayar">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Kembalian</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control form-control-lg bg-light" id="kembalian_display" readonly>
                                    <input type="hidden" name="kembalian" id="kembalian">
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Shortcut Buttons -->
                        <div class="d-flex flex-wrap justify-content-center mb-3 gap-2">
                            <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="10000">Rp 10.000</button>
                            <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="20000">Rp 20.000</button>
                            <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="50000">Rp 50.000</button>
                            <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="100000">Rp 100.000</button>
                            <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="200000">Rp 200.000</button>
                            <button type="button" class="btn btn-outline-primary uang-pas-btn">Uang Pas</button>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="proses_pembayaran" class="btn btn-primary btn-lg" id="btn-bayar" disabled>
                                <i class="bi bi-check2-circle me-2"></i> Proses Pembayaran
                            </button>
                            <a href="index.php?page=penjualan" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number to Indonesian Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    
    // Parse Rupiah format to number
    function parseRupiah(rupiahStr) {
        return parseInt(rupiahStr.replace(/\D/g, '')) || 0;
    }
    
    const totalHarga = parseFloat(document.getElementById('total_harga').value);
    const jumlahBayarDisplay = document.getElementById('jumlah_bayar_display');
    const jumlahBayarHidden = document.getElementById('jumlah_bayar');
    const kembalianDisplay = document.getElementById('kembalian_display');
    const kembalianHidden = document.getElementById('kembalian');
    const btnBayar = document.getElementById('btn-bayar');
    
    // Handle Jumlah Bayar Input
    jumlahBayarDisplay.addEventListener('input', function() {
        const value = parseRupiah(this.value);
        
        // Format display value
        this.value = formatRupiah(value);
        
        // Set hidden input value
        jumlahBayarHidden.value = value;
        
        // Calculate kembalian
        const kembalian = value - totalHarga;
        
        kembalianDisplay.value = formatRupiah(Math.max(0, kembalian));
        kembalianHidden.value = Math.max(0, kembalian);
        
        // Enable/disable payment button
        btnBayar.disabled = value < totalHarga;
    });
    
    // Handle Nominal Buttons
    document.querySelectorAll('.nominal-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const nominal = parseInt(this.dataset.nominal);
            const currentValue = parseRupiah(jumlahBayarDisplay.value) || 0;
            
            jumlahBayarDisplay.value = formatRupiah(currentValue + nominal);
            jumlahBayarHidden.value = currentValue + nominal;
            
            // Calculate kembalian
            const kembalian = (currentValue + nominal) - totalHarga;
            
            kembalianDisplay.value = formatRupiah(Math.max(0, kembalian));
            kembalianHidden.value = Math.max(0, kembalian);
            
            // Enable/disable payment button
            btnBayar.disabled = (currentValue + nominal) < totalHarga;
        });
    });
    
    // Handle Uang Pas Button
    document.querySelector('.uang-pas-btn').addEventListener('click', function() {
        jumlahBayarDisplay.value = formatRupiah(totalHarga);
        jumlahBayarHidden.value = totalHarga;
        
        kembalianDisplay.value = formatRupiah(0);
        kembalianHidden.value = 0;
        
        btnBayar.disabled = false;
    });
    
    // Handle Form Submission
    document.getElementById('formPembayaran').addEventListener('submit', function(e) {
        const jumlahBayar = parseFloat(jumlahBayarHidden.value) || 0;
        
        if (jumlahBayar < totalHarga) {
            e.preventDefault();
            alert('Jumlah pembayaran kurang dari total belanja!');
            return false;
        }
    });
    
    // Set keyboard focus to the payment input field when the page loads
    jumlahBayarDisplay.focus();
});
</script>