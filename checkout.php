<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_details = $select_user->fetch(PDO::FETCH_ASSOC);

// Validate user details
if (empty($user_details['number'])) {
    echo "Phone number is required. Please update your profile.";
    exit();
}

// Check if cart_id is set in the URL for the selected product
if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];

    // Fetch the selected product from the cart  
    $fetch_cart = $conn->prepare(
        "SELECT c.*, p.name AS product_name, p.price AS product_price, p.stock AS product_stock
        FROM `cart` c
        JOIN `products` p ON c.pid = p.id
        WHERE c.user_id = ? AND c.id = ?"
    );
    $fetch_cart->execute([$user_id, $cart_id]);

    if ($fetch_cart->rowCount() > 0) {
        $cart_item = $fetch_cart->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "This item is no longer in your cart.";
        exit;
    }
} else {
    echo "No product selected for checkout.";
    exit;
}

if (isset($_POST['update_details'])) {
    $new_number = $_POST['number'];
    $new_address = $_POST['address'];

    // Update phone number and address, but NOT the name
    $update_user = $conn->prepare("UPDATE `users` SET number = ?, address = ? WHERE id = ?");
    $update_user->execute([$new_number, $new_address, $user_id]);

    // Refresh user details after update
    $user_details['number'] = $new_number;
    $user_details['address'] = $new_address;
}

if (isset($_POST['order'])) {
    $total_price = $cart_item['product_price'] * $cart_item['quantity'];
    $total_products = $cart_item['quantity'];

    // Check if stock is sufficient
    if ($cart_item['quantity'] > $cart_item['product_stock']) {
        echo "Not enough stock for this product.";
        exit();
    }

    try {
        // Insert order into the orders table
        $insert_order = $conn->prepare(
            "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending')"
        );
        $insert_order->execute([
            $user_id,
            $user_details['name'], // Name remains unchanged
            $user_details['number'],
            $user_details['email'],
            $_POST['method'],
            $user_details['address'],
            $total_products,
            $total_price
        ]);

        // Fetch the order ID of the newly inserted order
        $order_id = $conn->lastInsertId();

        // Insert the product into the order_items table
        $insert_item = $conn->prepare("INSERT INTO `order_items`(order_id, product_name, quantity, price) VALUES(?, ?, ?, ?)");
        $insert_item->execute([$order_id, $cart_item['product_name'], $cart_item['quantity'], $cart_item['product_price']]);

        // Decrease the stock of the product
        $updated_stock = $cart_item['product_stock'] - $cart_item['quantity'];
        $update_stock = $conn->prepare("UPDATE `products` SET stock = ? WHERE id = ?");
        $update_stock->execute([$updated_stock, $cart_item['pid']]);

        // Remove the product from the cart
        $delete_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
        $delete_item->execute([$cart_id]);

        $message[] = 'Order placed successfully!';
        header('location:orders.php');
        exit();

    } catch (PDOException $e) {
        echo "Error placing order: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/checkout.css">
  <link rel="stylesheet" href="components/user_header.php">
   <style>
      body {
         background-image: url('images/home-bg.png'); 
         background-size: cover; 
         background-position: center;
         background-attachment: fixed;
         color: #000; /* Ensures text is readable */
      }
   </style>
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="checkout">
      <form action="" method="post">
         <h3>Checkout</h3>
         <?php if (isset($message)): ?>
            <div class="message">
               <?php foreach ($message as $msg): ?>
                  <p><?= $msg ?></p>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>

         <!-- User Information -->
         <p>FullName: <?= $user_details['name'] ?> <small>(Cannot be changed)</small></p>
         <p>Email: <?= $user_details['email'] ?></p>
         <p>Phone: <input type="text" name="number" value="<?= $user_details['number'] ?>" required></p>
         <p>Address: <input type="text" name="address" value="<?= $user_details['address'] ?>" required></p>

         <!-- Update Details Button -->
         <input type="submit" value="Update Details" class="btn" name="update_details">
         
         <h4>Your Cart:</h4>
         <p>Product: <?= $cart_item['product_name'] ?></p>
         <p>Price per item: ₱<?= number_format($cart_item['product_price'], 2) ?></p>
         <p>Quantity: <?= $cart_item['quantity'] ?></p>
         <p>Total: ₱<?= number_format($cart_item['product_price'] * $cart_item['quantity'], 2) ?></p>
         
         <!-- Payment Method -->
         <select name="method" required>
            <option value="" disabled selected>Choose payment method</option>
            <option value="credit card">Credit Card</option>
            <option value="gcash">Gcash</option>
            <option value="cash on delivery">Cash on Delivery</option>
         </select>

         <input type="submit" value="Place Order" class="btn" name="order">
      </form>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html>
