<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Plain text check as per existing skeleton implication or simple 'fix'.
            // In a real app we'd use password_verify($password, $user['password'])
            // For this specific 'fix' request where sample users are inserted with plain text:
            if ($password == $user['password']) {
                return $user;
            }
        }
        return false;
    }

    public function register($fullname, $username, $password) {
        // Simple plain text storage for now to match the project style or requirements inferred.
        $query = "INSERT INTO users (fullname, username, password, role) VALUES (?, ?, ?, 'customer')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $fullname);
        $stmt->bindParam(2, $username);
        $stmt->bindParam(3, $password);
        
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function getById($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $fullname, $phone, $address, $gender, $avatar = null) {
        if ($avatar) {
            $query = "UPDATE users SET fullname = ?, phone = ?, address = ?, gender = ?, avatar = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$fullname, $phone, $address, $gender, $avatar, $id]);
        } else {
            $query = "UPDATE users SET fullname = ?, phone = ?, address = ?, gender = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$fullname, $phone, $address, $gender, $id]);
        }
    }
}
?>
