<?php
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE NamaPelanggan LIKE '%$search%' OR NomorTelepon LIKE '%$search%'");
} else {
    $query = mysqli_query($connect, "SELECT * FROM pelanggan");
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Pelanggan</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <a href="index.php?page=tambahPelanggan" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Pelanggan
                    </a>
                </div>
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="hidden" name="page" value="pelanggan">
                        <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Cari pelanggan...">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows(($query)) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $data['PelangganID'] . "</td>";
                                echo "<td>" . $data['NamaPelanggan'] . "</td>";
                                echo "<td>" . $data['Alamat'] . "</td>";
                                echo "<td>" . $data['NomorTelepon'] . "</td>";
                                echo "<td>";
                                echo "<div class='btn-group'>";
                                echo "<a href='index.php?page=editPelanggan&id=" . $data['PelangganID'] . "' class='btn btn-warning btn-sm'>Edit</a>";
                                echo "<a data-href='index.php?page=hapusPelanggan&id=" . $data['PelangganID'] . "' class='btn btn-danger btn-sm delete-btn' >Hapus</a>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='5' class='text-center'>Tidak ada data pelanggan</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>