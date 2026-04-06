

<?php

include 'db.php';

// Return JSON response
header('Content-Type: application/json');

// Read the book id from the form data
$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$description = $_POST['description'] ?? '';

// Validate required fields
if (!$id || !$title || !$author || !$description) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields"]);
    exit();
}

// Get the current image value from the database
$currentImage = '';

$selectStmt = $conn->prepare("SELECT cover_image FROM books WHERE id = ?");
$selectStmt->bind_param("i", $id);
$selectStmt->execute();
$result = $selectStmt->get_result();

if ($row = $result->fetch_assoc()) {
    $currentImage = $row['cover_image'];
}

$selectStmt->close();

// Default image value remains the current one
$coverImage = $currentImage;

// Check if a new file was uploaded
if (isset($_FILES['cover'])) {
    if ($_FILES['cover']['error'] === 0) {
        $uploadDirectory = 'uploads/';
        $fileName = time() . '_' . basename($_FILES['cover']['name']);
        $targetFile = $uploadDirectory . $fileName;

        if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetFile)) {
            $coverImage = $fileName;
        } else {
            http_response_code(500);
            echo json_encode([
                "message" => "Failed to upload file",
                "tmp_name" => $_FILES['cover']['tmp_name'],
                "target_file" => $targetFile
            ]);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "message" => "Upload error",
            "file_error_code" => $_FILES['cover']['error']
        ]);
        exit();
    }
}

// Update the book record
$updateStmt = $conn->prepare("
    UPDATE books
    SET title = ?, author = ?, description = ?, cover_image = ?
    WHERE id = ?
");

$updateStmt->bind_param("ssssi", $title, $author, $description, $coverImage, $id);

if ($updateStmt->execute()) {
    echo json_encode(["message" => "Book updated successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to update book"]);
}

$updateStmt->close();
?>