<?php
// Bổ sung file kết nối Database
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php'; 

class ProductController
{
    private $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // KẾT NỐI DATABASE THỰC TẾ
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index() { 
        $this->list(); 
    }

    public function list()
    {
        // 1. [FIXED] LẤY DANH SÁCH DANH MỤC TỪ DATABASE
        $stmt_cat = $this->conn->prepare("SELECT * FROM categories ORDER BY id ASC");
        $stmt_cat->execute();
        $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

        // 2. Lấy tham số Lọc và Sắp xếp từ URL
        $brand = isset($_GET['brand']) ? $_GET['brand'] : '';
        $catId = isset($_GET['category']) ? $_GET['category'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'noi_bat';

        // 3. Xây dựng câu lệnh SQL động
        // Chú ý: Cần join với bảng categories nếu muốn lọc theo category_id
        $query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id";
        
        $conditions = [];
        $params = [];

        if (!empty($brand)) {
            $conditions[] = "p.brand = :brand";
            $params[':brand'] = $brand;
        }
        
        if (!empty($catId)) {
            $conditions[] = "p.category_id = :catId";
            $params[':catId'] = $catId;
        }

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        // 4. Áp dụng logic Sắp xếp
        switch ($sort) {
            case 'ban_chay':
                $query .= " ORDER BY p.sales_count DESC";
                break;
            case 'moi':
                $query .= " ORDER BY p.created_at DESC";
                break;
            case 'gia_tang':
                $query .= " ORDER BY p.price ASC";
                break;
            case 'gia_giam':
                $query .= " ORDER BY p.price DESC";
                break;
            case 'noi_bat':
            default:
                $query .= " ORDER BY p.is_featured DESC, p.id DESC";
                break;
        }

        // 5. Thực thi truy vấn và lấy dữ liệu
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Chuyển dữ liệu sang View (Bây giờ cả $products và $categories đều tồn tại)
        include 'app/views/product/list.php';
    }

    public function detail($id = null)
    {
        if ($id === null) {
            header('Location: /Product/list');
            exit();
        }

        $stmt = $this->conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die('<div style="padding:20px; font-family:sans-serif;"><h1>Lỗi 404</h1><p>Sản phẩm không tồn tại trong hệ thống.</p></div>');
        }

        $stmt_rel = $this->conn->prepare("SELECT * FROM products WHERE brand = :brand AND id != :prodId LIMIT 4");
        $stmt_rel->execute([':brand' => $product['brand'], ':prodId' => $product['id']]);
        $relatedProducts = $stmt_rel->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/product/detail.php';
    }

    public function add()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $price = $_POST['price'];
            $brand = isset($_POST['brand']) ? trim($_POST['brand']) : 'SAMSUNG'; 

            if (empty($name)) $errors[] = 'Tên sản phẩm là bắt buộc.';
            if (!is_numeric($price) || $price <= 0) $errors[] = 'Giá bán phải lớn hơn 0.';

            $imagePath = 'public/images/Phone-card-image-1.jpg'; 
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileInfo = pathinfo($_FILES['image']['name']);
                $cleanName = preg_replace("/[^a-zA-Z0-9]/", "", $fileInfo['filename']);
                $imageName = time() . '_' . $cleanName . '.' . $fileInfo['extension'];
                $uploadFullPath = $uploadDir . $imageName;
                
                if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFullPath)) {
                    $imagePath = 'public/images/' . $imageName;
                }
            }

            if (empty($errors)) {
                $query = "INSERT INTO products (name, brand, price, image_url) VALUES (:name, :brand, :price, :image_url)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':name' => $name,
                    ':brand' => $brand,
                    ':price' => $price,
                    ':image_url' => $imagePath
                ]);
                header('Location: /Product/list');
                exit();
            }
        }
        include 'app/views/product/add.php';
    }

    public function edit($id = null)
    {
        if ($id === null) {
            header('Location: /Product/list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $price = $_POST['price'];
            $brand = isset($_POST['brand']) ? trim($_POST['brand']) : 'SAMSUNG';

            $query = "UPDATE products SET name = :name, brand = :brand, price = :price";
            $params = [':name' => $name, ':brand' => $brand, ':price' => $price, ':id' => $id];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/';
                $fileInfo = pathinfo($_FILES['image']['name']);
                $cleanName = preg_replace("/[^a-zA-Z0-9]/", "", $fileInfo['filename']);
                $imageName = time() . '_' . $cleanName . '.' . $fileInfo['extension'];
                $uploadFullPath = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFullPath)) {
                    $query .= ", image_url = :image_url";
                    $params[':image_url'] = 'public/images/' . $imageName;
                }
            }

            $query .= " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            header('Location: /Product/list');
            exit();
        }

        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die('Product not found in database.');
        }

        include 'app/views/product/edit.php';
    }

    public function delete($id = null)
    {
        if ($id === null) {
            header('Location: /Product/list');
            exit();
        }

        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: /Product/list');
        exit();
    }
}
?>