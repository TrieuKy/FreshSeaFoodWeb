<?php
require_once __DIR__ . '/../config/database.php';

class OrderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Tạo đơn hàng mới, ghi order + order_items vào DB.
     * Trả về order_id nếu thành công, false nếu thất bại.
     */
    public function createOrder($userId, $totalPrice, $shippingFee, $cart, $name, $phone, $address, $payment, $discountAmount = 0, $note = '', $deliveryTime = '') {
        try {
            $this->conn->beginTransaction();

            // 1. Ghi vào bảng orders
            $stmt = $this->conn->prepare(
                "INSERT INTO orders (user_id, total_price, shipping_fee, discount_amount, customer_name, customer_phone, customer_address, payment_method, note, delivery_time)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$userId, $totalPrice, $shippingFee, $discountAmount, $name, $phone, $address, $payment, $note, $deliveryTime]);
            $orderId = $this->conn->lastInsertId();

            // 2. Ghi từng sản phẩm vào order_items
            $itemStmt = $this->conn->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price)
                 VALUES (?, ?, ?, ?, ?)"
            );
            foreach ($cart as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['name'],
                    $item['quantity'],
                    $item['price'],
                ]);
            }

            $this->conn->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('OrderModel::createOrder error: ' . $e->getMessage());
            return false;
        }
    }

    /** Lấy tất cả đơn hàng (cho admin) */
    public static function getAll() {
        $db   = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->query(
            "SELECT o.*, u.username
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             ORDER BY o.created_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy đơn hàng theo ID kèm chi tiết items */
    public static function getById($id) {
        $db   = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare(
            "SELECT o.*, u.username
             FROM orders o LEFT JOIN users u ON o.user_id = u.id
             WHERE o.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;

        $itemStmt = $conn->prepare(
            "SELECT oi.*, p.image FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?"
        );
        $itemStmt->execute([$id]);
        $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }

    /** Lấy đơn hàng theo user_id */
    public static function getByUser($userId) {
        $db   = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Cập nhật trạng thái đơn hàng */
    public static function updateStatus($id, $status) {
        $allowed = ['pending','confirmed','shipping','delivered','cancelled'];
        if (!in_array($status, $allowed)) return false;
        $db   = new Database();
        $conn = $db->getConnection();
        return $conn->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $id]);
    }

    /** Đếm đơn theo trạng thái */
    public static function countByStatus() {
        $db   = new Database();
        $conn = $db->getConnection();
        $rows = $conn->query("SELECT status, COUNT(*) as cnt FROM orders GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
        $result = ['pending'=>0,'confirmed'=>0,'shipping'=>0,'delivered'=>0,'cancelled'=>0];
        foreach ($rows as $r) $result[$r['status']] = $r['cnt'];
        return $result;
    }
}
?>
