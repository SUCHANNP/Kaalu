<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller: Choose Action</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .seller-choice-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
        }
        .seller-choice-title {
            font-size: 2rem;
            margin-bottom: 32px;
            font-weight: bold;
        }
        .seller-choice-circles {
            display: flex;
            gap: 60px;
        }
        .seller-choice-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 12px #0002;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: box-shadow 0.2s, background 0.2s;
            font-size: 1.1rem;
        }
        .seller-choice-circle:hover {
            background: #ff9900;
            color: #fff;
            box-shadow: 0 4px 20px #ff990055;
        }
        .seller-choice-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="seller-choice-container">
    <div class="seller-choice-title">Become a Seller</div>
    <div class="seller-choice-circles">
        <div class="seller-choice-circle" onclick="window.location.href='seller_login.php'">
            <div class="seller-choice-icon">🔑</div>
            Login
        </div>
        <div class="seller-choice-circle" onclick="window.location.href='seller_register.php'">
            <div class="seller-choice-icon">📝</div>
            Register
        </div>
    </div>
</div>
</body>
</html>
