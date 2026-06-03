<?php
// ==========================================
// 1. BACKEND ROUTING LOGIC
// ==========================================
require_once 'app/models/ProductModel.php';
require_once 'app/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Đếm số lượng sản phẩm khác nhau trong Giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = count(array_keys($_SESSION['cart']));
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); 
if ($scriptDir === '/') { $scriptDir = ''; }
define('BASE_URL', $protocol . '://' . $host . $scriptDir . '/');

$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlArray = explode('/', $url);
$urlArray = array_filter($urlArray);
$urlArray = array_values($urlArray);

$currentBrand = isset($_GET['brand']) ? $_GET['brand'] : '';
$currentCat = isset($_GET['category']) ? $_GET['category'] : '';
$currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'noi_bat';
$currentSearch = isset($_GET['search']) ? trim($_GET['search']) : '';
$currentRoute = !empty($url) ? $url : 'Product/list';

$controllerName = isset($urlArray[0]) && $urlArray[0] !== '' ? ucfirst($urlArray[0]) . 'Controller' : 'ProductController'; 
$action = isset($urlArray[1]) && $urlArray[1] !== '' ? $urlArray[1] : 'index'; 
$params = array_slice($urlArray, 2);

$controllerFile = 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) { die("<div class='p-4'><h1>Lỗi 404</h1><p>Không tìm thấy file Controller.</p></div>"); }
require_once $controllerFile;
if (!class_exists($controllerName)) { die("<div class='p-4'><h1>Lỗi hệ thống</h1><p>Thiếu Class Controller.</p></div>"); }

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    $action = 'list';
    if (!method_exists($controller, $action)) { die("<div class='p-4'><h1>Lỗi 404</h1><p>Không tìm thấy Action.</p></div>"); }
}

// Lấy danh mục toàn cục cho Thanh Navigation đen
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmtCat = $conn->prepare("SELECT * FROM categories ORDER BY id ASC");
    $stmtCat->execute();
    $globalCategories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("<div class='p-4 text-danger'><h1>Lỗi Database</h1><p>Không thể kết nối CSDL: " . $e->getMessage() . "</p></div>");
}

// Xác định các trang cần ẩn thanh Navigation đen
$hideNavLinks = false;
if ($controllerName !== 'ProductController' || in_array($action, ['detail', 'add', 'edit'])) {
    $hideNavLinks = true;
}

