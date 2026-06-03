<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function getConnection() {
        $this->conn = null;

        // Ưu tiên đọc biến môi trường từ Render. 
        // NẾU KHÔNG CÓ, tự động dùng thông tin của Aiven (Không dùng localhost nữa).
        $this->host = getenv('DB_HOST') ?: "mysql-17d9b8fc-nguyenha954-1a76.h.aivencloud.com";
        $this->db_name = getenv('DB_NAME') ?: "defaultdb";
        $this->username = getenv('DB_USER') ?: "avnadmin";
        
        $this->password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ""; 
        
        $this->port = getenv('DB_PORT') ?: "18116";

        try {
            // Thiết lập chuỗi kết nối DSN
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            
            // Cấu hình PDO: Ép buộc mã hóa SSL để vượt qua tường lửa của Aiven
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Cho phép Render kết nối Aiven mà không cần file ca.pem cứng
            );

            // Khởi tạo kết nối với tùy chọn SSL
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // In ra thông báo lỗi màu đỏ to rõ để bạn dễ dàng bắt bệnh nếu sai mật khẩu
            die("<div style='background:#f8d7da; color:#842029; padding:20px; margin:20px; border-radius:5px; border: 1px solid #f5c2c7; font-family:sans-serif;'>
                    <strong>Lỗi kết nối CSDL:</strong> <br>" . $exception->getMessage() . "
                 </div>");
        }
        
        return $this->conn;
    }
}
?>