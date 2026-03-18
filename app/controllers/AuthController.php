<?php
require_once 'app/models/UserModel.php';

class AuthController {

    public function login_customer() {
        include 'app/views/auth/login_customer.php';
    }

    public function process_login_customer() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->login($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['avatar'] = $user['avatar'] ?? null;
            header('Location: /banhaisan');
        } else {
            echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.history.back();</script>";
        }
    }

    public function login_staff() {
        include 'app/views/auth/login_staff.php';
    }

    public function process_login_staff() {
        $username = $_POST['code'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->login($username, $password);

        if ($user && ($user['role'] == 'admin' || $user['role'] == 'employee')) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['avatar'] = $user['avatar'] ?? null;
            
            if ($user['role'] == 'admin') {
                header('Location: /banhaisan/admin/products');
            } else {
                header('Location: /banhaisan/employee/dashboard');
            }
        } else {
            echo "<script>alert('Thông tin đăng nhập không đúng!'); window.history.back();</script>";
        }
    }

    public function register() {
        include 'app/views/auth/register.php';
    }

    public function process_register() {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm  = $_POST['confirm_password'];

        if ($password != $confirm) {
            echo "<script>alert('Mật khẩu nhập lại không khớp!'); window.history.back();</script>";
            return;
        }

        $userModel = new UserModel();
        if ($userModel->register($fullname, $username, $password)) {
            echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location.href='/banhaisan/auth/login_customer';</script>";
        } else {
            echo "<script>alert('Đăng ký thất bại (Uername đã tồn tại)!'); window.history.back();</script>";
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /banhaisan');
    }
}
?>

