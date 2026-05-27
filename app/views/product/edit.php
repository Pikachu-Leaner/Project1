<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i>Cập Nhật Sản Phẩm</h5>
            </div>
            <div class="card-body p-4">
                
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($product['name']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                        <select class="form-select" name="brand" required>
                            <?php 
                            $brands = ['SAMSUNG', 'iPhone', 'xiaomi', 'OPPO', 'vivo', 'realme', 'HONOR', 'motorola'];
                            foreach ($brands as $b): 
                            ?>
                                <option value="<?= $b ?>" <?= ($product['brand'] === $b) ? 'selected' : '' ?>>
                                    <?= $b ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" required min="1" value="<?= $product['price'] ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Cập nhật hình ảnh (Tùy chọn)</label>
                        
                        <div class="mb-2">
                            <p class="mb-1 text-muted small">Ảnh hiện tại:</p>
                            <img src="<?= BASE_URL . htmlspecialchars($product['image_url']) ?>" alt="Current Image" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                        
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <div class="form-text">Chỉ chọn ảnh mới nếu bạn muốn thay đổi ảnh hiện tại.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Lưu thay đổi</button>
                        <a href="<?= BASE_URL ?>Product/list" class="btn btn-light border px-4 fw-bold">Hủy bỏ</a>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>