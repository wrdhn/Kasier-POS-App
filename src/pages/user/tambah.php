<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);
    $level = mysqli_real_escape_string($connect, $_POST['level']);
    
    $check_query = mysqli_query($connect, "SELECT * FROM user WHERE Username = '$username'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Username sudah digunakan. Silakan pilih username lain.');</script>";
    } else {
        $query = mysqli_query($connect, "INSERT INTO user (Username, Password, Level) VALUES ('$username', '$password', '$level')");
        
        if ($query) {
            echo "<script>alert('Data pengguna berhasil ditambahkan'); window.location.href='index.php?page=user';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data: " . mysqli_error($connect) . "');</script>";
        }
    }
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Pengguna</h5>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="">-- Pilih Level --</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php?page=user" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>