<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';

class OrderController {
    private $conn;
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->productModel = new ProductModel();
    }

    // Giao diện Thanh toán (Order and Payment Page)
    public function checkout() {
        // Nếu giỏ hàng trống, đuổi về trang giỏ hàng
        if (empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . 'Cart/index');
            exit;
        }

        // Tính toán tổng tiền từ giỏ hàng
        $cartItems = [];
        $totalAmount = 0;
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $product['quantity'] = $quantity;
                $cartItems[] = $product;
                $totalAmount += ($product['price'] * $quantity);
            }
        }

        // XỬ LÝ KHI NGƯỜI DÙNG BẤM "XÁC NHẬN THANH TOÁN" TRONG POP-UP
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['customer_name']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $payment_method = $_POST['payment_method'];
            $notes = trim($_POST['notes']);

            // 1. Lưu vào bảng `orders`
            $stmt = $this->conn->prepare("INSERT INTO orders (customer_name, phone, address, payment_method, notes, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $address, $payment_method, $notes, $totalAmount]);
            
            // Lấy ID của đơn hàng vừa tạo
            $orderId = $this->conn->lastInsertId();

            // 2. Lưu từng sản phẩm vào bảng `order_details`
            $stmtDetail = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmtDetail->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }

            // 3. Xóa sạch giỏ hàng
            unset($_SESSION['cart']);
            
            // 4. Chuyển hướng sang trang Lịch sử đơn hàng kèm thông báo thành công
            header('Location: ' . BASE_URL . 'Order/history?success=1');
            exit;
        }

        // Gọi giao diện form thanh toán
        include 'app/views/order/checkout.php';
    }

    // Giao diện Lịch sử mua hàng
    public function history() {
        $stmt = $this->conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/order/history.php';
    }
}
?>