// ==========================================
// 2. OUTPUT BUFFERING
// ==========================================
ob_start(); 
call_user_func_array([$controller, $action], $params);
$viewContent = ob_get_clean(); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartphone Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css?v=<?= time() ?>">
</head>
<body class="bg-light">

    <header class="header-main py-2 shadow-sm border-bottom bg-white">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>public/images/Store-image.png" class="store-logo" style="height: 40px;" alt="Logo" onerror="this.src='https://via.placeholder.com/150x40/ffd400/333333?text=Logo'"></a>
            
            <form action="<?= BASE_URL ?>Product/list" method="GET" class="input-group w-50">
                <input type="text" name="search" class="form-control search-input" placeholder="Tìm tên sản phẩm, hãng..." value="<?= htmlspecialchars($currentSearch) ?>">
                <button class="btn search-btn" type="submit" style="border: 1px solid #ced4da; border-left: none;"><i class="fas fa-search text-muted"></i></button>
            </form>

            <div class="d-flex align-items-center gap-3">
                <a href="<?= BASE_URL ?>Cart/index" class="btn btn-dark text-white rounded-pill px-3 fs-7 shadow-sm position-relative">
                    <i class="fas fa-shopping-cart me-1"></i> Giỏ hàng
                    <?php if ($cartCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle border shadow-sm rounded-pill px-3 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <img src="<?= BASE_URL . htmlspecialchars($_SESSION['user_avatar'] ?? 'public/images/default-avatar.png') ?>" class="rounded-circle" style="width: 25px; height: 25px; object-fit: cover;">
                            <span class="fw-bold fs-7"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        <?php else: ?>
                            <i class="fas fa-user-circle fs-5 text-secondary"></i>
                            <span class="fw-bold fs-7 text-secondary">Tài khoản</span>
                        <?php endif; ?>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <?php if($_SESSION['user_role'] === 'Admin'): ?>
                                <li><a class="dropdown-item fw-bold text-danger" href="<?= BASE_URL ?>Admin/dashboard"><i class="fas fa-chart-line me-2"></i>Admin Panel</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>User/profile"><i class="fas fa-id-badge me-2 text-primary"></i>Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>Order/history"><i class="fas fa-box me-2 text-success"></i>Đơn hàng của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="<?= BASE_URL ?>Auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item fw-bold" href="<?= BASE_URL ?>Auth/login"><i class="fas fa-sign-in-alt me-2 text-primary"></i>Đăng nhập</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>Auth/register"><i class="fas fa-user-plus me-2 text-success"></i>Đăng ký mới</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <nav class="nav-main shadow bg-dark text-white">
        <div class="container">
            <ul class="nav nav-pills justify-content-start flex-nowrap overflow-x-auto overflow-y-hidden py-2">
                <?php if (!$hideNavLinks): ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>Product/list" class="nav-link text-white <?= empty($currentCat) ? 'active bg-primary' : '' ?>">
                            <i class="fas fa-th-large me-1"></i> Tất cả
                        </a>
                    </li>
                    <?php foreach ($globalCategories as $cat): ?>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>Product/list?category=<?= $cat['id'] ?>" 
                               class="nav-link text-white <?= ($currentCat == $cat['id']) ? 'active bg-primary' : '' ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="nav-item"><span class="nav-link text-secondary"><i class="fas fa-lock me-1"></i> Khu vực chức năng</span></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="container my-4" id="shop-section">
        <?php if($controllerName === 'ProductController' && ($action == 'index' || $action == 'list')): ?>
            
            <div id="mainPromoCarousel" class="carousel slide custom-carousel mb-4 shadow rounded overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-1.png" class="d-block w-100" alt="Banner 1" onerror="this.src='https://via.placeholder.com/1200x300/0d6efd/ffffff?text=Banner+Khuyen+Mai+1'">
                    </div>
                    <div class="carousel-item" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-2.png" class="d-block w-100" alt="Banner 2" onerror="this.src='https://via.placeholder.com/1200x300/6c757d/ffffff?text=Banner+Khuyen+Mai+2'">
                    </div>
                    <div class="carousel-item" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-3.png" class="d-block w-100" alt="Banner 3" onerror="this.src='https://via.placeholder.com/1200x300/198754/ffffff?text=Banner+Khuyen+Mai+3'">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainPromoCarousel" data-bs-slide="prev" aria-label="Previous">
                    <span class="carousel-control-prev-icon rounded-circle p-3" style="background-color: rgba(0,0,0,0.5);" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainPromoCarousel" data-bs-slide="next" aria-label="Next">
                    <span class="carousel-control-next-icon rounded-circle p-3" style="background-color: rgba(0,0,0,0.5);" aria-hidden="true"></span>
                </button>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2 mb-4 filter-container bg-white p-3 rounded shadow-sm border border-light">
                <a href="<?= BASE_URL . $currentRoute ?>?sort=<?= $currentSort ?>#shop-section" 
                   class="filter-btn border border-secondary-subtle rounded px-3 py-1 <?= empty($currentBrand) ? 'active bg-light text-primary' : 'text-dark' ?> fw-bold text-decoration-none">
                    <i class="fas fa-filter"></i> Lọc
                </a>
                
                <?php 
                $brands = [
                    'SAMSUNG' => 'text-dark', 'iPhone' => 'text-dark', 'xiaomi' => 'text-dark', 
                    'OPPO' => 'text-success', 'vivo' => 'text-primary', 'realme' => 'text-warning', 
                    'HONOR' => 'text-dark', 'motorola' => 'text-dark'
                ];
                
                foreach ($brands as $brandName => $colorClass): 
                    $isActive = ($currentBrand === $brandName) ? 'active border-primary bg-primary text-white' : 'border-secondary-subtle bg-white'; 
                    $finalColor = ($currentBrand === $brandName) ? 'text-white' : $colorClass;
                    $searchParam = !empty($currentSearch) ? "&search=" . urlencode($currentSearch) : "";
                ?>
                    <a href="<?= BASE_URL . $currentRoute ?>?brand=<?= urlencode($brandName) ?>&sort=<?= $currentSort ?><?= $searchParam ?>#shop-section" 
                       class="filter-btn border rounded px-3 py-1 fw-bold text-decoration-none <?= $finalColor ?> <?= $isActive ?>">
                        <?= htmlspecialchars($brandName) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?= $viewContent; ?>

    </main>

    <footer class="footer-main mt-5 border-top bg-white pt-4 pb-3">
        <div class="container text-center text-muted" style="font-size: 13px;">
            <p class="mb-1">© 2024. Cửa hàng điện thoại. Dự án PHP MVC trên Laragon.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/script.js?v=<?= time() ?>"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var hash = window.location.hash;
        if(hash) {
            setTimeout(function() {
                var targetSection = document.querySelector(hash);
                if(targetSection) { 
                    targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' }); 
                }
            }, 50);
        }
    });
    </script>
</body>
</html>