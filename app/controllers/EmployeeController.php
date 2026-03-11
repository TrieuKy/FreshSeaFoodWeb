<?php
class EmployeeController {

    public function __construct() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
            header('Location: /banhaisan/auth/login_staff');
            exit();
        }
    }

    public function dashboard() {
        // Fetch all orders (Mock or need OrderModel method)
        // Let's add getAll method to OrderModel or just query directly if model update isn't requested yet.
        // It's cleaner to add to Model.
        $orderModel = new OrderModel();
        // Assuming we will add getAll()
        $orders = $orderModel->getAll(); 
        
        include 'app/views/employee/dashboard.php';
    }

    public function update_status() {
        $id = $_POST['order_id'];
        $status = $_POST['status'];
        
        $orderModel = new OrderModel();
        $orderModel->updateStatus($id, $status);
        
        header('Location: /banhaisan/employee/dashboard');
    }
}
?>