<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <div class="card shadow-sm border-0 rounded-3 p-5">
                <i class="fas fa-envelope-open-text text-warning mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mb-3">Xác thực Email</h4>
                <p class="text-muted small">Chúng tôi đã gửi mã OTP 6 số đến email <strong><?= htmlspecialchars($_SESSION['temp_email']) ?></strong>.</p>
                
                <?php if(isset($_SESSION['debug_otp'])): ?>
                    <div class="alert alert-info small"><strong>Test OTP:</strong> <?= $_SESSION['debug_otp'] ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger fw-bold small"><?= $error ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>Auth/verify_otp" method="POST">
                    <div class="mb-4">
                        <input type="text" name="otp" class="form-control form-control-lg text-center fw-bold ls-widest" placeholder="------" required maxlength="6">
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill">XÁC NHẬN OTP</button>
                </form>
            </div>
        </div>
    </div>
</div>