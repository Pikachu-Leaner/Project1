<?php
// ==========================================
// 1. BACKEND ROUTING LOGIC
// ==========================================
require_once 'app/models/ProductModel.php';
require_once 'app/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$currentSearch = isset($_GET['search']) ? $_GET['search'] : '';
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

// Lấy danh mục toàn cục cho Thanh Navigation đen (Sẽ gây lỗi nếu chưa chạy SQL trên Cloud)
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmtCat = $conn->prepare("SELECT * FROM categories ORDER BY id ASC");
    $stmtCat->execute();
    $globalCategories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("<div class='p-4 text-danger'><h1>Lỗi Database</h1><p>Vui lòng chạy script SQL để tạo bảng categories trên Cloud. Chi tiết: " . $e->getMessage() . "</p></div>");
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
            <a href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>public/images/Store-image.png" class="store-logo" style="height: 40px;" alt="Logo" onerror="this.src='https://via.placeholder.com/150x40?text=Logo'"></a>
            
            <form action="<?= BASE_URL ?>Product/list" method="GET" class="input-group w-50">
                <input type="text" name="search" class="form-control search-input" placeholder="Tìm tên sản phẩm, hãng..." value="<?= htmlspecialchars($currentSearch) ?>">
                <button class="btn search-btn" type="submit" style="border: 1px solid #ced4da; border-left: none;"><i class="fas fa-search text-muted"></i></button>
            </form>

            <div class="d-flex align-items-center gap-3">
                <a href="<?= BASE_URL ?>Order/history" class="text-dark text-decoration-none fs-7 fw-bold"><i class="fas fa-history me-1"></i> Lịch sử đơn hàng</a>
                <a href="<?= BASE_URL ?>Cart/index" class="btn btn-dark text-white rounded-pill px-3 fs-7 shadow-sm position-relative">
                    <i class="fas fa-shopping-cart me-1"></i> Giỏ hàng
                    <?php if ($cartCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
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
            <div class="d-flex flex-wrap gap-2 mb-4 filter-container bg-white p-3 rounded shadow-sm border border-light">
                <a href="<?= BASE_URL . $currentRoute ?>?sort=<?= $currentSort ?>#shop-section" class="filter-btn <?= empty($currentBrand) ? 'active text-primary' : '' ?> fw-bold text-decoration-none"><i class="fas fa-filter"></i> Lọc</a>
                <?php 
                $brands = ['SAMSUNG' => '', 'iPhone' => '', 'xiaomi' => '', 'OPPO' => 'text-success', 'vivo' => 'text-primary', 'realme' => 'text-warning', 'HONOR' => '', 'motorola' => ''];
                foreach ($brands as $brandName => $colorClass): 
                    $isActive = ($currentBrand === $brandName) ? 'active text-primary' : ''; 
                ?>
                    <?php $searchParam = !empty($currentSearch) ? "&search=" . urlencode($currentSearch) : ""; ?>
                    <a href="<?= BASE_URL . $currentRoute ?>?brand=<?= urlencode($brandName) ?>&sort=<?= $currentSort ?><?= $searchParam ?>#shop-section" class="filter-btn fw-bold text-decoration-none <?= $colorClass ?> <?= $isActive ?>"><?= htmlspecialchars($brandName) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?= $viewContent; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var hash = window.location.hash;
        if(hash) {
            setTimeout(function() {
                var targetSection = document.querySelector(hash);
                if(targetSection) { targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            }, 50);
        }
    });
    </script>
</body>
</html>