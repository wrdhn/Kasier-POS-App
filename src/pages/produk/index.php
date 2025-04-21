<?php
$query = mysqli_query($connect, "SELECT * FROM produk")
?>

<div class="container border rounded-4 my-5 py-3 px-0">
    <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
        <h2 class="fw-semibold">Data Produk</h2>
        <a href="index.php?page=tambahProduk" class="btn btn-primary">Tambah Produk</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover text-center align-middle">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows(($query)) > 0) {
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<th scope='col'>" . $data['ProdukID'] . "</th>";
                        echo "<td>" . $data['NamaProduk'] . "</td>";
                        echo "<td>" . $data['Harga'] . "</td>";
                        echo "<td>" . $data['Stok'] . "</td>";
                        echo "<td class='d-flex justify-content-center gap-2'>";
                        echo "<a href='index.php?page=editProduk&id=" . $data['ProdukID'] . "' class='btn btn-warning text-light'>Edit</a>";
                        echo "<a href='index.php?page=hapusProduk&id=" . $data['ProdukID'] . "' class='btn btn-danger'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='5'>Tidak ada data produk</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>