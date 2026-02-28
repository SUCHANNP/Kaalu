<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaalu - Online Shopping Nepal (Amazon Theme)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
session_start(); 
?>
<div class="top-bar">
    <div class="container">
        <a href="#">Shop the Kaalu App</a>
        <a href="#">Sell on Kaalu</a>
        <a href="#">Customer Service</a>
        <a href="#">Today's Deals</a>
        
        <a href="admin_login.php">Admin Login</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</div>

    <header class="main-header">
        <div class="container header-wrapper">
            <a href="#" class="logo"><img src="pio.png" alt="Cart" class="cart-icon" style="height:50px;"></a>

            <div class="search-box">
                <form action="search.php" method="get" style="display:flex;width:100%;">
                    <input type="text" name="q" placeholder="Search Kaalu" style="flex:1;">
                    <button type="submit">SEARCH</button>
                </form>
            </div>

            <div class="header-icons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button class="cart-btn">Cart</button>
                    <button class="profile-btn">Profile</button>
                <?php else: ?>
                    <button class="buyer">
                        <h3>Become Buyer</h3>
                    </button>
                    <<button class="seller" onclick="window.location.href='seller_choice.php'">Become Seller</button>
                <?php endif; ?>
            </div>
        </div>
    </header>
<div class="overlay" id="loginModal"> 
        <div class="login-box">
            <h2>Login</h2>
            <form id="loginForm" method="POST">
                <label>Email:</label>
                <input type="email" name="email" placeholder="Email" required>
                <label>Password:</label><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit" class="submit-btn">Login</button>
            </form>
            <div class="register-row">
                <p style="color: white;">Don't have account</p>
                <p class="not" id="switchToRegister">Register Here</p>
            </div>
            <div id="loginMessage"></div>
        </div>
    </div>

    <div class="over" id="registerModal"> 
        <div class="register">
            <h2>Registration Form</h2>
            <form id="registerForm" method="POST">
                <label>Email:</label>
                <input type="email" name="email" placeholder="Email" required>
                <label>Username:</label>
                <input type="text" name="username" placeholder="Username" required>
                <label>Password:</label><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit" class="submit-btn">Register</button>
            </form>
            <div class="login-row">
                <p style="color: white;">Already have an account</p>
                <p class="log" id="switchToLogin">Login</p>
            </div>
            <div id="registerMessage"></div>
        </div>
    </div>

    <main>
        <section class="container hero-section">
            <div class="hero-grid">
                <ul class="categories-sidebar">
                    <li>Shop By Category</li>
                    <li>Electronics</li>
                    <li>Books & Media</li>
                    <li>Home & Kitchen</li>
                    <li>Clothing & Shoes</li>
                    <li>Health & Beauty</li>
                    <li>Toys & Games</li>
                    <li>Automotive</li>
                    <li>Outdoors</li>
                    <li>Software</li>
                </ul>
                
                <div class="hero-banner">
                    <div class="banner-slider">
                        <img src="MERO.PNG" class="banner-img" alt="Banner 1">
                        <img src="ads-2.png" class="banner-img" alt="Banner 2">
                        <img src="wow.png" class="banner-img" alt="Banner 3">
                    </div>
                </div>
            </div>
        </section>


        <section class="container recently-added">
            <h2 class="section-title">Recently Added Items</h2>
            <div class="products-grid">
            <?php
            require_once 'config.php';
            $result = $conn->query("SELECT id, name, price, photo FROM items ORDER BY created_at DESC LIMIT 6");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="product.php?id=' . $row['id'] . '" class="product-card">';
                    echo '<img src="' . htmlspecialchars($row['photo']) . '" class="product-img">';
                    echo '<div class="product-title">' . htmlspecialchars($row['name']) . '</div>';
                    echo '<div class="product-price">Rs. ' . number_format($row['price']) . '</div>';
                    echo '</a>';
                }
            } else {
                echo '<div>No items found.</div>';
            }
            ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-cols">
            <div>
                <h3>Get to Know Us</h3>
                <ul>
                    <li>Careers</li>
                    <li>Kaalu Blog</li>
                    <li>About Kaalu</li>
                    <li>Investor Relations</li>
                </ul>
            </div>
            <div>
                <h3>Make Money with Us</h3>
                <ul>
                    <li>Sell products on Kaalu</li>
                    <li>Sell on Kaalu Business</li>
                    <li>Become an Affiliate</li>
                    <li>Advertise Your Products</li>
                </ul>
            </div>
            <div>
                <h3>Kaalu Payment Products</h3>
                <ul>
                    <li>Kaalu Business Card</li>
                    <li>Shop with Points</li>
                    <li>Reload Your Balance</li>
                    
                </ul>
            </div>
            <div>
                <h3>Let Us Help You</h3>
                <ul>
                    <li>Your Account</li>
                    <li>Your Orders</li>
                    <li>Shipping Rates</li>
                    <li>Help Center</li>
                </ul>
            </div>
        </div>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>