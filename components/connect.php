<?php
$db_name   = 'mysql:host=127.0.0.1;dbname=maindb'; 
$user_name = 'myuser';
$user_pass = 'MyP@ssw0rd!';

try {
    $conn = new PDO($db_name, $user_name, $user_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
