

<?php

include 'db.php';

// Read form data sent by Angular
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$description = $_POST['description'] ?? '';

// Default image value
$coverImage = '';

// Check if a file was uploaded
if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
    $uploadDirectory = 'uploads/';
    $fileName = time() . '_' . basename($_FILES['cover']['name']);
    $targetFile = $uploadDirectory . $fileName;

    if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetFile)) {
        $coverImage = $fileName;
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Failed to upload file"]);
        exit();
    }
}

// Validate that all fields were provided
if ($title && $author && $description) {
    $stmt = $conn->prepare("INSERT INTO books (title, author, description, cover_image) VALUES (?, ?, ?, ?)");

$stmt->bind_param("ssss", $title, $author, $description, $coverImage);

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