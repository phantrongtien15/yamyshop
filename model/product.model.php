<?php
include_once __DIR__ . "/../config/db.php";

class Product {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    /**
     * Lấy tất cả sản phẩm
     * @return array
     */
    public function getAllProducts() {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    /**
     * Lấy sản phẩm theo ID
     * @param int $id
     * @return array|null
     */
    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /**
     * Lấy sản phẩm ngẫu nhiên (trừ sản phẩm hiện tại nếu có)
     * @param int $limit
     * @param int|null $excludeId
     * @return array
     */
    public function getRandomProducts($limit = 4, $excludeId = null) {
        if ($excludeId !== null) {
            $query = "SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) return [];

            $stmt->bind_param("ii", $excludeId, $limit);
        } else {
            $query = "SELECT * FROM products ORDER BY RAND() LIMIT ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) return [];

            $stmt->bind_param("i", $limit);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    /**
     * Lấy sản phẩm theo danh mục
     * @param int $categoryId
     * @return array
     */
    public function getProductsByCategory($categoryId) {
        $query = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    /**
     * Đếm tất cả sản phẩm
     * @return int
     */
    public function countAllProducts() {
        $query = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
return $data['total'] ?? 0;
    }

    /**
     * Đếm sản phẩm theo danh mục
     * @param int $categoryId
     * @return int
     */
    public function countProductsByCategory($categoryId) {
        $query = "SELECT COUNT(*) as total FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['total'] ?? 0;
    }

    /**
     * Lấy tất cả sản phẩm (có phân trang)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllProductsPaginated($limit, $offset) {
        $query = "SELECT * FROM products LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    /**
     * Lấy sản phẩm theo danh mục (có phân trang)
     * @param int $categoryId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getProductsByCategoryPaginated($categoryId, $limit, $offset) {
        $query = "SELECT * FROM products WHERE category_id = ? LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $categoryId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }
}
?>