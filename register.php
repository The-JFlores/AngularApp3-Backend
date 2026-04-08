

<?php

include 'db.php';

// Return JSON response
header('Content-Type: application/json');

// Read JSON data sent by Angular
$data = json_decode(file_get_contents("php://input"), true);

// Extract the values from the request
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Validate required fields
if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields"]);
    exit();
}

// Check if the email already exists
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["message" => "Email already registered"]);
    $checkStmt->close();
    exit();
}

$checkStmt->close();

// Hash the password before saving it
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user
$insertStmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$insertStmt->bind_param("ss", $email, $hashedPassword);

if ($insertStmt->execute()) {
    echo json_encode(["message" => "User registered successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to register user"]);
}

$insertStmt->close();
?>