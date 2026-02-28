<?php
// admin_login.php
session_start();
require_once 'config.php';

// If already logged in, redirect to admin panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_panel.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $db_username, $hashed_password);
            $stmt->fetch();
            
            // Verify password (default admin password is 'admin')
            if (password_verify($password, $hashed_password)) {
                // Password is correct
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $db_username;
                
                // Redirect to admin panel
                header('Location: admin_panel.php');
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Kaalu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-login-container {
            max-width: 400px;
            margin: 100px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .admin-login-title {
            text-align: center;
            margin-bottom: 30px;
            color: #232f3e;
        }
        .admin-login-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .admin-login-form button {
            width: 100%;
            padding: 12px;
            background: #232f3e;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .admin-login-form button:hover {
            background: #37475a;
        }
        .error-msg {
            color: #ff3333;
            text-align: center;
            margin-bottom: 15px;
        }
        .back-home {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <h2 class="admin-login-title">Admin Login</h2>
        
        <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="admin-login-form">
            <input type="text" name="username" placeholder="Username" required value="admin">
            <input type="password" name="password" placeholder="Password" required value="admin">
            <button type="submit">Login</button>
        </form>
        
        <div class="back-home">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>