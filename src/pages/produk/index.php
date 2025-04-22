<?php
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $query = mysqli_query($connect, "SELECT * FROM produk WHERE NamaProduk LIKE '%$search%' OR ProdukID LIKE '%$search%'");
} else {
    $query = mysqli_query($connect, "SELECT * FROM produk");
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Produk</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <a href="index.php?page=tambahProduk" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </a>
                </div>
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="hidden" name="page" value="produk">
                        <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Cari produk...">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows(($query)) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $data['ProdukID'] . "</td>";
                                echo "<td>" . $data['NamaProduk'] . "</td>";
                                echo "<td>Rp " . number_format($data['Harga'], 0, ',', '.') . "</td>";
                                echo "<td>" . $data['Stok'] . "</td>";
                                echo "<td>";
                                echo "<div class='btn-group'>";
                                echo "<a href='index.php?page=editProduk&id=" . $data['ProdukID'] . "' class='btn btn-warning btn-sm'>Edit</a>";
                                echo "<a href='index.php?page=hapusProduk&id=" . $data['ProdukID'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus produk ini?');\">Hapus</a>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='5' class='text-center'>Tidak ada data produk</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>