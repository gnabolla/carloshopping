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
    <title>Clicknbuy</title>
    <style>
        body {
            background-image: url("images/orangeBG.avif");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .home {
            margin-top: 20px;
        }
        .swiper-wrapper {
            display: flex;
            justify-content: space-between;
        }
        .home-slider, .category-slider, .products-slider {
            padding: 20px;
        }
        .swiper-slide {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
        }
        .home-slider .swiper-slide, .category-slider .swiper-slide {
            width: 30%;
        }
        .home-slider .image img, .category-slider .image img {
            width: 100%;
            border-radius: 10px;
        }
        .home .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        .home .content h3 {
            font-size: 24px;
            color: #fff;
        }
        .category h3, .products h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .category .swiper-slide {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* New style for products */
        .products-slider .swiper-slide {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            max-width: 100%;
            box-sizing: border-box;
            margin: 10px;
            transition: transform 0.3s ease-in-out;
        }
        .products-slider .swiper-slide:hover {
            transform: translateY(-10px);
        }
        .products-slider .swiper-slide img {
            width: 100%;
            border-radius: 10px;
            height: 250px;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .products-slider .name, .products-slider .price {
            color: #fff;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn {
            background-color: #ff6f61;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #ff4d43;
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
        .disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        /* Ensure responsive behavior for the products section */
        .products-slider {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>  

    <div class="home-bg">  
        <section class="home">
            <div class="swiper home-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide slide">
                        <div class="image">
                            <img src="images/Le3no.png" alt="">
                        </div>
                        <div class="content">
                            <span>upto 50% off</span>
                            <h3>Latest Fashion</h3>
                            <a href="shop.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="swiper-slide slide">
                        <div class="image">
                            <img src="images/latest.png" alt="">
                        </div>
                        <div class="content">
                            <span>upto 50% off</span>
                            <h3>Latest Styles</h3>
                            <a href="shop.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="swiper-slide slide">
                        <div class="image">
                            <img src="images/zengjo.png" alt="">
                        </div>
                        <div class="content">
                            <span>upto 50% off</span>
                            <h3>Sale Ends Today!!</h3>
                            <a href="shop.php" class="btn">shop now</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    </div>

   <!--  <section class="category">
        <h1 class="heading">shop by category</h1>
        <div class="swiper category-slider">
            <div class="swiper-wrapper">
                <a href="category.php?category=men" class="swiper-slide slide">
                    <img src="images/man.jpg" alt="">
                    <h3>Men</h3>
                </a>
                <a href="category.php?category=women" class="swiper-slide slide">
                    <img src="images/woman.png" alt="">
                    <h3>Women</h3>
                </a>
                <a href="category.php?category=kids" class="swiper-slide slide">
                    <img src="images/kids.jpg" alt="">
                    <h3>Kidswear</h3>
                </a>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section> -->

   <section class="home-products">
    <h1 class="heading">Latest Products</h1>
    <div class="swiper products-slider">
        <div class="swiper-wrapper">
            <?php  
                // Fetch products including stock column
                $select_products = $conn->prepare("SELECT id, name, price, image_01, stock FROM `products` LIMIT 6");       
                $select_products->execute();      
                if ($select_products->rowCount() > 0) {       
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {    
            ?>    
            <form action="" method="post" class="swiper-slide slide product-card">       
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">       
                <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">       
                <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">       
                <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">       
                <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>       
                <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>       
                <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="<?= $fetch_product['name']; ?>">       
                <div class="name"><?= $fetch_product['name']; ?></div>       
                <div class="flex">          
                    <div class="price"><span>₱</span><?= $fetch_product['price']; ?><span>/-</span></div>          
                    <!-- Stock Display -->
                    <div class="stock">
                        <?php 
                            $stock = $fetch_product['stock']; 
                            if ($stock > 0) {
                                echo "<span>In stock: " . $stock . "</span>";
                            } else {
                                echo "<span class='out-of-stock'>Out of stock</span>";
                            }
                        ?>
                    </div>
                    <!-- Quantity Input -->
                    <input type="number" 
                           name="qty" 
                           class="qty" 
                           min="1" 
                           max="<?= min($fetch_product['stock'], 99); ?>" 
                           value="1" 
                           <?= ($fetch_product['stock'] > 0 ? '' : 'disabled'); ?>>       
                </div>       
                <!-- Add to Cart Button -->
                <input type="submit" 
                       value="Add to Cart" 
                       class="btn <?= ($stock > 0 ? '' : 'disabled'); ?>" 
                       name="add_to_cart" 
                       <?= ($stock > 0 ? '' : 'disabled'); ?>>    
            </form>    
            <?php       
                    }    
                } else {       
                    echo '<p class="empty">No products added yet!</p>';    
                }    
            ?>     
        </div>     
        <div class="swiper-pagination"></div>     
    </div>  
</section>  

    <?php include 'components/footer.php'; ?>  

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>  
    <script src="js/script.js"></script>  
    <script>  
    // Initialize the home slider
    var homeSwiper = new Swiper(".home-slider", {    
        loop: true,    
        spaceBetween: 20,    
        pagination: {       
            el: ".swiper-pagination",       
            clickable: true,     
        },
    });

    // Initialize the products slider
    var productsSwiper = new Swiper(".products-slider", {    
        loop: false, // Disable looping to avoid duplicate slides
        spaceBetween: 20, // Space between slides
        slidesPerView: 1, // Adjust this if you want more slides visible at once
        slidesPerGroup: 1, // Number of slides to move per swipe
        pagination: {       
            el: ".swiper-pagination",       
            clickable: true,     
        },
        on: {
            init: function () {
                // Get all pagination bullets
                const paginationBullets = document.querySelectorAll(".swiper-pagination-bullet");

                // Hide any extra bullets beyond the slide count
                const totalSlides = this.slides.length;
                paginationBullets.forEach((bullet, index) => {
                    if (index >= totalSlides) {
                        bullet.style.display = "none";
                    }
                });
            },
        },
    });
</script>




</body>
</html>
