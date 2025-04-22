<?php
// Cek apakah ID penjualan tersedia
if (!isset($_GET['id'])) {
    header("Location: index.php?page=penjualan");
    exit();
}

$penjualan_id = $_GET['id'];

// Ambil data penjualan
$query_penjualan = mysqli_query($connect, "SELECT p.*, pl.NamaPelanggan, pl.NomorTelepon, pl.Alamat 
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
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Struk Pembayaran</h2>
        <div>
            <button onclick="printReceipt()" class="btn btn-primary me-2">
                <i class="bi bi-printer"></i> Cetak Struk
            </button>
            <a href="index.php?page=penjualan" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="receipt" class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="mb-1">Kasier (gaa punya toko)</h3>
                        <p class="mb-1">Jl. Kebenaran No. 123, Kota Yang ditinggalkan</p>
                        <p>Telp: 0882-1606-6403</p>
                        <hr>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-1"><strong>No. Struk:</strong> #<?= $penjualan_id ?></p>
                            <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($penjualan['TanggalPenjualan'])) ?></p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1"><strong>Pelanggan:</strong> <?= $penjualan['NamaPelanggan'] ?></p>
                            <p><strong>Telp:</strong> <?= $penjualan['NomorTelepon'] ?></p>
                        </div>
                    </div>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
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
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end">Rp <?= number_format($penjualan['TotalHarga'], 0, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="mb-1">Terima kasih atas kunjungan Anda</p>
                        <p>Selamat berbelanja kembali!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printReceipt() {
    const printContents = document.getElementById('receipt').innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="width: 80mm; margin: 0 auto; font-family: 'Arial', sans-serif; font-size: 12px;">
            ${printContents}
        </div>`;
    
    window.print();
    document.body.innerHTML = originalContents;
}
</script>