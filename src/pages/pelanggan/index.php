<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
    $query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE NamaPelanggan LIKE '%$search%' OR Alamat LIKE '%$search%' OR NomorTelepon LIKE '%$search%'");
} else {
    $query = mysqli_query($connect, "SELECT * FROM pelanggan");
}

?>
    <div class="container my-4 px-0">
    <div class="card shadow rounded-2 border">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary py-3 rounded-top-2">
    <h2 class="fw-semibold text-white">Data Pelanggan</h2>
    </div>
    <div class="row my-3 mx-2">
        <div class="col-md-6">
            <a href="index.php?page=tambahPelanggan" class="btn btn-success">Tambah Pelanggan</a>
        </div>
        <div class="col-md-6 text-end">
            <form action="index.php?page=pelanggan" class="d-inline-flex" role="search">
                <input type="search" value="" class="form-control me-2" placeholder="Cari..." name="search">
                <button class="btn btn-primary" type="submit" >Cari</button>
            </form>
        </div>
    </div>

    <div class="table-responsive p-3 bg-light">
        <table class="table table-hover text-center align-middle">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nama Pelanggan</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Telepon</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows(($query)) > 0) {
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<th scope='col'>" . $data['PelangganID'] . "</th>";
                        echo "<td>" . $data['NamaPelanggan'] . "</td>";
                        echo "<td>" . $data['Alamat'] . "</td>";
                        echo "<td>" . $data['NomorTelepon'] . "</td>";
                        echo "<td class='d-flex justify-content-center gap-2'>";
                        echo "<a href='index.php?page=editPelanggan&id=" . $data['PelangganID'] . "' class='btn btn-warning text-light'>Edit</a>";
                        echo "<a href='index.php?page=hapusPelanggan&id=" . $data['PelangganID'] . "' class='btn btn-danger'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='5'>Tidak ada data pelanggan</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>