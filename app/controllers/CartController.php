<?php
require_once 'app/models/ProductModel.php';

class CartController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        // Khởi tạo session giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Hiển thị giỏ hàng
    public function index() {
        $cartItems = [];
        
        // Duyệt qua session giỏ hàng để lấy thông tin chi tiết từ Database
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $product['quantity'] = $quantity;
                $cartItems[] = $product;
            }
        }
        
        include 'app/views/layouts/header.php';
        // Đã đổi tên file từ index.php thành cart.php theo yêu cầu
        include 'app/views/cart/cart.php'; 
        include 'app/views/layouts/footer.php';
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add($id) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1; // Thêm mới với số lượng 1
        } else {
            $_SESSION['cart'][$id]++;   // Tăng số lượng nếu đã có
        }
        // Chuyển hướng đến trang giỏ hàng sau khi thêm
        header("Location: /Cart/index");
        exit;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: /Cart/index");
        exit;
    }
}
?>