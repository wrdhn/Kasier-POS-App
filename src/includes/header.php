<nav class="navbar navbar-expand-lg sticky-top shadow-sm bg-white border-bottom">
    <div class="container">
        <a href="" class="navbar-brand py-2 d-flex align-items-center">
            <span class="fs-3 fw-bold text-primary">K<span class="text-dark">asier</span></span>
            <span class="badge bg-primary ms-2 rounded-pill opacity-75 fw-normal">POS</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-1">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'home';
                
                $menu_items = [
                    'home' => ['Beranda', 'bi-house-door'],
                    'pelanggan' => ['Pelanggan', 'bi-people'],
                    'penjualan' => ['Penjualan', 'bi-cart'],
                    'produk' => ['Produk', 'bi-box']
                ];
                
                function isActivePage($current, $menuItem) {
                    if ($current == $menuItem) {
                        return true;
                    }
                    
                    $pattern = '/^(tambah|edit|detail|lihat|hapus)' . ucfirst($menuItem) . '/i';
                    if (preg_match($pattern, $current)) {
                        return true;
                    }
                    
                    return false;
                }
                
                foreach ($menu_items as $menu_key => $menu_info) {
                    $isActive = isActivePage($page, $menu_key);
                    $activeClass = $isActive ? 'active fw-medium text-primary border-bottom border-2 border-primary' : 'text-dark';
                    $hoverEffect = !$isActive ? 'nav-link-hover' : '';
                    
                    echo '<li class="nav-item">';
                    echo '<a href="index.php?page=' . $menu_key . '" class="nav-link px-3 py-2 ' . $activeClass . ' ' . $hoverEffect . '">';
                    echo '<i class="bi ' . $menu_info[1] . ' me-1"></i> ' . $menu_info[0];
                    echo '</a>';
                    echo '</li>';
                }
                
                if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin') {
                    $isActive = ($page == 'pengguna') ? 'active fw-medium text-primary border-bottom border-2 border-primary' : 'text-dark';
                    $hoverEffect = $page != 'pengguna' ? 'nav-link-hover' : '';
                    
                    echo '<li class="nav-item">';
                    echo '<a href="index.php?page=pengguna" class="nav-link px-3 py-2 ' . $isActive . ' ' . $hoverEffect . '">';
                    echo '<i class="bi bi-person-gear me-1"></i> User';
                    echo '<span class="badge rounded-pill bg-danger ms-1 small">Admin</span>';
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '<li class="nav-item d-none d-lg-block">';
                echo '<div class="vr mx-2 my-2"></div>';
                echo '</li>';
                
                echo '<li class="nav-item">';
                echo '<a href="index.php?page=logout" class="nav-link px-3 py-2 text-danger nav-link-hover">';
                echo '<i class="bi bi-box-arrow-right me-1"></i> Logout';
                echo '</a>';
                echo '</li>';
                ?>
            </ul>
        </div>
    </div>
</nav>

<!-- CSS tambahan untuk efek hover dan transisi -->
<style>
    .nav-link-hover {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .nav-link-hover:hover {
        color: var(--bs-primary) !important;
        transform: translateY(-2px);
    }
    
    .nav-link-hover:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        background-color: var(--bs-primary);
        transition: all 0.3s ease;
    }
    
    .nav-link-hover:hover:after {
        width: 80%;
        left: 10%;
    }
    
    .navbar-brand {
        transition: transform 0.3s ease;
    }
    
    .navbar-brand:hover {
        transform: scale(1.05);
    }
    
    .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
    }
    
    @media (max-width: 992px) {
        .navbar-nav .nav-item {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0.3rem 0;
        }
        
        .navbar-nav .nav-item:last-child {
            border-bottom: none;
        }
    }
</style>

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> -->
<script src="assets/bootstrap-5.3.5/js/bootstrap.bundle.min.js"></script>