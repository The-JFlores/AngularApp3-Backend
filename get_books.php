

<?php

include 'db.php';

// Return JSON response
header('Content-Type: application/json');

// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// SQL query to retrieve all books
$sql = "SELECT * FROM books ORDER BY id DESC";
$result = $conn->query($sql);

$books = array();

// Convert each database row into an array item
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);