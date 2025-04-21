<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>
            alert('ID Penjualan tidak valid');
            window.location.href = 'index.php?page=penjualan';
          </script>";
    exit;
}

$id_penjualan = $_GET['id'];

$query_penjualan = mysqli_query($connect, 
    "SELECT p.*, pel.NamaPelanggan, pel.Alamat, pel.NomorTelepon 
     FROM penjualan p
     JOIN pelanggan pel ON p.PelangganID = pel.PelangganID
     WHERE p.PenjualanID = '$id_penjualan'"
);

if (!$query_penjualan || mysqli_num_rows($query_penjualan) == 0) {
    echo "<script>
            alert('Data penjualan tidak ditemukan');
            window.location.href = 'index.php?page=penjualan';
          </script>";
    exit;
}

$penjualan = mysqli_fetch_assoc($query_penjualan);

$query_detail = mysqli_query($connect, 
    "SELECT dp.*, p.NamaProduk, p.Harga
     FROM detail_penjualan dp
     JOIN produk p ON dp.ProdukID = p.ProdukID
     WHERE dp.PenjualanID = '$id_penjualan'"
);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Penjualan #<?= $id_penjualan ?></h2>
        <div>
            <a href="index.php?page=penjualan" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Informasi Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">ID Penjualan</th>
                            <td>: <?= $penjualan['PenjualanID'] ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: <?= date('d/m/Y', strtotime($penjualan['TanggalPenjualan'])) ?></td>
                        </tr>
                        <tr>
                            <th>Total Harga</th>
                            <td>: Rp <?= number_format($penjualan['TotalHarga'], 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Pelanggan</th>
                            <td>: <?= $penjualan['NamaPelanggan'] ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: <?= $penjualan['Alamat'] ?></td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: <?= $penjualan['NomorTelepon'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Detail Produk</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Produk</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="15%">Jumlah</th>
                            <th width="25%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $total = 0;
                        while ($detail = mysqli_fetch_assoc($query_detail)) : 
                            $subtotal = $detail['Subtotal'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $detail['NamaProduk'] ?></td>
                            <td class="text-end">Rp <?= number_format($detail['Harga'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= $detail['JumlahProduk'] ?></td>
                            <td class="text-end">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style type="text/css" media="print">
    @page {
        size: auto;
        margin: 10mm;
    }
    
    body {
        margin: 0;
        padding: 20px;
    }
    
    header, footer, .btn, nav, .sidebar {
        display: none !important;
    }
    
    .container {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        margin-bottom: 20px !important;
    }
    
    .card-header {
        background-color: #f5f5f5 !important;
        padding: 10px 15px !important;
        border-bottom: 1px solid #ddd !important;
    }
    
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    
    .table th, .table td {
        border: 1px solid #ddd !important;
        padding: 8px !important;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05) !important;
    }
    
    .table-light {
        background-color: #f5f5f5 !important;
    }
    
    .text-center {
        text-align: center !important;
    }
    
    .text-end {
        text-align: right !important;
    }
    
    h2, h5 {
        margin-top: 0 !important;
    }
</style>