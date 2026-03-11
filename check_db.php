<?php
require_once 'app/config/database.php';
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("DESCRIBE products");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
?>
