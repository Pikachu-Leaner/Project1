<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-truck text-white me-2"></i> Thông tin giao hàng & Thanh toán</h5>
            </div>
            <div class="card-body p-4">
                <form id="checkoutForm" action="<?= BASE_URL ?>Order/checkout" method="POST">
                    
                    <h6 class="fw-bold text-secondary mb-3">1. Thông tin người nhận</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="customer_name" placeholder="Họ và tên người nhận" required>
                        </div>
                        <div class="col-md-6">
                            <input type="tel" class="form-control" name="phone" placeholder="Số điện thoại" required>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" name="address" rows="2" placeholder="Địa chỉ giao hàng chi tiết" required></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-secondary mb-3">2. Phương thức thanh toán</h6>
                    <div class="mb-4">
                        <select class="form-select border-primary text-dark" name="payment_method" required>
                            <option value="Thanh toán khi nhận hàng (COD)">Thanh toán khi nhận hàng (COD)</option>
                            <option value="Ví MoMo">Ví MoMo</option>
                            <option value="ZaloPay">ZaloPay</option>
                            <option value="VCB Digibank">Chuyển khoản VCB Digibank</option>
                            <option value="VNPT Money">VNPT Money</option>
                        </select>
                    </div>

                    <h6 class="fw-bold text-secondary mb-3">3. Ghi chú đơn hàng (Tùy chọn)</h6>
                    <div class="mb-4">
                        <textarea class="form-control" name="notes" rows="2" placeholder="Ví dụ: Giao trong giờ hành chính..."></textarea>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-dark">Tổng tiền thanh toán:</span>
                        <span class="fs-4 fw-bold text-danger"><?= number_format($totalAmount, 0, ',', '.') ?> ₫</span>
                    </div>

                    <button type="button" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#confirmOrderModal">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>

                    <div class="modal fade" id="confirmOrderModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i> Xác nhận thông tin</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body py-4 text-center">
                                    <p class="fs-5 mb-1">Bạn xác nhận các thông tin giao hàng đã chính xác?</p>
                                    <p class="text-muted small">Đơn hàng sẽ được tạo và gửi đến hệ thống.</p>
                                </div>
                                <div class="modal-footer justify-content-center bg-light">
                                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Kiểm tra lại</button>
                                    <button type="submit" class="btn btn-success px-5 fw-bold">Tiến hành đặt</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>