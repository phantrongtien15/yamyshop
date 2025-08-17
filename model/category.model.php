<?php
include_once __DIR__ . "/../config/db.php";

class Category {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    /**
     * Lấy tất cả danh mục
     * @return array
     */
    public function getAllCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    /**
     * Lấy tên danh mục theo ID
     * @param int $id
     * @return string|null
     */
    public function getCategoryNameById($id) {
        $query = "SELECT name FROM categories WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = $result->fetch_assoc();
        return $data ? $data['name'] : null;
    }
}
?>
