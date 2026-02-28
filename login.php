<?php
// Set the content type to JSON as the JavaScript expects a JSON response
header('Content-Type: application/json');
session_start();

// Include the database connection file
require_once 'config.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Prepare a SELECT statement
    $sql = "SELECT id, username, password_hash FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_email);

        // Set parameters
        $param_email = $email;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $stmt->store_result();

            // Check if email exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($id, $username, $hashed_password);

                if ($stmt->fetch()) {
                    // Verify password against the hash
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        
                        // Send success response
                        echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'username' => $username]);
                    } else {
                        // Password is not valid
                        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
                    }
                }
            } else {
                // Email doesn't exist
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Oops! Something went wrong.']);
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