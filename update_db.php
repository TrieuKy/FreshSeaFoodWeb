<?php
require_once __DIR__ . '/app/config/database.php';
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Add columns if they don't exist
    $conn->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL");
    $conn->exec("ALTER TABLE users ADD COLUMN gender ENUM('Nam','Nữ','Khác') DEFAULT NULL");
    
    echo "Successfully added avatar and gender columns to users table.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>
