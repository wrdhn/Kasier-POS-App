<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini'); window.location.href='index.php';</script>";
    exit;
}

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $query = mysqli_query($connect, "SELECT * FROM user WHERE Username LIKE '%$search%'");
} else {
    $query = mysqli_query($connect, "SELECT * FROM user");
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Pengguna</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <a href="index.php?page=tambahUser" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Pengguna
                    </a>
                </div>
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="hidden" name="page" value="user">
                        <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Cari pengguna...">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows(($query)) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $data['UserID'] . "</td>";
                                echo "<td>" . $data['Username'] . "</td>";
                                echo "<td>" . ucfirst($data['Level']) . "</td>";
                                echo "<td>";
                                echo "<div class='btn-group'>";
                                echo "<a href='index.php?page=editUser&id=" . $data['UserID'] . "' class='btn btn-warning btn-sm'>Edit</a>";
                                
                                // Jangan tampilkan tombol hapus untuk akun sendiri
                                if ($_SESSION['user_id'] != $data['UserID']) {
                                    echo "<a href='index.php?page=hapusUser&id=" . $data['UserID'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus pengguna ini?');\">Hapus</a>";
                                }
                                
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='4' class='text-center'>Tidak ada data pengguna</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>