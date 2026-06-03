<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle text-primary mb-3" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold">Đăng nhập</h3>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger fw-bold small"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success fw-bold small"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>Auth/login" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Nhập email...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Mật khẩu</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Nhập mật khẩu...">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye-slash" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label small text-muted" for="rememberMe">Nhớ tài khoản</label>
                            </div>
                            <a href="<?= BASE_URL ?>Auth/forgot_password" class="small text-decoration-none text-primary fw-bold">Quên mật khẩu?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2">ĐĂNG NHẬP</button>
                    </form>

                    <div class="text-center mt-4 small">
                        Bạn chưa có tài khoản? <a href="<?= BASE_URL ?>Auth/register" class="fw-bold text-decoration-none text-danger">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>