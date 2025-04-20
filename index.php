<?php
    include "./src/config/connection.php";
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
            include "./src/includes/header.php";
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
            } else if ($page === "penjualan") {
                include "./src/pages/penjualan/index.php";
            } else if ($page === "produk") {
                include "./src/pages/produk/index.php";
            } else if ($page === "detail_penjualan") {
                include "./src/pages/detail-penjualan/index.php";
            } else {
                include "./src/pages/404.php"; // 404 page
            }
        }
        ?>
    </main>
    <footer>
        <?php
            include "./src/includes/footer.php";
        ?>
    </footer>
</body>
</html>