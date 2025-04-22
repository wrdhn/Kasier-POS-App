<?php
$query = mysqli_query($connect, "SELECT penjualan.*, pelanggan.NamaPelanggan FROM penjualan JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID");
?>

<div class="container my-4 px-0">
    <div class="card shadow rounded-2 border">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary py-3 rounded-top-2">
    <h2 class="fw-semibold text-white">Data Penjualan</h2>
    </div>
    <div class="row my-3 mx-2">
        <div class="col-md-6">
            <a href="index.php?page=tambahPenjualan" class="btn btn-success">Tambah Penjualan</a>
        </div>
        <div class="col-md-6 text-end">
            <form action="" class="d-inline-flex" role="search">
                <input type="search" value="" class="form-control me-2" placeholder="Cari...">
                <button class="btn btn-primary" type="submit" >Cari</button>
            </form>
        </div>
    </div>
    <div class="table-responsive p-3 bg-light">
        <table class="table table-hover text-center align-middle">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tanggal Penjualan</th>
                    <th scope="col">Total</th>
                    <th scope="col">Nama Pelanggan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows(($query)) > 0) {
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<th scope='col'>" . $data['PenjualanID'] . "</th>";
                        echo "<td>" . $data['TanggalPenjualan'] . "</td>";
                        echo "<td> Rp" . number_format($data['TotalHarga'], 0, ',', '.') . "</td>";
                        echo "<td>" . $data['NamaPelanggan'] . "</td>";
                        echo "<td class='d-flex justify-content-center gap-2'>";
                        echo "<a href='index.php?page=detailPenjualan&id=" . $data['PenjualanID'] . "' class='btn btn-primary text-light'>Detail</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='5'>Tidak ada data penjualan</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
</div>