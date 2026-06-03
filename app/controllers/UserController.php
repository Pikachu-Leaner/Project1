<?php
require_once 'app/config/database.php';

class UserController {
    private $conn;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'Auth/login');
            exit;
        }
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['full_name']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $avatarPath = $_SESSION['user_avatar'];

            // Xử lý Upload Avatar
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/profiles/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                
                $fileInfo = pathinfo($_FILES['avatar']['name']);
                $imageName = 'user_' . $userId . '_' . time() . '.' . $fileInfo['extension'];
                
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $imageName)) {
                    $avatarPath = 'public/images/profiles/' . $imageName;
                    $_SESSION['user_avatar'] = $avatarPath; // Cập nhật session
                }
            }

            $stmt = $this->conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $address, $avatarPath, $userId]);
            $_SESSION['user_name'] = $name;
            $success = "Cập nhật hồ sơ thành công!";
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        include 'app/views/user/profile.php';
    }
}
?>