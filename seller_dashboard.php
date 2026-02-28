<?php
// seller_dashboard.php: Seller dashboard to add items
session_start();
require_once 'config.php';

if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_register.php');
    exit();
}

// Handle item add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $name = trim($_POST['item_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $seller_id = $_SESSION['seller_id'];
    $img_path = '';

    // Handle image upload - FIXED VERSION
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Create uploads directory if it doesn't exist
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Get file extension
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allow only image files
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            // Generate unique filename
            $unique_name = uniqid('item_', true) . '.' . $file_ext;
            $img_path = $upload_dir . $unique_name;
            
            // Move uploaded file
            if (move_uploaded_file($file_tmp, $img_path)) {
                // File uploaded successfully
                $success = 'Item added successfully!';
            } else {
                $error = 'Failed to upload image.';
                $img_path = '';
            }
        } else {
            $error = 'Only JPG, JPEG, PNG, GIF & WebP files are allowed.';
        }
    } else {
        $error = 'Please select an image file to upload.';
    }

    if ($name && $category && $price > 0 && $img_path) {
        $sql = "INSERT INTO items (seller_id, name, category, price, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issds', $seller_id, $name, $category, $price, $img_path);
        if ($stmt->execute()) {
            $success = 'Item added successfully!';
        } else {
            $error = 'Failed to save item to database: ' . $conn->error;
        }
        $stmt->close();
    } else {
        if (empty($error)) {
            $error = 'Please fill all fields and upload a photo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Kaalu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .seller-dashboard { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 32px; }
        .seller-dashboard h2 { text-align: center; margin-bottom: 24px; }
        .seller-dashboard form { display: flex; flex-direction: column; gap: 14px; }
        .seller-dashboard input, .seller-dashboard select { padding: 10px; border-radius: 6px; border: 1px solid #ddd; }
        .seller-dashboard button { padding: 12px; background: #ff9900; color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 1.1rem; }
        .seller-dashboard button:hover { background: #ffae42; }
        .success-msg { color: green; text-align: center; margin-bottom: 12px; }
        .error-msg { color: red; text-align: center; margin-bottom: 12px; }
        .preview-img { max-width: 200px; max-height: 200px; margin: 10px auto; display: none; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="seller-dashboard">
        <h2>Add New Item</h2>
        <?php if (!empty($success)) echo '<div class="success-msg">' . htmlspecialchars($success) . '</div>'; ?>
        <?php if (!empty($error)) echo '<div class="error-msg">' . htmlspecialchars($error) . '</div>'; ?>
        
        <!-- Debug info (remove after testing) -->
        <?php if (!empty($img_path)): ?>
            <div style="text-align:center;margin:10px 0;padding:10px;background:#f5f5f5;border-radius:5px;">
                <small>Image path stored: <?php echo htmlspecialchars($img_path); ?></small><br>
                <small>File exists: <?php echo file_exists($img_path) ? 'YES' : 'NO'; ?></small>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data" id="itemForm">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Electronics">Electronics</option>
                <option value="Fashion">Fashion</option>
                <option value="Home">Home</option>
                <option value="Toys">Toys</option>
                <option value="Books">Books</option>
                <option value="Other">Other</option>
            </select>
            <input type="number" name="price" placeholder="Price (Rs.)" min="1" required>
            
            <!-- Image upload with preview -->
            <input type="file" name="photo" id="photoInput" accept="image/*" required>
            <img id="imagePreview" class="preview-img" alt="Preview">
            
            <button type="submit" name="add_item">Add Item</button>
        </form>
    </div>
    
    <script>
        // Image preview functionality
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>