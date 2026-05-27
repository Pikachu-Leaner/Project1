<div class="container bg-white p-4 rounded shadow-sm border border-light">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>Product/list" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>Product/list?category=<?= $product['category_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-5">
            <div class="border rounded p-4 text-center shadow-sm">
                <img src="<?= BASE_URL . htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid" style="max-height: 400px; object-fit: contain;">
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold mb-2"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="text-muted mb-4">Mã SP: #<?= $product['id'] ?> | Thương hiệu: <span class="fw-bold text-primary"><?= htmlspecialchars($product['brand']) ?></span></p>
            
            <div class="bg-light p-3 rounded mb-4 d-flex align-items-center gap-3 border border-secondary-subtle">
                <h2 class="text-danger fw-bold m-0"><?= number_format($product['price'], 0, ',', '.') ?> ₫</h2>
                <?php if (!empty($product['old_price'])): ?>
                    <span class="text-muted text-decoration-line-through fs-5"><?= number_format($product['old_price'], 0, ',', '.') ?> ₫</span>
                    <?php 
                    $discount = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                    ?>
                    <span class="badge bg-danger">-<?= $discount ?>%</span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold text-secondary"><i class="fas fa-microchip me-2"></i>THÔNG SỐ KỸ THUẬT:</h6>
                <div class="p-3 border rounded bg-light text-dark" style="white-space: pre-line; line-height: 1.6;">
                    <?= htmlspecialchars($product['details']) ?>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <a href="<?= BASE_URL ?>Cart/buyNow/<?= $product['id'] ?>" class="btn btn-danger flex-fill py-3 rounded shadow-sm text-decoration-none text-center">
                    <h5 class="fw-bold text-white mb-0">MUA NGAY</h5>
                    <small class="text-white">Giao hàng tận nơi hoặc nhận tại siêu thị</small>
                </a>

                <a href="<?= BASE_URL ?>Cart/add/<?= $product['id'] ?>" class="btn btn-outline-primary flex-fill py-3 rounded shadow-sm fw-bold d-flex align-items-center justify-content-center text-decoration-none">
                    <i class="fas fa-cart-plus me-2 fs-5"></i> THÊM VÀO GIỎ
                </a>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-success fw-bold m-0"><i class="fas fa-shield-alt me-2"></i>Bảo hành chính hãng 12 tháng. Lỗi 1 đổi 1 trong 30 ngày.</p>
            </div>
        </div>
    </div>
    
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-5 pt-4 border-top">
        <h5 class="fw-bold mb-4 text-secondary">SẢN PHẨM CÙNG THƯƠNG HIỆU</h5>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php foreach ($relatedProducts as $rel): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-light product-card">
                        <a href="<?= BASE_URL ?>Product/detail/<?= $rel['id'] ?>" class="text-decoration-none">
                            <div class="text-center p-3">
                                <img src="<?= BASE_URL . htmlspecialchars($rel['image_url']) ?>" class="card-img-top img-fluid" style="max-height: 150px; object-fit: contain;">
                            </div>
                            <div class="card-body border-top">
                                <h6 class="card-title text-dark fw-bold text-truncate" title="<?= htmlspecialchars($rel['name']) ?>">
                                    <?= htmlspecialchars($rel['name']) ?>
                                </h6>
                                <p class="text-danger fw-bold m-0"><?= number_format($rel['price'], 0, ',', '.') ?> ₫</p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>