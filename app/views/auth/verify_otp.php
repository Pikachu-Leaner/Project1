<?php
// Fix chống lỗi sập trang khi rớt Session trên Render
$emailToShow = isset($_SESSION['temp_email']) ? $_SESSION['temp_email'] : 'email của bạn';
$otpToShow = isset($_SESSION['debug_otp']) ? $_SESSION['debug_otp'] : 'Lỗi: Không tìm thấy OTP (Rớt Session)';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <div class="card shadow-sm border-0 rounded-3 p-5">
                <i class="fas fa-envelope-open-text text-warning mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mb-3">Xác thực Email</h4>
                
                <p class="text-muted small">Chúng tôi đã gửi mã OTP 6 số đến <strong><?= htmlspecialchars($emailToShow) ?></strong>.</p>
                
                <div class="alert alert-info border-info fw-bold mb-4">
                    <div class="small text-muted fw-normal mb-1">MÃ OTP TEST (KHÔNG GỬI MAIL):</div>
                    <span style="font-size: 1.5rem; letter-spacing: 5px;"><?= htmlspecialchars($otpToShow) ?></span>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger fw-bold small"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>Auth/verify_otp" method="POST">
                    <div class="mb-4">
                        <input type="text" name="otp" class="form-control form-control-lg text-center fw-bold ls-widest" placeholder="------" required maxlength="6">
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill text-dark">XÁC NHẬN OTP</button>
                </form>
            </div>
        </div>
    </div>
</div>