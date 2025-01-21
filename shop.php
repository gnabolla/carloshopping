<?php
    include 'components/connect.php';  // Include the database connection file
    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; 
    } else { 
        $user_id = ''; 
    }

    // Add to Cart Logic
    if (isset($_POST['add_to_cart'])) {
        $pid = $_POST['pid'];  // Product ID
        $name = $_POST['name'];  // Product Name
        $price = $_POST['price'];  // Product Price
        $image = $_POST['image'];  // Product Image
        $qty = $_POST['qty'];  // Quantity selected by the user

        // Check if product already exists in the cart (using 'pid' as product ID)
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND pid = ?");
        $check_cart->execute([$user_id, $pid]);

        if ($check_cart->rowCount() > 0) {
            // If product exists, update the quantity
            $cart_item = $check_cart->fetch(PDO::FETCH_ASSOC);
            $new_qty = $cart_item['quantity'] + $qty;
            $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
            $update_qty->execute([$new_qty, $cart_item['id']]);
        } else {
            // If product doesn't exist in the cart, add a new record
            $add_to_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $add_to_cart->execute([$user_id, $pid, $name, $price, $image, $qty]);
        }

        // After adding to the cart, stay on home.php or redirect to shop.php
        header('location:shop.php');
    }

    include 'components/wishlist_cart.php';  // Include wishlist and cart functionality
?>  
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>
   <style>
      body {
         background-image: url("images/orangeBG.avif");
         background-size: cover;
         background-repeat: no-repeat;
         background-position: center center;
      }
      .stock {
         font-size: 0.9rem;
         color: #333;
         margin-top: 5px;
      }
      .out-of-stock {
         color: red;
         font-weight: bold;
      }
   </style>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">Latest Products</h1>

   <div class="box-container">

   <?php
     // Fetch only products that are in stock
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE `stock` > 0"); 
     $select_products->execute();
     if ($select_products->rowCount() > 0) {
        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt=""/>
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="<?= $fetch_product['stock']; ?>" value="1">
      </div>
      <div class="stock">Stock: <?= $fetch_product['stock']; ?></div>
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
   </form>
   <?php
        }
     } else {
        echo '<p class="empty">No products found!</p>';
     }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>  
