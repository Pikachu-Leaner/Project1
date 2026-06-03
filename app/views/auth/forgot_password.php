<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-unlock-alt text-warning mb-3" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold">Quên Mật Khẩu</h3>
                        <p class="text-muted small">Nhập email của bạn, chúng tôi sẽ gửi mã OTP để đặt lại mật khẩu.</p>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger fw-bold small"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>Auth/forgot_password" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Email đăng ký</label>
                            <input type="email" name="email" class="form-control" required placeholder="Nhập địa chỉ email...">
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill py-2 text-dark">GỬI MÃ OTP</button>
                    </form>

                    <div class="text-center mt-4 small">
                        <a href="<?= BASE_URL ?>Auth/login" class="fw-bold text-decoration-none text-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>