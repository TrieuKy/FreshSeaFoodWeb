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

    // Hiển thị form chỉnh sửa hồ sơ
    public function edit_profile() {
        $this->requireLogin();
        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $user = $userModel->getById($_SESSION['user_id']);
        include 'app/views/customer/edit_profile.php';
    }

    // Xử lý cập nhật hồ sơ
    public function update_profile() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /banhaisan/customer/edit_profile');
            exit;
        }

        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $id = $_SESSION['user_id'];
        
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $gender = $_POST['gender'] ?? null;
        
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $target_dir = 'public/images/avatars/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $avatar = time() . '_' . basename($_FILES['avatar']['name']);
            move_uploaded_file($_FILES['avatar']['tmp_name'], $target_dir . $avatar);
        }

        if ($userModel->updateProfile($id, $fullname, $phone, $address, $gender, $avatar)) {
            $_SESSION['fullname'] = $fullname;
            if ($avatar) $_SESSION['avatar'] = $avatar;
            echo "<script>alert('Cập nhật hồ sơ thành công!'); window.location.href='/banhaisan/customer/profile';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại.'); window.history.back();</script>";
        }
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

    // Gửi đánh giá
    public function submit_review() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /banhaisan');
            exit;
        }

        $product_id = intval($_POST['product_id'] ?? 0);
        $order_id   = intval($_POST['order_id'] ?? 0);
        $rating     = intval($_POST['rating'] ?? 5);
        $comment    = trim($_POST['comment'] ?? '');
        $user_id    = $_SESSION['user_id'];

        if ($product_id && $order_id && $rating >= 1 && $rating <= 5) {
            require_once 'app/models/ProductModel.php';
            // Verify if still eligible
            $eligibleOrderId = ProductModel::canUserReview($user_id, $product_id);
            if ($eligibleOrderId && $eligibleOrderId == $order_id) {
                if (ProductModel::addReview($user_id, $product_id, $order_id, $rating, $comment)) {
                    echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location.href='/banhaisan/product/detail/$product_id';</script>";
                    exit;
                }
            }
        }
        
        echo "<script>alert('Có lỗi xảy ra, không thể gửi đánh giá.'); window.history.back();</script>";
    }
}
?>
