<div class="container py-4 bg-white rounded shadow-sm">
    <div class="row g-5">
        <div class="col-md-5 text-center">
            <img src="<?= BASE_URL . htmlspecialchars($product['image_url']) ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>" 
                 class="img-fluid border rounded p-4 mb-3 shadow-sm" 
                 style="max-height: 450px; width: 100%; object-fit: contain;">
        </div>

        <div class="col-md-7">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/Product/list" class="text-decoration-none">Điện thoại</a></li>
                    <li class="breadcrumb-item text-muted" aria-current="page"><?= htmlspecialchars($product['category_name']) ?></li>
                </ol>
            </nav>

            <h2 class="fw-bold text-dark mb-2"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="text-muted small mb-4">
                Mã SP: #<?= $product['id'] ?> | Thương hiệu: <span class="text-primary fw-bold"><?= htmlspecialchars($product['brand'] ?? $product['category_name']) ?></span>
            </p>
            
            <div class="p-3 bg-light rounded mb-4 border">
                <div class="d-flex align-items-center gap-3">
                    <p class="text-danger fw-bold fs-2 mb-0">
                        <?= number_format($product['price'], 0, ',', '.') ?> ₫
                    </p>
                    <?php if (!empty($product['old_price'])): ?>
                        <p class="text-muted text-decoration-line-through fs-5 mb-0">
                            <?= number_format($product['old_price'], 0, ',', '.') ?> ₫
                        </p>
                        <span class="badge bg-danger ms-2">-<?= round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) ?>%</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold text-uppercase text-secondary mb-3"><i class="fas fa-microchip me-2"></i>Thông số kỹ thuật:</h6>
                <div class="p-3 border rounded text-dark fs-7 lh-lg" style="background-color: #f8f9fa;">
                    <?= nl2br(htmlspecialchars($product['details'] ?? 'Chưa có thông tin cấu hình chi tiết cho sản phẩm này.')) ?>
                </div>
            </div>

            <div class="d-grid gap-3 d-md-flex mb-4 pt-4 border-top">
                <a href="/Cart/add/<?= $product['id'] ?>" class="btn btn-danger btn-lg w-100 fw-bold rounded shadow-sm d-flex flex-column justify-content-center">
                    <span>MUA NGAY</span>
                    <small class="fw-normal" style="font-size: 12px;">Giao hàng tận nơi hoặc nhận tại siêu thị</small>
                </a>
                
                <a href="/Cart/add/<?= $product['id'] ?>" class="btn btn-outline-primary btn-lg w-100 fw-bold rounded shadow-sm d-flex flex-column justify-content-center align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-cart-plus me-2 fs-4"></i>
                        <span>THÊM VÀO GIỎ</span>
                    </div>
                </a>
            </div>
            
            <p class="text-center text-success small fw-bold"><i class="fas fa-shield-alt me-2"></i> Bảo hành chính hãng 12 tháng. Lỗi 1 đổi 1 trong 30 ngày.</p>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
        <hr class="my-5 border-2">
        <h4 class="fw-bold mb-4 text-uppercase border-start border-4 border-primary ps-3">Sản phẩm cùng thương hiệu</h4>
        
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <?php foreach ($relatedProducts as $relProd): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-light product-card-hover">
                        <a href="<?= BASE_URL ?>Product/detail/<?= $relProd['id'] ?>" class="text-decoration-none text-dark">
                            <div class="position-relative text-center p-3">
                                <img src="<?= BASE_URL . htmlspecialchars($relProd['image_url']) ?>" 
                                     class="card-img-top img-fluid" 
                                     alt="<?= htmlspecialchars($relProd['name']) ?>"
                                     style="max-height: 160px; object-fit: contain;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate fw-bold mb-3" title="<?= htmlspecialchars($relProd['name']) ?>">
                                    <?= htmlspecialchars($relProd['name']) ?>
                                </h6>
                                <div class="mt-auto">
                                    <p class="text-danger fw-bold fs-6 mb-0">
                                        <?= number_format($relProd['price'], 0, ',', '.') ?> ₫
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <style>
            .product-card-hover {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .product-card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            }
        </style>
    <?php endif; ?>
</div>