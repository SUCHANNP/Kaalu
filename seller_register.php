<?php
// seller_register.php: Seller registration form and logic
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $shop_name = trim($_POST['shop_name'] ?? '');

    if ($name && $email && $phone && $address && $password && $shop_name) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO sellers (name, email, phone, address, password_hash, shop_name) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $name, $email, $phone, $address, $hashed_password, $shop_name);
        if ($stmt->execute()) {
            $_SESSION['seller_id'] = $conn->insert_id;
            $_SESSION['seller_name'] = $name;
            header('Location: seller_dashboard.php');
            exit();
        } else {
            $error = 'Registration failed. Try again.';
        }
        $stmt->close();
    } else {
        $error = 'Please fill all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Registration - Kaalu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .seller-register-form { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 32px; }
        .seller-register-form input { width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 6px; border: 1px solid #ddd; }
        .seller-register-form button { width: 100%; padding: 12px; background: #ff9900; color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 1.1rem; }
        .seller-register-form button:hover { background: #ffae42; }
        .seller-register-form h2 { text-align: center; margin-bottom: 24px; }
        .error-msg { color: red; text-align: center; margin-bottom: 12px; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="seller-register-form">
        <h2>Become a Seller</h2>
        <?php if (!empty($error)) echo '<div class="error-msg">' . htmlspecialchars($error) . '</div>'; ?>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="shop_name" placeholder="Shop Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register as Seller</button>
        </form>
    </div>
</body>
</html>
