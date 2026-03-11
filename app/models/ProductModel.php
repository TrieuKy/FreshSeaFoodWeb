<?php
require_once __DIR__ . '/../config/database.php';

class ProductModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả sản phẩm (JOIN category để lấy tên danh mục)
    public static function getAll() {
        $database = new Database();
        $conn = $database->getConnection();
        $query = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.is_active = 1
                  ORDER BY p.id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo ID
    public static function getById($id) {
        $database = new Database();
        $conn = $database->getConnection();
        $query = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm và lọc sản phẩm
    public static function search($keyword = '', $category = '', $minPrice = '', $maxPrice = '') {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.is_active = 1";
        $params = [];

        if (!empty($keyword)) {
            $query .= " AND p.name LIKE ?";
            $params[] = "%$keyword%";
        }

        // Hỗ trợ tìm theo slug (tom, cua, ca...) hoặc tên category
        if (!empty($category)) {
            $query .= " AND (c.slug = ? OR c.name = ? OR p.category_id = ?)";
            $params[] = $category;
            $params[] = $category;
            $params[] = $category;
        }

        if (!empty($minPrice)) {
            $query .= " AND p.price >= ?";
            $params[] = $minPrice;
        }

        if (!empty($maxPrice)) {
            $query .= " AND p.price <= ?";
            $params[] = $maxPrice;
        }

        $query .= " ORDER BY p.id DESC";

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo sản phẩm mới
    public function create($name, $description, $price, $category_id, $image, $stock = 0, $unit = 'kg') {
        $query = "INSERT INTO products (category_id, name, description, price, image, stock, unit)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$category_id, $name, $description, $price, $image, $stock, $unit]);
    }

    // Cập nhật sản phẩm
    public function update($id, $name, $description, $price, $category_id, $image = null, $stock = null) {
        if ($image) {
            $query = "UPDATE products SET name=?, description=?, price=?, category_id=?, image=?, stock=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $description, $price, $category_id, $image, $stock, $id]);
        } else {
            $query = "UPDATE products SET name=?, description=?, price=?, category_id=?, stock=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $description, $price, $category_id, $stock, $id]);
        }
    }

    // Xóa sản phẩm
    public function delete($id) {
        $query = "DELETE FROM products WHERE id=?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>