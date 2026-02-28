<?php
session_start();
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$products = [
    ['id'=>1,'title'=>"Premium Noise Cancelling Headphones", 'price'=>1200, 'img'=>"https://placehold.co/300x300/png?text=Headphones"],
    ['id'=>2,'title'=>"Fitness Tracker Smart Watch", 'price'=>450, 'img'=>"https://placehold.co/300x300/png?text=Smart+Watch"],
    ['id'=>3,'title'=>"Men's Comfort Running Shoes", 'price'=>1850, 'img'=>"https://placehold.co/300x300/png?text=Shoes"],
    ['id'=>4,'title'=>"High-Capacity Travel Backpack", 'price'=>999, 'img'=>"https://placehold.co/300x300/png?text=Bag"],
    ['id'=>5,'title'=>"Beginner DSLR Camera Kit", 'price'=>55999, 'img'=>"https://placehold.co/300x300/png?text=Camera"],
    ['id'=>6,'title'=>"Sample Product Item For Sale", 'price'=>500, 'img'=>"https://placehold.co/300x300/png?text=Item+1"],
    ['id'=>7,'title'=>"Another Cool Product Item", 'price'=>1500, 'img'=>"https://placehold.co/300x300/png?text=Item+2"],
    ['id'=>8,'title'=>"Fashionable Item for Summer", 'price'=>890, 'img'=>"https://placehold.co/300x300/png?text=Item+3"],
    ['id'=>9,'title'=>"Kitchen Appliance Blender", 'price'=>3200, 'img'=>"https://placehold.co/300x300/png?text=Item+4"],
    ['id'=>10,'title'=>"Gaming Mouse RGB Light", 'price'=>1100, 'img'=>"https://placehold.co/300x300/png?text=Item+5"],
    ['id'=>11,'title'=>"Cotton T-Shirt Black", 'price'=>450, 'img'=>"https://placehold.co/300x300/png?text=Item+6"],
];
$results = [];
if ($search_query !== '') {
    // Search static products
    foreach ($products as $product) {
        if (stripos($product['title'], $search_query) !== false) {
            $results[] = $product;
        }
    }
    // Search items from database
    require_once 'config.php';
    $stmt = $conn->prepare("SELECT id, name, price, photo FROM items WHERE name LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param('s', $search_query);
    $stmt->execute();
    $db_result = $stmt->get_result();
    while ($row = $db_result->fetch_assoc()) {
        $results[] = [
            'id' => $row['id'],
            'title' => $row['name'],
            'price' => $row['price'],
            'img' => $row['photo']
        ];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results for '<?php echo htmlspecialchars($search_query); ?>' - Kaalu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-hero {
            background: linear-gradient(90deg, #ff9900 0%, #fff7e6 100%);
            padding: 32px 0 16px 0;
            margin-bottom: 24px;
            text-align: center;
        }
        .search-hero-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #131921;
            margin-bottom: 8px;
        }
        .search-hero-query {
            font-size: 1.1rem;
            color: #e47911;
        }
        .search-results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }
        .search-product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
            padding: 18px 14px 18px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow 0.2s;
            position: relative;
        }
        .search-product-card:hover {
            box-shadow: 0 4px 16px #0002;
        }
        .search-product-img {
            width: 160px;
            height: 160px;
            object-fit: contain;
            margin-bottom: 12px;
            border-radius: 6px;
            background: #f5f5f5;
        }
        .search-product-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #232f3e;
            margin-bottom: 8px;
            text-align: center;
            min-height: 44px;
        }
        .search-product-price {
            color: #e47911;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 12px;
        }
        .search-product-actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }
        .search-add-cart-btn, .search-buy-btn {
            padding: 8px 18px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
        }
        .search-add-cart-btn { background: #ff9900; color: #fff; }
        .search-add-cart-btn:hover { background: #ffae42; }
        .search-buy-btn { background: #131921; color: #fff; }
        .search-buy-btn:hover { background: #232f3e; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container header-wrapper">
            <a href="index.php" class="logo"><img src="pio.png" alt="Cart" class="cart-icon" style="height:50px;"></a>
            <div class="search-box">
                <form action="search.php" method="get" style="display:flex;width:100%;">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search Kaalu" style="flex:1;">
                    <button type="submit">SEARCH</button>
                </form>
            </div>
        </div>
    </header>
    <div class="search-hero">
        <div class="search-hero-title">Search Results</div>
        <div class="search-hero-query">for '<?php echo htmlspecialchars($search_query); ?>'</div>
    </div>
    <main class="container">
        <?php if ($search_query === ''): ?>
            <p style="text-align:center;font-size:1.2rem;">Please enter a search term.</p>
        <?php elseif (empty($results)): ?>
            <p style="text-align:center;font-size:1.2rem;">No products found.</p>
        <?php else: ?>
            <div class="search-results-grid">
                <?php foreach ($results as $product): ?>
                <div class="search-product-card">
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['img']); ?>" class="search-product-img" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    </a>
                    <div class="search-product-title"><?php echo htmlspecialchars($product['title']); ?></div>
                    <div class="search-product-price">Rs. <?php echo number_format($product['price']); ?></div>
                    <div class="search-product-actions">
                        <button class="search-add-cart-btn">Add to Cart</button>
                        <button class="search-buy-btn">Buy Now</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
