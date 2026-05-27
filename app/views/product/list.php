<?php
$currSort = isset($_GET['sort']) ? $_GET['sort'] : 'noi_bat';
$currBrand = isset($_GET['brand']) ? $_GET['brand'] : ''; 
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border border-light gap-3">
    <h5 class="text-uppercase fw-bold m-0 text-secondary">Danh mục</h5>
    
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center">
            <span class="text-muted small me-2 text-nowrap">Sắp xếp:</span>
            <?php $baseParams = "?brand=" . urlencode($currBrand) . "&category=" . urlencode($_GET['category'] ?? '') . "&sort="; ?>
            <select class="form-select form-select-sm shadow-sm border-secondary" style="min-width: 150px; font-weight: bold;" onchange="window.location.href='<?= BASE_URL ?>Product/list<?= $baseParams ?>' + this.value + '#shop-section'">
                <option value="noi_bat" <?= $currSort == 'noi_bat' ? 'selected' : '' ?>>Nổi bật</option>
                <option value="ban_chay" <?= $currSort == 'ban_chay' ? 'selected' : '' ?>>Bán chạy</option>
                <option value="moi" <?= $currSort == 'moi' ? 'selected' : '' ?>>Mới nhất</option>
                <option value="gia_tang" <?= $currSort == 'gia_tang' ? 'selected' : '' ?>>Giá thấp đến cao</option>
                <option value="gia_giam" <?= $currSort == 'gia_giam' ? 'selected' : '' ?>>Giá cao đến thấp</option>
            </select>
        </div>

        <a href="<?= BASE_URL ?>Product/add" class="btn btn-warning fw-bold shadow-sm text-nowrap">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="list-group shadow-sm">
            <a href="<?= BASE_URL ?>Product/list?sort=<?= $currSort ?>&brand=<?= urlencode($currBrand) ?>#shop-section" 
               class="list-group-item list-group-item-action fw-bold <?= empty($_GET['category']) ? 'active' : '' ?>">
                Tất cả
            </a>
            <?php if(isset($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <a href="<?= BASE_URL ?>Product/list?category=<?= $category['id'] ?>&sort=<?= $currSort ?>&brand=<?= urlencode($currBrand) ?>#shop-section" 
                       class="list-group-item list-group-item-action fw-bold <?= (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-9">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-light position-relative product-card">
                            <a href="<?= BASE_URL ?>Product/detail/<?= $product['id'] ?>" class="text-decoration-none">
                                <div class="position-relative text-center p-3">
                                    <img src="<?= BASE_URL . htmlspecialchars($product['image_url']) ?>" 
                                         class="card-img-top img-fluid" alt="<?= htmlspecialchars($product['name']) ?>"
                                         style="max-height: 180px; object-fit: contain;">
                                </div>
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate fw-bold mb-3" title="<?= htmlspecialchars($product['name']) ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </h6>
                                <div class="mt-auto mb-3">
                                    <p class="text-danger fw-bold fs-5 mb-0"><?= number_format($product['price'], 0, ',', '.') ?> ₫</p>
                                    <?php if (!empty($product['old_price'])): ?>
                                        <small class="text-muted text-decoration-line-through"><?= number_format($product['old_price'], 0, ',', '.') ?> ₫</small>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2 pt-3 border-top mt-auto">
                                    <a href="<?= BASE_URL ?>Cart/add/<?= $product['id'] ?>" class="btn btn-outline-primary btn-sm flex-fill" title="Thêm vào giỏ">
                                        <i class="fas fa-cart-plus"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>Product/edit/<?= $product['id'] ?>" class="btn btn-outline-secondary btn-sm flex-fill" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 d-flex justify-content-center align-items-center text-center py-5 w-100" style="min-height: 250px;">
                    <h4 class="text-muted m-0"><i class="fas fa-search me-2"></i> Không tìm thấy sản phẩm nào phù hợp.</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fw-bold" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Xác nhận xóa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4 text-center">
        <p class="fs-5 mb-2">Bạn có chắc chắn muốn xóa sản phẩm này?</p>
        <p class="fw-bold text-danger fs-5 mb-3" id="deleteProductName"></p>
        <p class="text-muted small mb-0">Hành động này sẽ xóa dữ liệu khỏi hệ thống và không thể hoàn tác.</p>
      </div>
      <div class="modal-footer justify-content-center bg-light">
        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Hủy bỏ</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger px-4 fw-bold">Đồng ý Xóa</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteProductName').textContent = button.getAttribute('data-name');
            document.getElementById('confirmDeleteBtn').href = '<?= BASE_URL ?>Product/delete/' + button.getAttribute('data-id');
        });
    }
});
</script>