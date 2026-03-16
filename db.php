

<?php

// Allow requests from the Angular application
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "angular_books";

// Create a new database connection
$conn = new mysqli($host, $username, $password, $database);

// Stop the script if the connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}