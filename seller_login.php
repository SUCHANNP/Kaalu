<?php
// seller_login.php: Seller login page and logic
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $error = '';
    if ($email && $password) {
        $stmt = $conn->prepare("SELECT id, name, password_hash FROM sellers WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hash);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['seller_id'] = $id;
                $_SESSION['seller_name'] = $name;
                header('Location: seller_dashboard.php');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
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
    <title>Seller Login - Kaalu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .seller-login-form { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 32px; }
        .seller-login-form input { width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 6px; border: 1px solid #ddd; }
        .seller-login-form button { width: 100%; padding: 12px; background: #ff9900; color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 1.1rem; }
        .seller-login-form button:hover { background: #ffae42; }
        .seller-login-form h2 { text-align: center; margin-bottom: 24px; }
        .error-msg { color: red; text-align: center; margin-bottom: 12px; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="seller-login-form">
        <h2>Seller Login</h2>
        <?php if (!empty($error)) echo '<div class="error-msg">' . htmlspecialchars($error) . '</div>'; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div style="text-align:center;margin-top:10px;">
            <a href="seller_register.php">Don't have an account? Register</a>
        </div>
    </div>
</body>
</html>
