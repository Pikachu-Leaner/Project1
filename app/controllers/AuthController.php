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

    // ==========================================
    // ĐĂNG NHẬP
    // ==========================================
    public function login() {
        if (isset($_GET['reset']) && $_GET['reset'] == 'success') {
            $success = "Đổi mật khẩu thành công! Vui lòng đăng nhập lại.";
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $remember = isset($_POST['remember']);

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra mật khẩu
            if ($user && password_verify($password, $user['password'])) {
                if (!$user['is_active']) {
                    $error = "Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.";
                } elseif (!$user['is_verified']) {
                    $_SESSION['temp_email'] = $email;
                    header('Location: ' . BASE_URL . 'Auth/verify_otp');
                    exit;
                } else {
                    // Đăng nhập thành công
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_avatar'] = $user['avatar'];

                    // Xử lý Remember Me
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (86400 * 30), "/"); 
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

    // ==========================================
    // ĐĂNG KÝ (TẠO OTP Ở ĐÂY)
    // ==========================================
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // LOGIC TẠO OTP
            $otp = rand(100000, 999999); 

            try {
                $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password, otp_code) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $password, $otp]);
                
                // ------------------------------------------------------------------
                // ĐÂY LÀ NƠI GỬI EMAIL THỰC TẾ (Nếu bạn cài PHPMailer sau này)
                // ------------------------------------------------------------------
                // $mail = new PHPMailer(true);
                // $mail->addAddress($email);
                // $mail->Subject = 'Mã xác thực tài khoản';
                // $mail->Body    = 'Mã OTP của bạn là: ' . $otp;
                // $mail->send();
                
                $_SESSION['temp_email'] = $email;
                $_SESSION['debug_otp'] = $otp; // Xóa dòng này nếu chạy thực tế
                
                header('Location: ' . BASE_URL . 'Auth/verify_otp');
                exit;
            } catch(PDOException $e) {
                $error = "Email này đã được sử dụng!";
            }
        }
        include 'app/views/auth/register.php';
    }

    // ==========================================
    // XÁC THỰC OTP KHI ĐĂNG KÝ
    // ==========================================
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
                unset($_SESSION['debug_otp']);
                $success = "Xác thực thành công! Bạn có thể đăng nhập.";
            } else {
                $error = "Mã OTP không hợp lệ!";
            }
        }
        include 'app/views/auth/verify_otp.php';
    }

    // ==========================================
    // QUÊN MẬT KHẨU (TẠO OTP Ở ĐÂY)
    // ==========================================
    public function forgot_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // LOGIC TẠO OTP CHO QUÊN MẬT KHẨU
                $otp = rand(100000, 999999);
                $upd = $this->conn->prepare("UPDATE users SET otp_code = ? WHERE id = ?");
                $upd->execute([$otp, $user['id']]);

                // ------------------------------------------------------------------
                // ĐÂY CŨNG LÀ NƠI GỬI EMAIL THỰC TẾ 
                // ------------------------------------------------------------------
                // mail($email, "Khôi phục mật khẩu", "Mã OTP của bạn là: " . $otp);

                $_SESSION['reset_email'] = $email;
                $_SESSION['debug_otp'] = $otp; // Xóa dòng này nếu chạy thực tế
                
                header('Location: ' . BASE_URL . 'Auth/reset_password');
                exit;
            } else {
                $error = "Email không tồn tại hoặc tài khoản đã bị khóa!";
            }
        }
        include 'app/views/auth/forgot_password.php';
    }

    // ==========================================
    // NHẬP MẬT KHẨU MỚI (TỪ FORGOT PASSWORD)
    // ==========================================
    public function reset_password() {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: ' . BASE_URL . 'Auth/forgot_password');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp']);
            $new_password = $_POST['new_password'];
            $email = $_SESSION['reset_email'];

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND otp_code = ?");
            $stmt->execute([$email, $otp]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $upd = $this->conn->prepare("UPDATE users SET password = ?, otp_code = NULL WHERE id = ?");
                $upd->execute([$hashed_password, $user['id']]);

                unset($_SESSION['reset_email']);
                unset($_SESSION['debug_otp']);

                header('Location: ' . BASE_URL . 'Auth/login?reset=success');
                exit;
            } else {
                $error = "Mã OTP không hợp lệ!";
            }
        }
        include 'app/views/auth/reset_password.php';
    }

    // ==========================================
    // ĐĂNG XUẤT
    // ==========================================
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $stmt = $this->conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        }
        setcookie('remember_token', '', time() - 3600, "/");
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }

    // ==========================================
    // HỖ TRỢ REMEMBER ME
    // ==========================================
    private function authorizeFromCookie() {
        $token = $_COOKIE['remember_token'];
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE remember_token = ? AND is_active = 1");
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