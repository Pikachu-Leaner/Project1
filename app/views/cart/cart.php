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
                                <img src="<?= BASE_URL . htmlspecialchars($item['image_url']) ?>" class="img-thumbnail border-0" style="width: 80px; height: 80px; object-fit: contain;">
                                <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></h6>
                            </div>
                        </td>
                        <td class="text-center text-danger fw-bold"><?= number_format($item['price'], 0, ',', '.') ?> ₫</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="<?= BASE_URL ?>Cart/update/<?= $item['id'] ?>/decrease" class="btn btn-sm btn-outline-secondary fw-bold px-2">-</a>
                                <span class="fw-bold" style="min-width: 25px;"><?= $item['quantity'] ?></span>
                                <a href="<?= BASE_URL ?>Cart/update/<?= $item['id'] ?>/increase" class="btn btn-sm btn-outline-secondary fw-bold px-2">+</a>
                            </div>
                        </td>
                        <td class="text-center text-danger fw-bold fs-5"><?= number_format($subtotal, 0, ',', '.') ?> ₫</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteCartModal" 
                                    data-id="<?= $item['id'] ?>" 
                                    data-name="<?= htmlspecialchars($item['name']) ?>">
                                <i class="fas fa-trash"></i>
                            </button>
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
                <a href="<?= BASE_URL ?>Order/checkout" class="btn btn-danger btn-lg px-5 rounded-pill shadow fw-bold w-100 text-decoration-none">
                    TIẾN HÀNH ĐẶT HÀNG
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <h4 class="text-muted mb-4">Giỏ hàng trống.</h4>
            <a href="<?= BASE_URL ?>Product/list" class="btn btn-primary rounded-pill px-4">Quay lại mua sắm</a>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="deleteCartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fw-bold">Xác nhận xóa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body py-4 text-center">
        <p>Bạn muốn xóa sản phẩm này khỏi giỏ hàng?</p>
        <p class="fw-bold text-danger fs-5" id="deleteCartProductName"></p>
      </div>
      <div class="modal-footer justify-content-center bg-light">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy</button>
        <a href="#" id="confirmDeleteCartBtn" class="btn btn-danger px-4">Đồng ý Xóa</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteCartModal = document.getElementById('deleteCartModal');
    deleteCartModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var pId = button.getAttribute('data-id');
        var pName = button.getAttribute('data-name');
        document.getElementById('deleteCartProductName').textContent = pName;
        document.getElementById('confirmDeleteCartBtn').href = '<?= BASE_URL ?>Cart/remove/' + pId;
    });
});
</script>