<?php
// Set the content type to JSON as the JavaScript expects a JSON response
header('Content-Type: application/json');
session_start();

// Include the database connection file
require_once 'config.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // Do not trim password

    // Basic server-side validation
    if (empty($email) || empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters.']);
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an INSERT statement
    $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sss", $param_username, $param_email, $param_password);

        // Set parameters
        $param_username = $username;
        $param_email = $email;
        $param_password = $hashed_password;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Registration successful! Please log in.']);
        } else {
            // Check for duplicate entry error (error code 1062)
            if ($conn->errno == 1062) {
                echo json_encode(['status' => 'error', 'message' => 'Email or Username is already registered.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again later.']);
            }
        }

        // Close statement
        $stmt->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close connection
$conn->close();
?>