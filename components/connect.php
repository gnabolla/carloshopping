<?php
$db_name     = 'mysql:host=localhost;dbname=maindb';
$user_name   = 'root';
$user_pass   = ''; // default XAMPP password is empty unless you've changed it

try {
    $conn = new PDO($db_name, $user_name, $user_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
