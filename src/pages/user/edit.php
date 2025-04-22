<?php
    $getData = mysqli_query($connect, "SELECT * FROM user WHERE UserID = '" . $_GET['id'] . "'");
    if (mysqli_num_rows($getData) > 0) {
        $data = mysqli_fetch_array($getData);
    } else {
        echo "<script>alert('Data tidak ditemukan');</script>";
        echo "<script>window.location.href='index.php?page=user';</script>";
    }
    if (isset($_POST["submit"])) {
    $query = mysqli_query($connect, "UPDATE user SET Username = '" . $_POST['username'] . "', Password = '" . $_POST['password'] . "', Level = '" . $_POST['level'] . "' WHERE UserID = '" . $_GET['id'] . "'");
    if ($query) {
        echo "<script>alert('Data berhasil diedit');</script>";
        echo "<script>window.location.href='index.php?page=user';</script>";
    } else {
        echo "<script>alert('Data gagal diedit');</script>";
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
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $data['Username']?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $data['Password']?>" required>
            </div>
            <div class="mb-3">
                <?php
                if ($data['Level'] === 'admin') { ?>
                    <input type="radio" class="form-check-input" id="admin" name="level" value="admin" checked required>
                <?php } else { ?>
                    <input type="radio" class="form-check-input" id="admin" name="level" value="admin" required>
                <?php }?>
                <label for="admin" class="form-label">Admin</label>
                <?php if ($data['Level'] === 'user') { ?>
                    <input type="radio" class="form-check-input" id="admin" name="level" value="user" checked required>
                <?php } else { ?>
                    <input type="radio" class="form-check-input" id="admin" name="level" value="user" required>
                <?php }?>
                <label for="user" class="form-label">User</label>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>