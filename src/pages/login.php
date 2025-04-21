<?php
if (isset($_SESSION['login'])) {
    header("Location: index.php?page=home");
    exit;
}

if (isset($_POST['submit'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = mysqli_query($connect, "SELECT * FROM user WHERE Username = '$username' AND Password = '$password'");

    if ($query) {
        if (mysqli_num_rows($query)> 0) {
        $data = mysqli_fetch_array($query);
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['Username'];
            $_SESSION['level'] = $data['Level'];
            header("Location: index.php?page=home");
            echo "<script>console.log('Login berhasil, session diatur');</script>";
            exit;
        } else {
            echo "<script>alert('Username atau password salah');</script>";
        }
    } else {
        echo "<script>alert('Gagal terhubung ke database');</script>";
    }
}
?>
<div class="container-fluid p-0">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2 class="text-white mb-0">Login</h2>
            </div>
            <div class="login-form">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger" name="reset">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
