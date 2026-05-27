<?php
require_once 'app/models/ProductModel.php';

class CartController {
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->productModel = new ProductModel();
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    }

    // Hiển thị giao diện Giỏ hàng
    public function index() {
        $cartItems = [];
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $product['quantity'] = $quantity;
                $cartItems[] = $product;
            }
        }
        include 'app/views/cart/cart.php';
    }

    // Nút "Thêm vào giỏ" bình thường (ở lại trang)
    public function add($id) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
        header("Location: " . BASE_URL . "Cart/index#cart-section");
        exit;
    }

    // MỚI: Nút "Mua Ngay" (Chuyển thẳng qua trang Thanh Toán)
    public function buyNow($id) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
        header("Location: " . BASE_URL . "Order/checkout");
        exit;
    }

    // Tăng giảm số lượng trong giỏ hàng
    public function update($id, $action) {
        if (isset($_SESSION['cart'][$id])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$id]++;
            } elseif ($action === 'decrease') {
                $_SESSION['cart'][$id]--;
                if ($_SESSION['cart'][$id] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }
        header("Location: " . BASE_URL . "Cart/index#cart-section");
        exit;
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: " . BASE_URL . "Cart/index#cart-section");
        exit;
    }
}
?>