<?php
// ==========================================
// 1. BACKEND ROUTING LOGIC
// ==========================================
require_once 'app/models/ProductModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CẬP NHẬT LẠI: Chỉ đếm số lượng các sản phẩm khác biệt (Unique items)
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = count(array_keys($_SESSION['cart']));
}

// Xử lý đường dẫn cho Windows Laragon
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); 
if ($scriptDir === '/') { 
    $scriptDir = ''; 
}
define('BASE_URL', $protocol . '://' . $host . $scriptDir . '/');

$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlArray = explode('/', $url);
$urlArray = array_filter($urlArray);
$urlArray = array_values($urlArray);

// Lấy tham số Brand và Sort từ URL
$currentBrand = isset($_GET['brand']) ? $_GET['brand'] : '';
$currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'noi_bat';
$currentRoute = !empty($url) ? $url : 'Product/list';

// Định tuyến mặc định
$controllerName = isset($urlArray[0]) && $urlArray[0] !== '' 
    ? ucfirst($urlArray[0]) . 'Controller' 
    : 'ProductController'; 

$action = isset($urlArray[1]) && $urlArray[1] !== '' 
    ? $urlArray[1] 
    : 'index'; 

$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    die("<div style='padding:20px; font-family:sans-serif;'><h1>Lỗi 404</h1><p>Không tìm thấy file Controller: <b>{$controllerName}.php</b></p></div>");
}
require_once $controllerFile;

if (!class_exists($controllerName)) {
    die("<div style='padding:20px; font-family:sans-serif;'><h1>Lỗi hệ thống</h1><p>Tìm thấy file nhưng thiếu Class: <b>{$controllerName}</b></p></div>");
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    $action = 'list';
    if (!method_exists($controller, $action)) {
        die("<div style='padding:20px; font-family:sans-serif;'><h1>Lỗi 404</h1><p>Không tìm thấy Action trong <b>{$controllerName}</b></p></div>");
    }
}

$params = array_slice($urlArray, 2);

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
    <title>Smartphone Store | Mua sắm dễ dàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css?v=<?= time() ?>">
</head>
<body class="bg-light">

    <header class="header-main py-2 shadow-sm border-bottom">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="<?= BASE_URL ?>" class="text-decoration-none">
                <img src="<?= BASE_URL ?>public/images/Store-image.png?v=<?= time() ?>" alt="Store Logo" class="store-logo" onerror="this.src='https://via.placeholder.com/150x40/ffd400/333333?text=StoreLogo'">
            </a>
            
            <div class="input-group w-50">
                <input type="text" class="form-control search-input" placeholder="Bạn tìm gì..." aria-label="Tìm kiếm sản phẩm">
                <button class="btn search-btn" type="button" aria-label="Nút Tìm kiếm"><i class="fas fa-search text-muted"></i></button>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-dark text-decoration-none fs-7"><i class="fas fa-history me-1"></i> Lịch sử</a>
                <a href="<?= BASE_URL ?>Cart/index" class="btn btn-dark text-white rounded-pill px-3 fs-7 shadow-sm position-relative">
                    <i class="fas fa-shopping-cart me-1"></i> Giỏ hàng
                    <?php if ($cartCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cartCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>

    <nav class="nav-main shadow">
        <div class="container">
            <ul class="nav nav-pills justify-content-start flex-nowrap overflow-x-auto overflow-y-hidden">
                <li class="nav-item"><a href="<?= BASE_URL ?>Product/list" class="nav-link active"><i class="fas fa-mobile-alt me-1"></i> Điện thoại</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-laptop me-1"></i> Laptop</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-tablet-alt me-1"></i> Tablet</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-headphones me-1"></i> Phụ kiện</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-clock me-1"></i> Đồng hồ</a></li>
            </ul>
        </div>
    </nav>

    <main class="container my-4" id="shop-section">
        
        <?php 
        // BỘ LỌC VÀ CAROUSEL CHỈ HIỆN Ở TRANG CHỦ / DANH SÁCH
        if($action == 'index' || $action == 'list'): 
        ?>
            <div id="mainPromoCarousel" class="carousel slide custom-carousel mb-4 shadow" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-1.png" class="d-block w-100" alt="Banner 1">
                    </div>
                    <div class="carousel-item" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-2.png" class="d-block w-100" alt="Banner 2">
                    </div>
                    <div class="carousel-item" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-3.png" class="d-block w-100" alt="Banner 3">
                    </div>
                    <div class="carousel-item" data-bs-interval="15000">
                        <img src="<?= BASE_URL ?>public/images/Carousel-image-4.png" class="d-block w-100" alt="Banner 4">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainPromoCarousel" data-bs-slide="prev" aria-label="Previous">
                    <span class="carousel-control-prev-icon rounded-circle p-3" style="background-color: rgba(0,0,0,0.5);" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainPromoCarousel" data-bs-slide="next" aria-label="Next">
                    <span class="carousel-control-next-icon rounded-circle p-3" style="background-color: rgba(0,0,0,0.5);" aria-hidden="true"></span>
                </button>
            </div>
            
            <div class="d-flex flex-wrap gap-2 mb-4 filter-container bg-white p-3 rounded shadow-sm border border-light">
                <a href="<?= BASE_URL . $currentRoute ?>?sort=<?= $currentSort ?>#shop-section" class="filter-btn <?= empty($currentBrand) ? 'active text-primary' : '' ?> fw-bold text-decoration-none">
                    <i class="fas fa-filter"></i> Lọc
                </a>
                
                <?php 
                $brands = [
                    'SAMSUNG' => '', 
                    'iPhone' => '', 
                    'xiaomi' => '', 
                    'OPPO' => 'text-success', 
                    'vivo' => 'text-primary', 
                    'realme' => 'text-warning', 
                    'HONOR' => '', 
                    'motorola' => ''
                ];
                
                foreach ($brands as $brandName => $colorClass): 
                    $isActive = ($currentBrand === $brandName) ? 'active text-primary' : ''; 
                ?>
                    <a href="<?= BASE_URL . $currentRoute ?>?brand=<?= urlencode($brandName) ?>&sort=<?= $currentSort ?>#shop-section" 
                       class="filter-btn fw-bold text-decoration-none <?= $colorClass ?> <?= $isActive ?>">
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
        if(window.location.hash === "#shop-section") {
            setTimeout(function() {
                var shopSection = document.getElementById("shop-section");
                if(shopSection) {
                    shopSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 50);
        }
    });
    </script>
</body>
</html>