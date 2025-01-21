<?php
$db_name = 'mysql:host=localhost;dbname=if0_37931780_maindb';
$user_name = 'root'; // Default username for XAMPP
$user_password = ''; // Default password for XAMPP

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>