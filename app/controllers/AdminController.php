<?php
require_once 'app/config/database.php';

class AdminController {
    private $conn;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->checkAuthorization('Admin'); // Chỉ Admin mới được truy cập
        
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ----------------------------------------------------
    // AUTHORIZATION MODULE: Kiểm tra quyền (Permissions)
    // ----------------------------------------------------
    private function checkAuthorization($requiredRole) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'Auth/login');
            exit;
        }
        if ($_SESSION['user_role'] !== $requiredRole) {
            die("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h1>Lỗi 403</h1><p>Bạn không có quyền Client/Admin để truy cập khu vực này.</p></div>");
        }
    }

    public function dashboard() {
        // Tổng doanh thu
        $stmtRev = $this->conn->query("SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'Completed'");
        $revenue = $stmtRev->fetch()['revenue'] ?? 0;

        // Quản lý người dùng
        $stmtUsers = $this->conn->query("SELECT id, full_name, email, role, is_active FROM users ORDER BY id DESC");
        $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

        // Quản lý đơn hàng
        $stmtOrders = $this->conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
        $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/admin/dashboard.php';
    }

    public function toggleUserStatus($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ? AND role != 'Admin'");
        $stmt->execute([$userId]);
        header('Location: ' . BASE_URL . 'Admin/dashboard');
        exit;
    }
}
?>