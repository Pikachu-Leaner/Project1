<?php
require_once 'app/config/database.php';

class ProductModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProducts($categoryId = null, $sortBy = null) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id";
        $conditions = [];
        $params = [];

        if ($categoryId) {
            $conditions[] = "p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Apply sorting
        if ($sortBy) {
            switch ($sortBy) {
                case 'price_asc': $query .= " ORDER BY p.price ASC"; break;
                case 'price_desc': $query .= " ORDER BY p.price DESC"; break;
                default: $query .= " ORDER BY p.name ASC"; break; // Default sort
            }
        } else {
             $query .= " ORDER BY p.name ASC"; // Default sort
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // You can keep or update your existing deleteProduct method here
    public function deleteProduct($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
?>