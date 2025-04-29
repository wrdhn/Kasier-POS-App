<?php
// Mengambil data statistik
$totalPelanggan = mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as total FROM pelanggan"))[0];
$totalProduk = mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as total FROM produk"))[0];
$totalPenjualan = mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as total FROM penjualan"))[0];
$totalPendapatan = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(TotalHarga) as total FROM penjualan"))[0];

// Mengambil produk dengan stok rendah (kurang dari 10)
$produkMenipis = mysqli_query($connect, "SELECT * FROM produk WHERE Stok < 10 ORDER BY Stok ASC LIMIT 5");

// Mengambil transaksi terbaru
$transaksiTerbaru = mysqli_query($connect, 
    "SELECT p.PenjualanID, p.TanggalPenjualan, p.TotalHarga, pel.NamaPelanggan 
     FROM penjualan p
     JOIN pelanggan pel ON p.PelangganID = pel.PelangganID
     ORDER BY p.TanggalPenjualan DESC LIMIT 5"
);

// Mengambil data penjualan per bulan untuk grafik
$tahunIni = date('Y');
$dataBulan = [];
$namaBulan = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

for ($i = 1; $i <= 12; $i++) {
    $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
    $queryBulan = mysqli_query($connect, 
        "SELECT SUM(TotalHarga) as total FROM penjualan 
         WHERE YEAR(TanggalPenjualan) = '$tahunIni' AND MONTH(TanggalPenjualan) = '$bulan'"
    );
    $result = mysqli_fetch_assoc($queryBulan);
    $dataBulan[] = $result['total'] ? $result['total'] : 0;
}

// Mengambil data untuk pie chart
$queryProdukTerlaris = mysqli_query($connect, 
    "SELECT p.NamaProduk, SUM(dp.JumlahProduk) as JumlahTerjual
     FROM detailpenjualan dp 
     JOIN produk p ON dp.ProdukID = p.ProdukID
     GROUP BY dp.ProdukID
     ORDER BY JumlahTerjual DESC
     LIMIT 5"
);

$namaProduk = [];
$jumlahTerjual = [];
while ($row = mysqli_fetch_assoc($queryProdukTerlaris)) {
    $namaProduk[] = $row['NamaProduk'];
    $jumlahTerjual[] = $row['JumlahTerjual'];
}
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow">
                <div class="card-body p-4">
                    <h2 class="fw-bold">Selamat Datang di Kasier</h2>
                    <p class="mb-0">A POS system - running on vanilla PHP, giving ORM the finger, and loving it &#128148;</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="text-muted">Total Pelanggan</h5>
                            <h2 class="fw-bold"><?= number_format($totalPelanggan) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-box fs-3"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="text-muted">Total Produk</h5>
                            <h2 class="fw-bold"><?= number_format($totalProduk) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-receipt fs-3"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="text-muted">Total Penjualan</h5>
                            <h2 class="fw-bold"><?= number_format($totalPenjualan) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-currency-dollar fs-3"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="text-muted">Total Pendapatan</h5>
                            <h2 class="fw-bold fs-3">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="m-0 fw-bold text-primary">Grafik Penjualan Tahun <?= $tahunIni ?></h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="m-0 fw-bold text-primary">Produk Terlaris</h5>
                </div>
                <div class="card-body">
                    <canvas id="productChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="m-0 fw-bold text-primary">Transaksi Terbaru</h5>
                    <a href="index.php?page=penjualan" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($transaksiTerbaru) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($transaksiTerbaru)): ?>
                                        <tr>
                                            <td><?= $row['PenjualanID'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['TanggalPenjualan'])) ?></td>
                                            <td><?= $row['NamaPelanggan'] ?></td>
                                            <td>Rp <?= number_format($row['TotalHarga'], 0, ',', '.') ?></td>
                                            <td>
                                                <a href="index.php?page=detailPenjualan&id=<?= $row['PenjualanID'] ?>" class="btn btn-sm btn-info text-white">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada transaksi terbaru</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                    <h5 class="m-0 fw-bold text-primary">Stok Menipis</h5>
                    <a href="index.php?page=produk" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($produkMenipis) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($produkMenipis)): ?>
                                        <tr>
                                            <td><?= $row['NamaProduk'] ?></td>
                                            <td><?= $row['Stok'] ?></td>
                                            <td>
                                                <?php if ($row['Stok'] <= 0): ?>
                                                    <span class="badge bg-danger">Habis</span>
                                                <?php elseif ($row['Stok'] <= 5): ?>
                                                    <span class="badge bg-warning text-dark">Kritis</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info text-white">Menipis</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Semua stok dalam keadaan aman</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-white">
                    <h5 class="m-0 fw-bold text-primary">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <a href="index.php?page=tambahPenjualan" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="bi bi-cart-plus mb-2 d-block fs-1"></i>
                                Transaksi Baru
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="index.php?page=tambahPelanggan" class="btn btn-success btn-lg w-100 py-3">
                                <i class="bi bi-person-plus mb-2 d-block fs-1"></i>
                                Tambah Pelanggan
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="index.php?page=tambahProduk" class="btn btn-warning btn-lg w-100 py-3 text-white">
                                <i class="bi bi-box-seam mb-2 d-block fs-1"></i>
                                Tambah Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> -->

<script src="assets/chart.js-4.4.9/package/dist/chart.umd.js"></script>


<script>
// Chart.js configuration
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($namaBulan) ?>,
            datasets: [{
                label: 'Penjualan Bulanan',
                data: <?= json_encode($dataBulan) ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.2)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Penjualan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            }
        }
    });

    // Product Chart
    const productCtx = document.getElementById('productChart').getContext('2d');
    const productChart = new Chart(productCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($namaProduk) ?>,
            datasets: [{
                data: <?= json_encode($jumlahTerjual) ?>,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)',
                    'rgba(54, 185, 204, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' terjual';
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
});
</script>