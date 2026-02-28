<?php
// product.php: Show product details and allow add to cart or buy
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
$category = null;

// ONLY check database, no static array
if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, name, price, photo, category FROM items WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_id, $db_name, $db_price, $db_photo, $db_category);
        $stmt->fetch();
        $product = [
            'id' => $db_id,
            'title' => $db_name,
            'price' => $db_price,
            'desc' => '',
            'img' => $db_photo
        ];
        $category = $db_category;
    }
    $stmt->close();
}

if (!$product) {
    // Product not found in database
    echo "<h2 style='text-align:center;margin:50px;'>Product not found.</h2>";
    echo "<p style='text-align:center;'><a href='index.php'>Go back to homepage</a></p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['title']); ?> - Kaalu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-detail-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; display: flex; gap: 40px; padding: 32px; }
        .product-detail-img { width: 400px; height: 400px; object-fit: contain; border-radius: 8px; background: #f5f5f5; }
        .product-detail-info { flex: 1; display: flex; flex-direction: column; }
        .product-detail-title { font-size: 2rem; font-weight: bold; margin-bottom: 12px; }
        .product-detail-price { font-size: 1.5rem; color: #e47911; margin-bottom: 16px; }
        .product-detail-desc { font-size: 1.1rem; margin-bottom: 24px; }
        .product-detail-actions { display: flex; gap: 16px; align-items: center; }
        .product-detail-actions input[type=number] { width: 60px; padding: 6px; font-size: 1rem; }
        .add-cart-btn, .buy-now-btn { padding: 12px 28px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; }
        .add-cart-btn { background: #ff9900; color: #fff; }
        .add-cart-btn:hover { background: #ffae42; }
        .buy-now-btn { background: #131921; color: #fff; }
        .buy-now-btn:hover { background: #232f3e; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="product-detail-container">
        <?php
        // Handle image path
        $img_url = $product['img'];
        
        // If it's not a full URL, prepend uploads/
        if (strpos($img_url, 'http') !== 0 && strpos($img_url, '//') !== 0) {
            // Remove leading slash if exists
            $img_url = ltrim($img_url, '/');
            
            // Check if file exists at the path
            if (!file_exists($img_url)) {
                // Try with uploads/ prefix
                if (file_exists('uploads/' . $img_url)) {
                    $img_url = 'uploads/' . $img_url;
                } elseif (file_exists('uploads/' . basename($img_url))) {
                    $img_url = 'uploads/' . basename($img_url);
                } else {
                    // Use placeholder
                    $img_url = 'https://placehold.co/400x400/png?text=' . urlencode($product['title']);
                }
            }
        }
        ?>
        <img src="<?php echo htmlspecialchars($img_url); ?>" 
             class="product-detail-img" 
             alt="<?php echo htmlspecialchars($product['title']); ?>"
             onerror="this.onerror=null; this.src='https://placehold.co/400x400/png?text=<?php echo urlencode($product['title']); ?>';">
        <div class="product-detail-info">
            <div class="product-detail-title"><?php echo htmlspecialchars($product['title']); ?></div>
            <div class="product-detail-price">Rs. <?php echo number_format($product['price']); ?></div>
            <?php if (!empty($category)): ?>
                <div class="product-detail-desc"><b>Category:</b> <?php echo htmlspecialchars($category); ?></div>
            <?php endif; ?>
            <form class="product-detail-actions" method="post" action="#">
                <label>Quantity: <input type="number" name="qty" value="1" min="1"></label>
                <button type="submit" class="add-cart-btn">Add to Cart</button>
                <button type="submit" class="buy-now-btn">Buy Now</button>
            </form>
        </div>
    </div>
    
    <!-- Recommended products - only from database -->
    <div class="container" style="margin-top:40px;">
        <h2 style="font-size:1.3rem;margin-bottom:18px;">Recommended Products</h2>
        <div class="products-grid">
        <?php
        if (!empty($category)) {
            // Recommend other items in the same category
            $stmt = $conn->prepare("SELECT id, name, price, photo FROM items WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4");
            $stmt->bind_param('si', $category, $id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $rec_img = $row['photo'];
                    // Fix image path
                    if (strpos($rec_img, 'http') !== 0) {
                        $rec_img = ltrim($rec_img, '/');
                        if (!file_exists($rec_img) && file_exists('uploads/' . $rec_img)) {
                            $rec_img = 'uploads/' . $rec_img;
                        }
                    }
                    
                    echo '<a href="product.php?id=' . $row['id'] . '" class="product-card">';
                    echo '<img src="' . htmlspecialchars($rec_img) . '" class="product-img" onerror="this.onerror=null; this.src=\'https://placehold.co/200x200/png?text=' . urlencode($row['name']) . '\';">';
                    echo '<div class="product-title">' . htmlspecialchars($row['name']) . '</div>';
                    echo '<div class="product-price">Rs. ' . number_format($row['price']) . '</div>';
                    echo '</a>';
                }
            } else {
                // Show random items if no same category
                $random_result = $conn->query("SELECT id, name, price, photo FROM items WHERE id != $id ORDER BY RAND() LIMIT 4");
                while ($row = $random_result->fetch_assoc()) {
                    echo '<a href="product.php?id=' . $row['id'] . '" class="product-card">';
                    echo '<img src="' . htmlspecialchars($row['photo']) . '" class="product-img">';
                    echo '<div class="product-title">' . htmlspecialchars($row['name']) . '</div>';
                    echo '<div class="product-price">Rs. ' . number_format($row['price']) . '</div>';
                    echo '</a>';
                }
            }
            $stmt->close();
        } else {
            // Show random items
            $random_result = $conn->query("SELECT id, name, price, photo FROM items WHERE id != $id ORDER BY RAND() LIMIT 4");
            if ($random_result->num_rows > 0) {
                while ($row = $random_result->fetch_assoc()) {
                    echo '<a href="product.php?id=' . $row['id'] . '" class="product-card">';
                    echo '<img src="' . htmlspecialchars($row['photo']) . '" class="product-img">';
                    echo '<div class="product-title">' . htmlspecialchars($row['name']) . '</div>';
                    echo '<div class="product-price">Rs. ' . number_format($row['price']) . '</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>No other products available.</p>';
            }
        }
        ?>
        </div>
    </div>
</body>
</html>