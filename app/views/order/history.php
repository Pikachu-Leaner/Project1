<div class="container bg-white p-4 rounded shadow-sm border border-light">
    <h4 class="fw-bold text-primary mb-4"><i class="fas fa-clipboard-list me-2"></i> Lịch sử đơn hàng</h4>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success fw-bold shadow-sm border-0">
            <i class="fas fa-check-circle me-2"></i> Đặt hàng thành công! Cảm ơn bạn đã mua sắm.
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Mã ĐH</th>
                    <th>Ngày đặt</th>
                    <th>Người nhận</th>
                    <th>Thanh toán</th>
                    <th>Tổng tiền</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): foreach ($orders as $order): 
                    $badgeClass = 'bg-warning text-dark';
                    if ($order['status'] == 'Completed') $badgeClass = 'bg-success';
                    if ($order['status'] == 'Cancelled') $badgeClass = 'bg-danger';
                ?>
                    <tr>
                        <td class="fw-bold text-secondary">#<?= $order['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?> <br><small class="text-muted"><?= htmlspecialchars($order['phone']) ?></small></td>
                        <td><?= htmlspecialchars($order['payment_method']) ?></td>
                        <td class="text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?> ₫</td>
                        <td class="text-center">
                            <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill"><?= htmlspecialchars($order['status']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">Bạn chưa có đơn hàng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>