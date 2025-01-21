<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $image = $_POST['image'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

    // Check if the product is already in the cart for this user
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND pid = ?");
    $check_cart->execute([$user_id, $pid]);

    if ($check_cart->rowCount() > 0) {
        // Update quantity if the product already exists in the cart
        $fetch_cart = $check_cart->fetch(PDO::FETCH_ASSOC);
        $new_quantity = $fetch_cart['quantity'] + $quantity;

        // Optional: Check stock limit here if needed
        $get_stock = $conn->prepare("SELECT stock FROM `products` WHERE id = ?");
        $get_stock->execute([$pid]);
        $fetch_stock = $get_stock->fetch(PDO::FETCH_ASSOC);
        $available_stock = (int)$fetch_stock['stock'];

        if ($new_quantity <= $available_stock) {
            $update_cart = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE user_id = ? AND pid = ?");
            $update_cart->execute([$new_quantity, $user_id, $pid]);
            $message[] = 'Quantity updated in cart.';
        } else {
            $message[] = 'Requested quantity exceeds available stock.';
        }
    } else {
        // Insert new product into the cart
        $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_cart->execute([$user_id, $pid, $name, $price, $image, $quantity]);
        $message[] = 'Product added to cart.';
    }
}

// Delete item from cart
if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
}

// Delete all items from cart
if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}

// Update quantity in cart
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    // Get the product ID from the cart
    $get_product = $conn->prepare("SELECT pid FROM `cart` WHERE id = ?");
    $get_product->execute([$cart_id]);
    $fetch_product = $get_product->fetch(PDO::FETCH_ASSOC);

    // Fetch the available stock for the product
    $product_id = $fetch_product['pid'];
    $get_stock = $conn->prepare("SELECT stock FROM `products` WHERE id = ?");
    $get_stock->execute([$product_id]);
    $fetch_stock = $get_stock->fetch(PDO::FETCH_ASSOC);

    $available_stock = (int)$fetch_stock['stock']; // Ensure it's treated as an integer
    $requested_qty = (int)$qty; // Ensure requested quantity is an integer

    // Check if the requested quantity is available in stock
    if ($requested_qty <= $available_stock) {
        // Update the quantity in the cart
        $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        $update_qty->execute([$qty, $cart_id]);
        $message[] = 'Cart quantity updated';
    } else {
        // Show an error message if the quantity exceeds the available stock
        $message[] = 'Not enough stock available!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            background-image: url("images/orangeBG.avif");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">
    <h3 class="heading">Shopping Cart</h3>
    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" method="post" class="box">
            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
            <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
            <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
            <div class="name"><?= $fetch_cart['name']; ?></div>
            <div class="flex">
                <div class="price">₱<?= $fetch_cart['price']; ?>/-</div>
                <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>">
                <button type="submit" class="fas fa-edit" name="update_qty"></button>
            </div>
            <div class="sub-total"> Subtotal: <span>₱<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
            <input type="submit" value="Delete Item" onclick="return confirm('Delete this from cart?');" class="delete-btn" name="delete">
            <a href="checkout.php?cart_id=<?= $fetch_cart['id']; ?>" class="btn <?= ($sub_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
        </form>
        <?php
                $grand_total += $sub_total;
            }
        } else {
            echo '<p class="empty">Your cart is empty</p>';
        }
        ?>
    </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
