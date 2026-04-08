

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

// Find the user by email
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verify the password against the stored hash
    if (password_verify($password, $row['password'])) {
        echo json_encode([
            "message" => "Login successful",
            "userId" => $row['id'],
            "email" => $email
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid email or password"]);
    }
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid email or password"]);
}

$stmt->close();
?>