<?php
include 'components/connect.php';

session_start();

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('location:home.php');
    exit();
}

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = sha1(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));
    $cpass = sha1(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $address = 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);

    // Check if email already exists
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);

    if ($select_user->rowCount() > 0) {
        $message[] = 'Email already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Passwords do not match!';
        } else {
            // Insert new user into `users` table
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password, number, address) VALUES(?, ?, ?, ?, ?)");
            $insert_user->execute([$name, $email, $pass, $number, $address]);

            // Redirect after successful registration
            $message[] = 'Registered successfully, please login now!';
            header('location:user_login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Register now</h3>
      <?php if (isset($message)): ?>
         <div class="message">
            <?php foreach ($message as $msg): ?>
               <p><?= $msg ?></p>
            <?php endforeach; ?>
         </div>
      <?php endif; ?>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box">
      <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="box">
      <input type="text" name="number" required placeholder="Enter your phone number" maxlength="15" class="box">
      <input type="text" name="flat" required placeholder="Flat no." maxlength="50" class="box">
      <input type="text" name="street" required placeholder="Street name" maxlength="50" class="box">
      <input type="text" name="city" required placeholder="City" maxlength="50" class="box">
      <input type="text" name="state" required placeholder="State" maxlength="50" class="box">
      <input type="text" name="country" required placeholder="Country" maxlength="50" class="box">
      <input type="text" name="pin_code" required placeholder="Pin code" maxlength="10" class="box">
      <input type="submit" value="Register Now" class="btn" name="submit">
      <p>Already have an account?</p>
      <a href="user_login.php" class="option-btn">Login now</a>
   </form>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>

</body>
</html>
