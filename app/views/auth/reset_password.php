<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-3 p-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-2">Tạo mật khẩu mới</h4>
                    <p class="text-muted small">Mã OTP đã được gửi đến <strong><?= htmlspecialchars($_SESSION['reset_email']) ?></strong></p>
                </div>
                
                <?php if(isset($_SESSION['debug_otp'])): ?>
                    <div class="alert alert-info small"><strong>Test OTP:</strong> <?= $_SESSION['debug_otp'] ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger fw-bold small"><?= $error ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>Auth/reset_password" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mã OTP (6 số)</label>
                        <input type="text" name="otp" class="form-control text-center fw-bold ls-widest" placeholder="------" required maxlength="6">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="passwordInput" class="form-control" required placeholder="Nhập mật khẩu mới...">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">ĐỔI MẬT KHẨU</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const pwd = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text'; icon.classList.replace('fa-eye-slash', 'fa-eye');
    } else {
        pwd.type = 'password'; icon.classList.replace('fa-eye', 'fa-eye-slash');
    }
});
</script>