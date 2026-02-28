<?php
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Change this to your database username
define('DB_PASSWORD', '');     // Change this to your database password
define('DB_NAME', 'kaalu_db');

// Attempt to establish a MySQL connection using MySQLi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // Return a JSON error message if the connection fails (for AJAX)
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}
// Note: If connection is successful, $conn object is now available for use in other files.
?>