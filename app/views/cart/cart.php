<div class="container py-4 bg-white rounded shadow-sm" id="cart-section">
    <h3 class="fw-bold mb-4 text-primary"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h3>
    
    <?php if (!empty($cartItems)): ?>
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col" class="text-center">Đơn giá</th>
                        <th scope="col" class="text-center">Số lượng</th>
                        <th scope="col" class="text-center">Thành tiền</th>
                        <th scope="col" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0; 
                    foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity']; 
                        $total += $subtotal; 
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= BASE_URL . htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-thumbnail border-0" style="width: 80px; height: 80px; object-fit: contain;">
                                <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></h6>
                            </div>
                        </td>
                        <td class="text-center text-danger fw-bold">
                            <?= number_format($item['price'], 0, ',', '.') ?> ₫
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="<?= BASE_URL ?>Cart/update/<?= $item['id'] ?>/decrease" class="btn btn-sm btn-outline-secondary fw-bold px-2">-</a>
                                <span class="fw-bold" style="min-width: 25px;"><?= $item['quantity'] ?></span>
                                <a href="<?= BASE_URL ?>Cart/update/<?= $item['id'] ?>/increase" class="btn btn-sm btn-outline-secondary fw-bold px-2">+</a>
                            </div>
                        </td>
                        <td class="text-center text-danger fw-bold fs-5">
                            <?= number_format($subtotal, 0, ',', '.') ?> ₫
                        </td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>Cart/remove/<?= $item['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-3 border-top">
            <a href="<?= BASE_URL ?>Product/list" class="btn btn-outline-secondary mb-3 mb-md-0 fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
            </a>
            
            <div class="text-md-end text-center bg-light p-4 rounded shadow-sm border">
                <p class="text-muted mb-1 fs-6">Tổng thanh toán:</p>
                <h2 class="text-danger fw-bold mb-3"><?= number_format($total, 0, ',', '.') ?> ₫</h2>
                <button class="btn btn-danger btn-lg px-5 rounded-pill shadow fw-bold w-100">
                    TIẾN HÀNH ĐẶT HÀNG
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-box-open text-muted mb-3" style="font-size: 64px;"></i>
            <h4 class="text-muted mb-4">Giỏ hàng của bạn đang trống.</h4>
            <a href="<?= BASE_URL ?>Product/list" class="btn btn-primary btn-lg px-4 rounded-pill fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Quay lại mua sắm
            </a>
        </div>
    <?php endif; ?>
</div>