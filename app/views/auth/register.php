<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus text-success mb-3" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold">Đăng ký tài khoản</h3>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger fw-bold small"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>Auth/register" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" required placeholder="Ví dụ: Nguyễn Văn A">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="Nhập địa chỉ email...">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Mật khẩu</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Tạo mật khẩu...">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye-slash" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill py-2">ĐĂNG KÝ</button>
                    </form>

                    <div class="text-center mt-4 small">
                        Đã có tài khoản? <a href="<?= BASE_URL ?>Auth/login" class="fw-bold text-decoration-none text-primary">Đăng nhập</a>
                    </div>
                </div>
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