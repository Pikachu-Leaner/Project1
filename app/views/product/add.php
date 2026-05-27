<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Thêm Sản Phẩm Mới</h5>
            </div>
            <div class="card-body p-4">
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="Nhập tên điện thoại...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                        <select class="form-select" name="brand" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            <option value="SAMSUNG">SAMSUNG</option>
                            <option value="iPhone">iPhone</option>
                            <option value="xiaomi">xiaomi</option>
                            <option value="OPPO">OPPO</option>
                            <option value="vivo">vivo</option>
                            <option value="realme">realme</option>
                            <option value="HONOR">HONOR</option>
                            <option value="motorola">motorola</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" required min="1" placeholder="Ví dụ: 15000000">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Hình ảnh sản phẩm</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <div class="form-text">Định dạng hỗ trợ: JPG, PNG, GIF.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Thêm sản phẩm</button>
                        <a href="<?= BASE_URL ?>Product/list" class="btn btn-light border px-4 fw-bold">Hủy bỏ</a>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>