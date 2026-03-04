<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<div class="top-bar">
    <div class="container">
        <a href="#">Shop the Kaalu App</a>
        <a href="#">Sell on Kaalu</a>
        <a href="#">Customer Service</a>
        <a href="#">Today's Deals</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</div>
<header class="main-header">
    <div class="container header-wrapper">
        <a href="index.php" class="logo"><img src="pio.png" alt="Cart" class="cart-icon" style="height:50px;"></a>
        <div class="search-box">
            <form action="search.php" method="get" style="display:flex;width:100%;">
                <input type="text" name="q" placeholder="Search Kaalu" style="flex:1;">
                <button type="submit">SEARCH</button>
            </form>
        </div>
        <div class="header-icons">
            <?php 
            // Check if we're on a seller page
            $current_page = basename($_SERVER['PHP_SELF']);
            $seller_pages = ['seller_choice.php', 'seller_login.php', 'seller_register.php', 'seller_dashboard.php'];
            
            if(isset($_SESSION['user_id'])): 
                // Logged in users
                if(isset($_SESSION['seller_id'])): 
                    // Seller logged in
            ?>
                    <a href="index.php" class="buyer-btn" style="padding: 10px 20px; background: #ff9900; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">Go to Buyer Site</a>
                    <button class="profile-btn">Seller Dashboard</button>
                    <button class="cart-btn">Logout</button>
            <?php 
                else: 
                    // Buyer logged in
            ?>
                    <button class="cart-btn">Cart</button>
                    <button class="profile-btn">Profile</button>
                    <a href="seller_choice.php" class="seller-btn" style="padding: 10px 20px; background: #232f3e; color: white; text-decoration: none; border-radius: 4px;">Become Seller</a>
            <?php 
                endif; 
            else: 
                // Not logged in
                if(in_array($current_page, $seller_pages)): 
                    // On seller pages - show "Become Buyer" button
            ?>
                    <button class="buyer" onclick="window.location.href='index.php'">
                        <h3>Become Buyer</h3>
                    </button>
            <?php 
                else: 
                    // On buyer pages (homepage, etc.) - show both buttons
            ?>
                    <button class="buyer">
                        <h3>Become Buyer</h3>
                    </button>
                    <button class="seller" onclick="window.location.href='seller_choice.php'">Become Seller</button>
            <?php 
                endif; 
            endif; 
            <style>
                .header-icons button, .header-icons a {
                    transition: all 0.3s ease;
                }
                .cart-btn:hover {
                    transform: scale(1.05);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                }
                .profile-btn:hover {
                    background-color: #ff9900;
                    color: white;
                }
                .seller-btn:hover {
                    background-color: #ff9900;
                    transform: translateY(-2px);
                }
                .buyer:hover, .seller:hover {
                    opacity: 0.9;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
                }
                .buyer-btn:hover {
                    background-color: #ff7700;
                    transform: scale(1.02);
                }
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                .header-icons { animation: fadeIn 0.5s ease; }
            </style>
            ?>
        </div>
    </div>
</header>