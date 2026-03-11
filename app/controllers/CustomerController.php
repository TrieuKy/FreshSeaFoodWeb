<?php
require_once 'app/models/ProductModel.php';
require_once 'app/models/OrderModel.php';

class CustomerController {

    private function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
            header('Location: /banhaisan/auth/login_customer');
            exit;
        }
    }

    // Trang hồ sơ + lịch sử đơn hàng
    public function profile() {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $orders = OrderModel::getByUser($userId);
        include 'app/views/customer/profile.php';
    }

    // Chi tiết 1 đơn hàng của khách
    public function order_detail() {
        $this->requireLogin();
        $id    = intval($_GET['id'] ?? 0);
        $order = $id ? OrderModel::getById($id) : null;
        // Chỉ cho xem đơn hàng của chính mình
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header('Location: /banhaisan/customer/profile');
            exit;
        }
        include 'app/views/customer/order_detail.php';
    }

    // Kiểm tra voucher (AJAX)
    public function check_voucher() {
        header('Content-Type: application/json');
        $code  = trim($_POST['code'] ?? '');
        $total = floatval($_POST['total'] ?? 0);

        if (empty($code)) {
            echo json_encode(['valid' => false, 'message' => 'Vui long nhap ma voucher']);
            return;
        }

        $db   = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare(
            "SELECT * FROM vouchers WHERE code = ? AND is_active = 1
             AND (expires_at IS NULL OR expires_at >= CURDATE()) LIMIT 1"
        );
        $stmt->execute([$code]);
        $voucher = $stmt->fetch();

        if (!$voucher) {
            echo json_encode(['valid' => false, 'message' => 'Ma voucher khong hop le hoac da het han']);
            return;
        }

        if ($total < $voucher['min_order_value']) {
            echo json_encode([
                'valid'   => false,
                'message' => 'Don hang toi thieu ' . number_format($voucher['min_order_value'], 0, ',', '.') . 'd de ap dung ma nay',
            ]);
            return;
        }

        if ($voucher['discount_type'] === 'percent') {
            $discount = $total * $voucher['discount_value'] / 100;
        } else {
            $discount = $voucher['discount_value'];
        }
        $discount = min($discount, $total); // Không giảm quá tổng

        echo json_encode([
            'valid'          => true,
            'message'        => 'Ap dung thanh cong! Giam ' . ($voucher['discount_type'] === 'percent' ? $voucher['discount_value'].'%' : number_format($voucher['discount_value'],0,',','.').'d'),
            'discount'       => $discount,
            'discount_type'  => $voucher['discount_type'],
            'discount_value' => $voucher['discount_value'],
            'voucher_id'     => $voucher['id'],
        ]);
    }
}
?>
