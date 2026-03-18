<?php
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';

class AdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: /banhaisan/auth/login_staff');
            exit();
        }
    }

    public function index() {
        header('Location: /banhaisan/admin/dashboard');
        exit;
    }

    // 1. Dashboard
    public function dashboard() {
        require_once 'app/models/OrderModel.php';
        $db   = new Database();
        $conn = $db->getConnection();
        $orderCounts = OrderModel::countByStatus();
        $stats = [
            'revenue'        => $conn->query("SELECT COALESCE(SUM(total_price),0) FROM orders WHERE MONTH(created_at)=MONTH(NOW())")->fetchColumn(),
            'total_orders'   => $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'total_products' => $conn->query("SELECT COUNT(*) FROM products WHERE is_active=1")->fetchColumn(),
            'new_customers'  => $conn->query("SELECT COUNT(*) FROM users WHERE role='customer' AND MONTH(created_at)=MONTH(NOW())")->fetchColumn(),
            'order_counts'   => $orderCounts,
        ];
        include 'app/views/admin/dashboard.php';
    }

    // 2. Danh sach san pham
    public function products() {
        $products   = ProductModel::getAll();
        $categories = CategoryModel::getAll();
        include 'app/views/admin/products.php';
    }

    // 3. Them san pham moi
    public function store_product() {
        $name        = trim($_POST['name']);
        $price       = floatval($_POST['price']);
        $category_id = intval($_POST['category_id']);
        $description = trim($_POST['description'] ?? '');
        $stock       = intval($_POST['stock'] ?? 0);
        $unit        = trim($_POST['unit'] ?? 'kg');

        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = 'public/images/';
            $image = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
        }

        $model = new ProductModel();
        $model->create($name, $description, $price, $category_id, $image, $stock, $unit);
        header('Location: /banhaisan/admin/products');
    }

    // 4. Form chinh sua
    public function edit_product() {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) { header('Location: /banhaisan/admin/products'); exit; }
        $product    = ProductModel::getById($id);
        $categories = CategoryModel::getAll();
        if (!$product) { header('Location: /banhaisan/admin/products'); exit; }
        include 'app/views/admin/edit_product.php';
    }

    // 5. Luu chinh sua san pham
    public function update_product() {
        $id          = intval($_POST['id']);
        $name        = trim($_POST['name']);
        $price       = floatval($_POST['price']);
        $category_id = intval($_POST['category_id']);
        $description = trim($_POST['description'] ?? '');
        $stock       = intval($_POST['stock'] ?? 0);

        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = 'public/images/';
            $image = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
        }

        $model = new ProductModel();
        $model->update($id, $name, $description, $price, $category_id, $image, $stock);
        header('Location: /banhaisan/admin/products');
    }

    // 6. Xoa san pham
    public function delete_product() {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            $model = new ProductModel();
            $model->delete($id);
        }
        header('Location: /banhaisan/admin/products');
    }

    // 7. Danh sach don hang
    public function orders() {
        require_once 'app/models/OrderModel.php';
        $orders = OrderModel::getAll();
        include 'app/views/admin/orders.php';
    }

    // 7b. Chi tiet don hang
    public function order_detail() {
        require_once 'app/models/OrderModel.php';
        $id    = intval($_GET['id'] ?? 0);
        $order = $id ? OrderModel::getById($id) : null;
        if (!$order) { header('Location: /banhaisan/admin/orders'); exit; }
        include 'app/views/admin/order_detail.php';
    }

    // 7c. Cap nhat trang thai don hang
    public function update_order_status() {
        require_once 'app/models/OrderModel.php';
        $id     = intval($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? '';
        if ($id && $status) OrderModel::updateStatus($id, $status);
        $redirect = $_GET['redirect'] ?? '';
        if ($redirect === 'detail') {
            header('Location: /banhaisan/admin/order_detail?id=' . $id);
        } else {
            header('Location: /banhaisan/admin/orders');
        }
    }

    // 7d. In hóa đơn
    public function print_invoice() {
        require_once 'app/models/OrderModel.php';
        $id    = intval($_GET['id'] ?? 0);
        $order = $id ? OrderModel::getById($id) : null;
        if (!$order) { header('Location: /banhaisan/admin/orders'); exit; }
        include 'app/views/admin/invoice.php';
    }

    // 8. Danh sach voucher
    public function vouchers() {
        $db       = new Database();
        $conn     = $db->getConnection();
        $vouchers = $conn->query("SELECT * FROM vouchers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/admin/vouchers.php';
    }

    // 8b. Tao voucher moi
    public function store_voucher() {
        $code           = strtoupper(trim($_POST['code'] ?? ''));
        $discount_type  = $_POST['discount_type'] ?? 'percent';
        $discount_value = floatval($_POST['discount_value'] ?? 0);
        $min_order      = floatval($_POST['min_order'] ?? 0);
        $expires_at     = $_POST['expires_at'] ?? null;

        if (empty($code) || $discount_value <= 0) {
            header('Location: /banhaisan/admin/vouchers');
            return;
        }

        $db   = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("INSERT INTO vouchers (code, discount_type, discount_value, min_order_value, expires_at) VALUES (?,?,?,?,?)");
        $stmt->execute([$code, $discount_type, $discount_value, $min_order, $expires_at ?: null]);
        header('Location: /banhaisan/admin/vouchers');
    }

    // 8c. Xoa voucher
    public function delete_voucher() {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            $db   = new Database();
            $conn = $db->getConnection();
            $conn->prepare("DELETE FROM vouchers WHERE id=?")->execute([$id]);
        }
        header('Location: /banhaisan/admin/vouchers');
    }

    // 9. Thong ke doanh thu
    public function statistics() {
        require_once 'app/models/OrderModel.php';
        $month = intval($_GET['month'] ?? date('n'));
        $year  = intval($_GET['year']  ?? date('Y'));
        $db    = new Database();
        $conn  = $db->getConnection();

        $stmt = $conn->prepare("SELECT COALESCE(SUM(CASE WHEN status != 'cancelled' THEN total_price END),0) AS revenue, COUNT(*) AS orders, SUM(status='delivered') AS delivered, SUM(status='cancelled') AS cancelled FROM orders WHERE MONTH(created_at)=? AND YEAR(created_at)=?");
        $stmt->execute([$month, $year]);
        $monthStats = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $conn->prepare("SELECT DAY(created_at) AS day, COALESCE(SUM(total_price),0) AS revenue FROM orders WHERE MONTH(created_at)=? AND YEAR(created_at)=? AND status != 'cancelled' GROUP BY DAY(created_at) ORDER BY day");
        $stmt2->execute([$month, $year]);
        $dailyRevenue = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt3 = $conn->prepare("SELECT o.*, COUNT(oi.id) AS item_count FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id WHERE MONTH(o.created_at)=? AND YEAR(o.created_at)=? GROUP BY o.id ORDER BY o.created_at DESC");
        $stmt3->execute([$month, $year]);
        $monthOrders = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/admin/statistics.php';
    }

    // 10. Xuat CSV bao cao
    public function export_revenue() {
        $month = intval($_GET['month'] ?? date('n'));
        $year  = intval($_GET['year']  ?? date('Y'));
        $db    = new Database();
        $conn  = $db->getConnection();
        $stmt  = $conn->prepare("SELECT id, customer_name, customer_phone, payment_method, total_price, shipping_fee, discount_amount, status, created_at FROM orders WHERE MONTH(created_at)=? AND YEAR(created_at)=? ORDER BY created_at");
        $stmt->execute([$month, $year]);
        $rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="doanh_thu_' . $month . '_' . $year . '.csv"');
        header('Pragma: no-cache');
        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF");
        fputcsv($out, ['Ma DH', 'Khach hang', 'SDT', 'TT', 'Tong tien', 'Phi ship', 'Giam gia', 'Trang thai', 'Ngay dat']);
        foreach ($rows as $r) {
            fputcsv($out, [
                '#' . str_pad($r['id'], 5, '0', STR_PAD_LEFT),
                $r['customer_name'], $r['customer_phone'], $r['payment_method'],
                $r['total_price'],   $r['shipping_fee'],  $r['discount_amount'],
                $r['status'],        $r['created_at'],
            ]);
        }
        fclose($out);
        exit;
    }

    // 11. Quan ly khach hang
    public function customers() {
        $db   = new Database();
        $conn = $db->getConnection();
        $q    = trim($_GET['q'] ?? '');
        if ($q) {
            $stmt = $conn->prepare(
                "SELECT u.*, COUNT(o.id) AS order_count, COALESCE(SUM(o.total_price),0) AS total_spent
                 FROM users u LEFT JOIN orders o ON u.id=o.user_id
                 WHERE u.role='customer' AND (u.username LIKE ? OR u.email LIKE ? OR u.phone LIKE ? OR u.fullname LIKE ?)
                 GROUP BY u.id ORDER BY u.created_at DESC"
            );
            $like = "%$q%";
            $stmt->execute([$like,$like,$like,$like]);
        } else {
            $stmt = $conn->query(
                "SELECT u.*, COUNT(o.id) AS order_count, COALESCE(SUM(o.total_price),0) AS total_spent
                 FROM users u LEFT JOIN orders o ON u.id=o.user_id
                 WHERE u.role='customer' GROUP BY u.id ORDER BY u.created_at DESC"
            );
        }
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/admin/customers.php';
    }

    // 11b. Don hang cua 1 khach hang
    public function customer_orders() {
        require_once 'app/models/OrderModel.php';
        $id       = intval($_GET['id'] ?? 0);
        if (!$id) { header('Location: /banhaisan/admin/customers'); exit; }
        $db       = new Database();
        $conn     = $db->getConnection();
        $stmt     = $conn->prepare("SELECT * FROM users WHERE id=? AND role='customer' LIMIT 1");
        $stmt->execute([$id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$customer) { header('Location: /banhaisan/admin/customers'); exit; }
        $stmt2 = $conn->prepare(
            "SELECT o.*, COUNT(oi.id) AS item_count FROM orders o
             LEFT JOIN order_items oi ON o.id=oi.order_id
             WHERE o.user_id=? GROUP BY o.id ORDER BY o.created_at DESC"
        );
        $stmt2->execute([$id]);
        $orders = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/admin/customer_orders.php';
    }
}
?>
