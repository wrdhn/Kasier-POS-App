<?php
$query = mysqli_query($connect, "SELECT penjualan.*, pelanggan.NamaPelanggan FROM penjualan JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID");
?>

<div class="container border rounded-4 my-5 py-3 px-0">
    <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
        <h2 class="fw-semibold">Data Penjualan</h2>
        <a href="index.php?page=tambahPenjualan" class="btn btn-primary">Tambah Penjualan</a>
    </div>
    <div class="table-responsive">
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
                        echo "<a href='index.php?page=editPenjualan&id=" . $data['PenjualanID'] . "' class='btn btn-warning text-light'>Edit</a>";
                        echo "<a href='index.php?page=detailPenjualan&id=" . $data['PenjualanID'] . "' class='btn btn-primary'>Detail</a>";
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