<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
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

   <section class="orders">

      <h1 class="heading">Placed Orders</h1>

      <div class="box-container">

         <?php
         if ($user_id == '') {
            echo '<p class="empty">Please login to see your orders.</p>';
         } else {
            // Fetch orders
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            $select_orders->execute([$user_id]);

            if ($select_orders->rowCount() > 0) {
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <div class="box">
                     <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
                     <p>FullName : <span><?= $fetch_orders['name']; ?></span></p>
                     <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
                     <p>Number : <span><?= $fetch_orders['number']; ?></span></p>
                     <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
                     <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>


                     <?php
                     // Fetch order items for this order
                     $order_id = $fetch_orders['id']; // The order ID
                     $select_items = $conn->prepare("SELECT * FROM `order_items` WHERE order_id = ?");
                     $select_items->execute([$order_id]);

                     if ($select_items->rowCount() > 0) {
                        while ($fetch_item = $select_items->fetch(PDO::FETCH_ASSOC)) {
                           $item_name = $fetch_item['product_name'];
                           $item_qty = $fetch_item['quantity'];
                           $item_price = $fetch_item['price'];
                           $item_total = $item_qty * $item_price; // Calculate total price for each item
                     ?>
                           <div class="order-item">
                              <p>Product: <span><?= $item_name; ?></span></p>
                              <p>Quantity: <span><?= $item_qty; ?></span></p>
                              <p>Total Price: <span>$<?= $item_total; ?>/-</span></p>
                           </div>
                     <?php
                        }
                     } else {
                        echo '<p>No items found for this order.</p>';
                     }
                     ?>

                     <p>Payment Status : <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
                                                               echo 'red';
                                                            } else {
                                                               echo 'green';
                                                            }; ?>"><?= $fetch_orders['payment_status']; ?></span></p>

                  </div>
         <?php
               }
            } else {
               echo '<p class="empty">No orders placed yet!</p>';
            }
         }
         ?>

      </div>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>