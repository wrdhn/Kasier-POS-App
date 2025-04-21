<?php
    include "./src/config/connection.php";

    $is_login_page = isset($_GET['page']) &&  $_GET['page'] === 'login';

    if (!isset($_SESSION['login']) && !$is_login_page) {
        header("Location: index.php?page=login");
        exit;
    }

    if (isset($_SESSION['login']) && $_SESSION['login'] === true && $is_login_page) {
        header("Location: index.php?page=home");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasier-App</title>
    <link rel="stylesheet" href="assets/bootstrap-5.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <?php 
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            include "./src/includes/header.php";
        }
        ?>
    </header>
    <main class="container min-vh-100 d-flex flex-column">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page === "home") {
                include "./src/pages/home.php";
            } else if ($page === "pelanggan") {
                include "./src/pages/pelanggan/index.php";
            } else if ($page === "tambahPelanggan") {
                include "./src/pages/pelanggan/tambah.php";
            } else if ($page === "editPelanggan") {
                include "./src/pages/pelanggan/edit.php";
            } else if ($page === "hapusPelanggan") {
                include "./src/pages/pelanggan/hapus.php";
            } else if ($page === "penjualan") {
                include "./src/pages/penjualan/index.php";
            } else if ($page === "tambahPenjualan") {
                include "./src/pages/penjualan/tambah.php";
            } else if ($page === "editPenjualan") {
                include "./src/pages/penjualan/edit.php";
            } else if ($page === "hapusPenjualan") {
                include "./src/pages/penjualan/hapus.php";
            } else if ($page === "produk") {
                include "./src/pages/produk/index.php";
            } else if ($page === "tambahProduk") {
                include "./src/pages/produk/tambah.php";
            } else if ($page === "hapusProduk") {
                include "./src/pages/produk/hapus.php";
            } else if ($page === "editProduk") {
                include "./src/pages/produk/edit.php";
            } else if ($page === "detailPenjualan") {
                include "./src/pages/detail-penjualan/index.php";
            } else if ($page === "login") {
                include "./src/pages/login.php";
            } else if ($page === "logout") {
                include "./src/pages/logout.php";
            } else {
                include "./src/pages/404.php"; // 404 page
            }
        } else {
            if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                header("Location: index.php?page=home");
            } else {
                header("Location: index.php?page=login");
            }
            exit;
        }
        ?>
    </main>
    <footer>
        <?php
            if (!isset($_GET['page']) || $_GET['page'] !== 'login') {
                include "./src/includes/footer.php";
            }
        ?>
    </footer>
</body>
</html>