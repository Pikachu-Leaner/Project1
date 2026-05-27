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

        // Lấy thông tin từ Cloud (Render), nếu không có thì dùng Local (Laragon)
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->db_name = getenv('DB_NAME') ?: "electronic_store";
        $this->username = getenv('DB_USER') ?: "root";
        $this->password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
        $this->port = getenv('DB_PORT') ?: "3306"; // 3306 là port mặc định của Laragon

        try {
            // Đã thêm Port vào chuỗi kết nối
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            echo "Lỗi kết nối CSDL: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>