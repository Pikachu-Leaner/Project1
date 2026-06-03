<div class="container-fluid py-4 bg-light">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-tachometer-alt text-danger me-2"></i> Bảng điều khiển Quản trị viên</h3>
        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary fw-bold rounded-pill"><i class="fas fa-store me-1"></i> Trở về Cửa hàng</a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-4">
                <h6 class="fw-bold text-white-50">TỔNG DOANH THU</h6>
                <h2 class="fw-bold m-0"><?= number_format($revenue, 0, ',', '.') ?> ₫</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-4">
                <h6 class="fw-bold text-white-50">TỔNG ĐƠN HÀNG</h6>
                <h2 class="fw-bold m-0"><?= count($orders) ?> đơn</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning text-dark p-4">
                <h6 class="fw-bold text-dark-50">NGƯỜI DÙNG</h6>
                <h2 class="fw-bold m-0"><?= count($users) ?> thành viên</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-users text-primary me-2"></i>Quản lý tài khoản</div>
                <div class="card-body p-0 table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Tên</th>
                                <th>Vai trò</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($u['full_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                    </td>
                                    <td><span class="badge bg-<?= $u['role'] == 'Admin' ? 'danger' : 'secondary' ?>"><?= $u['role'] ?></span></td>
                                    <td class="text-center">
                                        <?php if ($u['role'] !== 'Admin'): ?>
                                            <a href="<?= BASE_URL ?>Admin/toggleUserStatus/<?= $u['id'] ?>" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-danger' : 'btn-success' ?>">
                                                <?= $u['is_active'] ? '<i class="fas fa-ban"></i> Khóa' : '<i class="fas fa-check"></i> Mở' ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">System</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 fw-bold"><i class="fas fa-box-open text-success me-2"></i>Đơn hàng gần đây</div>
                <div class="card-body p-0 table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Mã</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $o): 
                                $badge = 'bg-warning text-dark';
                                if($o['status'] == 'Completed') $badge = 'bg-success';
                                if($o['status'] == 'Cancelled') $badge = 'bg-danger';
                            ?>
                                <tr>
                                    <td class="fw-bold">#<?= $o['id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($o['customer_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($o['phone']) ?></small>
                                    </td>
                                    <td class="text-danger fw-bold"><?= number_format($o['total_amount'], 0, ',', '.') ?>đ</td>
                                    <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                                    <td><span class="badge <?= $badge ?>"><?= $o['status'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>