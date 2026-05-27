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

    public function checkout() {
        if (empty($_SESSION['cart'])) {
            header('Location: /Cart/index');
            exit;
        }

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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['customer_name']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $payment_method = $_POST['payment_method'];
            $notes = trim($_POST['notes']);

            // Insert into orders table
            $stmt = $this->conn->prepare("INSERT INTO orders (customer_name, phone, address, payment_method, notes, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $address, $payment_method, $notes, $totalAmount]);
            $orderId = $this->conn->lastInsertId();

            // Insert into order_details table
            $stmtDetail = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmtDetail->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }

            // Clear cart
            unset($_SESSION['cart']);
            
            // Redirect to history
            header('Location: /Order/history?success=1');
            exit;
        }

        include 'app/views/order/checkout.php';
    }

    public function history() {
        $stmt = $this->conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/order/history.php';
    }
}
?>