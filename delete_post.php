<?php
session_start();
include 'db_connect.php'; // Ensure your database connection is correctly included

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'Post ID not provided']);
    exit;
}

$post_id = $input['post_id'];
// Optionally, log the post_id to a file to ensure it's correct
file_put_contents('debug.txt', "Trying to delete post: $post_id\n", FILE_APPEND);

$sql = "DELETE FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No rows affected. Check post ID: ' . $post_id]);
}

$stmt->close();
$conn->close();
?>