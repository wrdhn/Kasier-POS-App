<?php
if ($_SESSION['level'] == 'admin') {
    if (isset($_POST['submit'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $level = $_POST["level"];

        $query = mysqli_query($connect, "INSERT INTO user (Username, Password, Level) VALUES ('$username', '$password', '$level')");

        if ($query) {
            echo "<script>alert('User berhasil ditambahkan');</script>";
            header("Location: index.php?page=user");
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan user');</script>";
        }
    }
}
?>

<div class="container my-4 px-0">
    <div class="card shadow rounded-2 border">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary py-3 rounded-top-2">
    <h2 class="fw-semibold text-white">Data Pengguna</h2>
    </div>
    <div class="row my-3 mx-2">
        <div class="col-md-6">
            <a href="index.php?page=tambahPengguna" class="btn btn-success">Tambah Pengguna</a>
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
                    <th scope="col">Username</th>
                    <th scope="col">Level</th>
                    <?php if ($_SESSION['level'] == 'admin') { ?>
                        <th scope="col">Aksi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($connect, "SELECT * FROM user");
                if (mysqli_num_rows($query) > 0) {
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<th scope='col'>" . $data['UserID'] . "</th>";
                        echo "<td>" . $data['Username'] . "</td>";
                        echo "<td>" . $data['Level'] . "</td>";
                        if ($_SESSION['level'] == 'admin') {
                            echo "<td class='d-flex justify-content-center gap-2'>";
                            echo "<a href='index.php?page=editPengguna=" . $data['UserID'] . "' class='btn btn-warning text-light'>Edit</a>";
                            echo "<a href='index.php?page=editPengguna=" . $data['UserID'] . "' class='btn btn-danger'>Hapus</a>";
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='4'>Tidak ada data user</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>