

<?php

include 'db.php';

// Return JSON response
header('Content-Type: application/json');

// Get the book id from the query string
$id = $_GET['id'] ?? null;

// Validate that the id was provided
if ($id) {
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Book deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete book"]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing book id"]);
}