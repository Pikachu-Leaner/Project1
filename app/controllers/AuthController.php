<?php
require_once 'app/config/database.php';

class AuthController {
    private $conn;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $db = new Database();
        $this->conn = $db->getConnection();
        
        // Kiểm tra Remember Me Cookie nếu chưa đăng nhập
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
            $this->authorizeFromCookie();
        }
    }

    // ----------------------------------------------------
    // AUTHORIZE MODULE: Xác định danh tính người dùng
    // ----------------------------------------------------
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $remember = isset($_POST['remember']);

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                if (!$user['is_active']) {
                    $error = "Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.";
                } elseif (!$user['is_verified']) {
                    $_SESSION['temp_email'] = $email;
                    header('Location: ' . BASE_URL . 'Auth/verify_otp');
                    exit;
                } else {
                    // Cấp phiên bản quyền (Authorize)
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_avatar'] = $user['avatar'];

                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (86400 * 30), "/"); // 30 ngày
                        $upd = $this->conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                        $upd->execute([$token, $user['id']]);
                    }

                    header('Location: ' . BASE_URL);
                    exit;
                }
            } else {
                $error = "Email hoặc mật khẩu không chính xác!";
            }
        }
        include 'app/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $otp = rand(100000, 999999); // Mã OTP 6 số

            try {
                $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password, otp_code) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $password, $otp]);
                
                // Giả lập gửi email (Trong thực tế dùng PHPMailer)
                // mail($email, "Mã xác thực tài khoản", "Mã OTP của bạn là: " . $otp);
                
                $_SESSION['temp_email'] = $email;
                $_SESSION['debug_otp'] = $otp; // Dùng để test trên Localhost Laragon
                
                header('Location: ' . BASE_URL . 'Auth/verify_otp');
                exit;
            } catch(PDOException $e) {
                $error = "Email này đã được sử dụng!";
            }
        }
        include 'app/views/auth/register.php';
    }

    public function verify_otp() {
        if (!isset($_SESSION['temp_email'])) { header('Location: ' . BASE_URL . 'Auth/login'); exit; }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp']);
            $email = $_SESSION['temp_email'];

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND otp_code = ?");
            $stmt->execute([$email, $otp]);
            $user = $stmt->fetch();

            if ($user) {
                $upd = $this->conn->prepare("UPDATE users SET is_verified = TRUE, otp_code = NULL WHERE id = ?");
                $upd->execute([$user['id']]);
                unset($_SESSION['temp_email']);
                $success = "Xác thực thành công! Bạn có thể đăng nhập.";
            } else {
                $error = "Mã OTP không hợp lệ!";
            }
        }
        include 'app/views/auth/verify_otp.php';
    }

    public function logout() {
        setcookie('remember_token', '', time() - 3600, "/");
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }

    private function authorizeFromCookie() {
        $token = $_COOKIE['remember_token'];
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE remember_token = ? AND is_active = TRUE");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_avatar'] = $user['avatar'];
        }
    }
}
?>