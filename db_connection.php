<?php
$host = "localhost";
$username = "root"; // default XAMPP username
$password = ""; // default XAMPP password is empty
$database = "kaalu_shop";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>