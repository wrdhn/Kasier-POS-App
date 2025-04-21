<!-- <?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: index.php?page=home");
    exit;
}

if (isset($_POST['submit'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = mysqli_connect($connect, "SELECT * FROM user WHERE Username = '$username' AND Password = '$password'");

    if ($query) {
        $data = mysqli_fetch_array($query);
        if ($data) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['Username'];
            $_SESSION['level'] = $data['Level'];
            header("Location: index.php?page=home");
            exit;
        } else {
            echo "<script>alert('Username atau password salah');</script>";
        }
    } else {
        echo "<script>alert('Gagal terhubung ke database');</script>";
    }
}

echo "<script>alert(" .$_SESSION['login'] .")</script>";
?>

<div class="container border rounded-4 py-4 mt-4">
    <div class="text-center mb-5">
        <h2 class="text-primary">Login</h2>
    </div>
    <form action="" class="form" method="post">
        <div class="mt-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="mt-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="d-flex gap-2 mt-5   ">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-danger">Hapus</button>

        </div>
    </form>
</div> -->