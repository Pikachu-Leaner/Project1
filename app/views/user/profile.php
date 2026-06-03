<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <img src="<?= BASE_URL . htmlspecialchars($user['avatar']) ?>" class="rounded-circle mx-auto mb-3 shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #0d6efd;">
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['full_name']) ?></h5>
                <p class="text-muted small mb-3"><?= htmlspecialchars($user['email']) ?></p>
                <span class="badge bg-<?= $user['role'] == 'Admin' ? 'danger' : 'primary' ?> rounded-pill px-3 py-2"><?= $user['role'] ?></span>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Cập nhật hồ sơ</h5>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success fw-bold small"><i class="fas fa-check-circle me-1"></i> <?= $success ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>User/profile" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Địa chỉ nhận hàng</label>
                            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Thay đổi Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>