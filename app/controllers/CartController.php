<?php
require_once 'app/models/ProductModel.php';

class CartController {
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->productModel = new ProductModel();
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    }

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

    public function add($id) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
        // FIX: Thêm #cart-section để tránh nhảy trang
        header("Location: /Cart/index#cart-section");
        exit;
    }

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
        // FIX: Thêm #cart-section để tránh nhảy trang
        header("Location: /Cart/index#cart-section");
        exit;
    }

    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        // FIX: Thêm #cart-section
        header("Location: /Cart/index#cart-section");
        exit;
    }
}
?>