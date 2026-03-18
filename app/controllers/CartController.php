<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class CartController {

    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        
        $relatedProducts = ProductModel::getAll();

        include 'app/views/cart/index.php';
    }

    public function add() {
        $args = func_get_args();
        $id   = $args[0] ?? null;
        $qty  = intval($_GET['qty'] ?? 1);
        if ($qty < 1) $qty = 1;

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!$id) {
            if ($isAjax) { echo json_encode(['success'=>false,'message'=>'Không tìm thấy sản phẩm']); return; }
            header('Location: /banhaisan'); return;
        }

        $product = ProductModel::getById($id);
        if (!$product) {
            if ($isAjax) { echo json_encode(['success'=>false,'message'=>'Sản phẩm không tồn tại']); return; }
            header('Location: /banhaisan'); return;
        }

        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        $currentQty = $_SESSION['cart'][$id]['quantity'] ?? 0;
        $newQty = $currentQty + $qty;

        if ($product['stock'] < $newQty) {
            $msg = 'Sản phẩm chỉ còn ' . $product['stock'] . ' ' . ($product['unit'] ?? 'kg');
            if ($isAjax) { echo json_encode(['success'=>false,'message'=>$msg]); return; }
            echo "<script>alert('$msg'); window.history.back();</script>";
            return;
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $newQty;
        } else {
            $_SESSION['cart'][$id] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price'],
                'image'    => $product['image'],
                'quantity' => $qty,
            ];
        }

        $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success'    => true,
                'message'    => '✅ Đã thêm <strong>' . htmlspecialchars($product['name']) . '</strong> vào giỏ hàng!',
                'cart_count' => count($_SESSION['cart']),
                'product'    => ['name' => $product['name'], 'image' => $product['image']],
            ]);
            return;
        }

        // Fallback: redirect về trang trước
        $ref = $_SERVER['HTTP_REFERER'] ?? '/banhaisan';
        header('Location: ' . $ref);
    }

    public function delete() {
        $args = func_get_args();
        $id = $args[0] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /banhaisan/cart/index');
    }

    public function update() {
        $args = func_get_args();
        $id = $args[0] ?? null;
        $qty = intval($_GET['qty'] ?? 1);
        if ($id && isset($_SESSION['cart'][$id])) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id]['quantity'] = $qty;
            }
        }
        header('Location: /banhaisan/cart/index');
    }

    public function clear() {
        unset($_SESSION['cart']);
        header('Location: /banhaisan/cart/index');
    }

    public function checkout() {
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location.href='/banhaisan/auth/login_customer';</script>";
            return;
        }

        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'customer') {
            echo "<script>alert('Tài khoản quản trị viên/nhân viên không thể đặt hàng!'); window.history.back();</script>";
            return;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            echo "<script>alert('Giỏ hàng trống!'); window.location.href='/banhaisan/cart/index';</script>";
            return;
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $shipping = $totalPrice >= 500000 ? 0 : 30000;

        include 'app/views/cart/checkout.php';
    }

    public function process_checkout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /banhaisan/cart/checkout');
            return;
        }

        if (!isset($_SESSION['username'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='/banhaisan/auth/login_customer';</script>";
            return;
        }

        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'customer') {
            echo "<script>alert('Tài khoản quản trị viên/nhân viên không thể đặt hàng!'); window.history.back();</script>";
            return;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            echo "<script>alert('Giỏ hàng trống!'); window.location.href='/banhaisan/cart/index';</script>";
            return;
        }

        $fullname       = trim($_POST['fullname'] ?? '');
        $phone          = trim($_POST['phone'] ?? '');
        $address        = trim($_POST['address'] ?? '');
        $payment_method = $_POST['payment_method'] ?? 'cod';
        $note           = trim($_POST['note'] ?? '');
        $delivery_time  = $_POST['delivery_time'] ?? 'Càng sớm càng tốt';

        if (empty($fullname) || empty($phone) || empty($address)) {
            echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); window.history.back();</script>";
            return;
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping   = $subtotal >= 500000 ? 0 : 30000;
        $totalPrice = $subtotal + $shipping;

        $userId = $_SESSION['user_id'] ?? 1;

        require_once 'app/models/OrderModel.php';
        $orderModel = new OrderModel();
        $orderId = $orderModel->createOrder(
            $userId, $totalPrice, $shipping, $cart,
            $fullname, $phone, $address, $payment_method, 0, $note, $delivery_time
        );

        if ($orderId) {
            $_SESSION['last_order'] = [
                'id'      => $orderId,
                'name'    => $fullname,
                'total'   => $totalPrice,
                'payment' => $payment_method,
                'items'   => $cart,
            ];
            unset($_SESSION['cart']);
            header('Location: /banhaisan/cart/order_success');
        } else {
            echo "<script>alert('Lỗi đặt hàng! Vui lòng thử lại.'); window.history.back();</script>";
        }
    }

    public function order_success() {
        $order = $_SESSION['last_order'] ?? null;
        include 'app/views/cart/order_success.php';
    }
}
?>