<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);
    $stock = $_POST['stock'];
    $stock = filter_var($stock, FILTER_SANITIZE_NUMBER_INT);

    // Check if stock is a valid positive number
    if ($stock < 0) {
        $message[] = 'Stock quantity cannot be negative!';
    } else {
        $image_01 = $_FILES['image_01']['name'];
        $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
        $image_size_01 = $_FILES['image_01']['size'];
        $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
        $image_folder_01 = '../uploaded_img/' . $image_01;

        $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
        $select_products->execute([$name]);

        if ($select_products->rowCount() > 0) {
            $message[] = 'Product name already exists!';
        } else {
            // Insert the new product with stock value
            $insert_product = $conn->prepare("INSERT INTO `products`(name, price, details, stock, image_01) VALUES(?,?,?,?,?)");
            $insert_product->execute([$name, $price, $details, $stock, $image_01]);

            if ($insert_product) {
                if ($image_size_01 > 2000000) {
                    $message[] = 'Image size is too large!';
                } else {
                    move_uploaded_file($image_tmp_name_01, $image_folder_01);
                    $message[] = 'New product added with stock!';
                }
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
    $delete_wishlist->execute([$delete_id]);
    header('location:products.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

    <h1 class="heading">Add Product</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <span>Product Name (required)</span>
                <input type="text" class="box" required maxlength="100" placeholder="Enter Product name" name="name">
            </div>
            <div class="inputBox">
                <span>Product Price (required)</span>
                <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter Product price"
                       onkeypress="if(this.value.length == 10) return false;" name="price">
            </div>
            <div class="inputBox">
                <span>Stock Quantity (required)</span>
                <input type="number" min="0" class="box" required max="999999" placeholder="Enter stock quantity"
                       name="stock">
            </div>
            <div class="inputBox">
                <span>Image (required)</span>
                <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                       required>
            </div>
            <div class="inputBox">
                <span>Product Details (required)</span>
                <textarea name="details" placeholder="Enter Product details" class="box" required maxlength="500"
                          cols="30" rows="10"></textarea>
            </div>
        </div>

        <input type="submit" value="Add Product" class="btn" name="add_product">
    </form>

</section>

<section class="show-products">

    <h1 class="heading">Products Added</h1>

    <div class="box-container">

        <?php
        $select_products = $conn->prepare("SELECT * FROM `products`");
        $select_products->execute();
        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box">
                    <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                    <div class="name"><?= $fetch_products['name']; ?></div>
                    <div class="price">₱<span><?= $fetch_products['price']; ?></span>/-</div>
                    <div class="details"><span><?= $fetch_products['details']; ?></span></div>
                    <div class="stock">Stock: <span><?= $fetch_products['stock']; ?></span></div>
                    <div class="flex-btn">
                        <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
                        <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn"
                           onclick="return confirm('Delete this product?');">Delete</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>

    </div>

</section>

<script src="../js/admin_script.js"></script>

</body>

</html>
