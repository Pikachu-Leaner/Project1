<div class="container py-4 bg-white rounded shadow-sm border border-light">
    
    <nav aria-label="breadcrumb" class="mb-4 border-bottom pb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>Product/list" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>Cart/index" class="text-decoration-none">Giỏ hàng</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-7">
            <h4 class="fw-bold text-dark mb-4"><i class="fas fa-shipping-fast text-primary me-2"></i> Thông tin giao hàng</h4>
            
            <form id="checkoutForm" action="<?= BASE_URL ?>Order/checkout" method="POST">
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Họ và tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="customer_name" placeholder="Nhập họ và tên" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control bg-light" name="phone" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted">Địa chỉ nhận hàng chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control bg-light" name="address" rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                    </div>
                </div>

                <h4 class="fw-bold text-dark mb-4 mt-5"><i class="fas fa-wallet text-primary me-2"></i> Phương thức thanh toán</h4>
                <div class="mb-4">
                    <div class="form-check border rounded p-3 mb-2 bg-light">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" id="payCOD" value="Thanh toán khi nhận hàng (COD)" checked>
                        <label class="form-check-label fw-bold ms-2" for="payCOD">
                            <i class="fas fa-money-bill-wave text-success me-2"></i> Thanh toán tiền mặt khi nhận hàng (COD)
                        </label>
                    </div>
                    <div class="form-check border rounded p-3 mb-2">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" id="payMomo" value="Ví MoMo">
                        <label class="form-check-label fw-bold ms-2 text-danger" for="payMomo">
                            Ví điện tử MoMo
                        </label>
                    </div>
                    <div class="form-check border rounded p-3 mb-2">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" id="payZalo" value="ZaloPay">
                        <label class="form-check-label fw-bold ms-2 text-primary" for="payZalo">
                            Ví điện tử ZaloPay
                        </label>
                    </div>
                    <div class="form-check border rounded p-3 mb-2">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" id="payBanking" value="Chuyển khoản Ngân hàng">
                        <label class="form-check-label fw-bold ms-2 text-info" for="payBanking">
                            <i class="fas fa-university me-2"></i> Chuyển khoản ngân hàng (VCB, Techcombank...)
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Ghi chú đơn hàng (Tùy chọn)</label>
                    <textarea class="form-control bg-light" name="notes" rows="2" placeholder="Ví dụ: Giao hàng vào giờ hành chính, gọi trước khi giao..."></textarea>
                </div>

        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-3 text-dark">Đơn hàng của bạn</h5>
                    
                    <div style="max-height: 250px; overflow-y: auto;" class="mb-3 pe-2">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-secondary rounded-pill"><?= $item['quantity'] ?>x</span>
                                    <small class="fw-bold text-dark text-truncate" style="max-width: 150px;"><?= htmlspecialchars($item['name']) ?></small>
                                </div>
                                <small class="text-danger fw-bold text-nowrap"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> ₫</small>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold"><?= number_format($totalAmount, 0, ',', '.') ?> ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 align-items-end">
                        <span class="fw-bold fs-5 text-dark">Tổng thanh toán:</span>
                        <span class="fw-bold fs-3 text-danger"><?= number_format($totalAmount, 0, ',', '.') ?> ₫</span>
                    </div>

                    <button type="button" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal">
                        ĐẶT NGAY
                    </button>
                    
                    <p class="text-center text-muted small mt-3"><i class="fas fa-shield-alt text-success me-1"></i> Thông tin của bạn được bảo mật tuyệt đối.</p>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold" id="confirmPaymentModalLabel"><i class="fas fa-check-circle me-2"></i> Xác nhận thanh toán</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4 text-center">
                        <i class="fas fa-box-open text-danger mb-3" style="font-size: 50px;"></i>
                        <h5 class="fw-bold text-dark mb-2">Hoàn tất đặt hàng?</h5>
                        <p class="text-muted mb-3">Bạn xác nhận thông tin giao hàng và số tiền <strong class="text-danger"><?= number_format($totalAmount, 0, ',', '.') ?> ₫</strong> là chính xác?</p>
                        <div class="alert alert-warning text-start small border-0 shadow-sm">
                            <i class="fas fa-info-circle me-1"></i> Sau khi đồng ý, hệ thống sẽ chốt đơn và gửi thông tin đến người bán.
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center bg-light">
                        <button type="button" class="btn btn-outline-secondary px-4 fw-bold" data-bs-dismiss="modal">Quay lại sửa</button>
                        <button type="submit" class="btn btn-danger px-5 fw-bold shadow-sm">Đồng ý đặt hàng</button>
                    </div>
                </div>
            </div>
        </div>

        </form> </div>
</div>