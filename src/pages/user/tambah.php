<?php
if (isset($_POST['submit'])) {
    $query = mysqli_query($connect, "INSERT INTO user (Username, Password, Level) VALUES ('" . $_POST['username'] . "', '" . $_POST['password'] . "', '" . $_POST['level'] . "')");
    if ($query) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
        echo "<script>window.location.href='index.php?page=user';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');</script>";
    }
}
?>

<div class="container border mt-5 rounded-4">
    <form action="" method="post">
        <div class="d-flex justify-content-between align-items-center px-4 pt-3 pb-4 border-bottom">
            <h2 class="fw-semibold">Tambah User</h2>
            <a href="index.php?page=user" class="btn btn-primary">Kembali</a>
        </div>
        <div class="px-4 py-3">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <input type="radio" class="form-check-input" id="admin" name="level" value="admin" required>
                <label for="admin" class="form-label">Admin</label>
                <input type="radio" class="form-check-input" id="user" name="level" value="user" required>
                <label for="user" class="form-label">User</label>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>