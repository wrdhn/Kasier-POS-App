<?php
$where_clause = "1=1"; // Default clause untuk query
$tanggal_awal = date('Y-m-d', strtotime('-30 days')); // Default 30 hari terakhir
$tanggal_akhir = date('Y-m-d');
$pelanggan_id = "";

$query_pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");

if (isset($_GET['filter'])) {
    if (!empty($_GET['tanggal_awal'])) {
        $tanggal_awal = mysqli_real_escape_string($connect, $_GET['tanggal_awal']);
        $where_clause .= " AND TanggalPenjualan >= '$tanggal_awal'";
    }
    
    if (!empty($_GET['tanggal_akhir'])) {
        $tanggal_akhir = mysqli_real_escape_string($connect, $_GET['tanggal_akhir']);
        $where_clause .= " AND TanggalPenjualan <= '$tanggal_akhir'";
    }
    
    if (!empty($_GET['pelanggan_id'])) {
        $pelanggan_id = mysqli_real_escape_string($connect, $_GET['pelanggan_id']);
        $where_clause .= " AND penjualan.PelangganID = '$pelanggan_id'";
    }
}

// Query data penjualan with filter
$query = mysqli_query($connect, "
    SELECT penjualan.*, pelanggan.NamaPelanggan 
    FROM penjualan 
    JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
    WHERE $where_clause 
    ORDER BY penjualan.TanggalPenjualan DESC
");
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 mb-3">
                    <a href="index.php?page=tambahPenjualan" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle"></i> Tambah Penjualan
                    </a>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body p-3">
                            <form action="" method="GET" class="row g-2">
                                <input type="hidden" name="page" value="penjualan">
                                <input type="hidden" name="filter" value="true">
                                
                                <div class="col-md-3">
                                    <label class="form-label small">Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" class="form-control form-control-sm" value="<?= $tanggal_awal ?>">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label small">Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="<?= $tanggal_akhir ?>">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label small">Pelanggan</label>
                                    <select name="pelanggan_id" class="form-select form-select-sm">
                                        <option value="">Semua Pelanggan</option>
                                        <?php 
                                        mysqli_data_seek($query_pelanggan, 0);
                                        while ($data_pelanggan = mysqli_fetch_assoc($query_pelanggan)): 
                                        ?>
                                            <option value="<?= $data_pelanggan['PelangganID'] ?>" <?= ($pelanggan_id == $data_pelanggan['PelangganID']) ? 'selected' : '' ?>>
                                                <?= $data_pelanggan['NamaPelanggan'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows(($query)) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $data['PenjualanID'] . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($data['TanggalPenjualan'])) . "</td>";
                                echo "<td>" . $data['NamaPelanggan'] . "</td>";
                                echo "<td>Rp " . number_format($data['TotalHarga'], 0, ',', '.') . "</td>";
                                echo "<td>";
                                echo "<div class='btn-group'>";
                                echo "<a href='index.php?page=detailPenjualan&id=" . $data['PenjualanID'] . "' class='btn btn-info btn-sm'>Detail</a>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='5' class='text-center py-3'>Tidak ada data penjualan</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php
            $query_summary = mysqli_query($connect, "
                SELECT COUNT(*) as total_transaksi, 
                       SUM(TotalHarga) as total_penjualan 
                FROM penjualan 
                WHERE $where_clause
            ");
            $summary = mysqli_fetch_assoc($query_summary);
            ?>
            <div class="card bg-light mt-3">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-receipt text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Transaksi</h6>
                                    <h4 class="mb-0"><?= number_format($summary['total_transaksi']) ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-cash-coin text-success" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Penjualan</h6>
                                    <h4 class="mb-0">Rp <?= number_format($summary['total_penjualan'], 0, ',', '.') ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('select[name="pelanggan_id"]').addEventListener('change', function() {
        this.form.submit();
    });
});
</script>