<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $query = mysqli_query($connect, "SELECT * FROM user WHERE UserID = '$id'");
    $data = mysqli_fetch_assoc($query);
    
    if (!$data) {
        echo "<script>alert('Data pengguna tidak ditemukan'); window.location.href='index.php?page=pengguna';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=pengguna';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password']; // Jika password diubah
    $level = mysqli_real_escape_string($connect, $_POST['level']);
    
    $check_query = mysqli_query($connect, "SELECT * FROM user WHERE Username = '$username' AND UserID != '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Username sudah digunakan. Silakan pilih username lain.');</script>";
    } else {
        // Update dengan atau tanpa password
        if (!empty($password)) {
            $password = mysqli_real_escape_string($connect, $_POST['password']);
            $query = mysqli_query($connect, "UPDATE user SET Username = '$username', Password = '$password', Level = '$level' WHERE UserID = '$id'");
        } else {
            // Jika password kosong, jangan update password yaa sayang
            $query = mysqli_query($connect, "UPDATE user SET Username = '$username', Level = '$level' WHERE UserID = '$id'");
        }
        
        if ($query) {
            echo "<script>alert('Data pengguna berhasil diperbarui'); window.location.href='index.php?page=user';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data: " . mysqli_error($connect) . "');</script>";
        }
    }
}
?>

<div class="container my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Pengguna</h5>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($data['Username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="">-- Pilih Level --</option>
                        <option value="admin" <?= ($data['Level'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="petugas" <?= ($data['Level'] == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index.php?page=pengguna" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>