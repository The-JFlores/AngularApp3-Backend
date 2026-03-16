

<?php

include 'db.php';

// Read the JSON body sent by Angular
$data = json_decode(file_get_contents("php://input"), true);

// Extract the values from the request
$title = $data['title'] ?? '';
$author = $data['author'] ?? '';
$description = $data['description'] ?? '';

// Validate that all fields were provided
if ($title && $author && $description) {
    $stmt = $conn->prepare("INSERT INTO books (title, author, description) VALUES (?, ?, ?)");

    $stmt->bind_param("sss", $title, $author, $description);

    if ($stmt->execute()) {
        header('Content-Type: application/json');
        echo json_encode(["message" => "Book added successfully"]);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Failed to add book"]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(["message" => "Missing required fields"]);
}