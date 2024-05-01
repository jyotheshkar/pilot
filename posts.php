<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
</head>
<body>
    <div>
        <form action="submit_post.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="postContent">What's on your mind?</label>
                <input type="text" id="postContent" name="postContent" required>
            </div>
            <div>
                <label for="postImage">Upload Image:</label>
                <input type="file" id="postImage" name="postImage" accept="image/*">
            </div>
            <button type="submit" name="submitPost">Post</button>
        </form>
    </div>
</body>
</html>

<?php
include 'db_connect.php';
$sql = "SELECT p.content, p.image, u.username, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>
                <img src='" . htmlspecialchars($row['profile_pic']) . "' alt='Profile Pic' style='width: 50px; height: 50px;'>
                <h3>" . htmlspecialchars($row['username']) . "</h3>
                <img src='" . htmlspecialchars($row['image']) . "' alt='Post Image'>
                <p>" . htmlspecialchars($row['content']) . "</p>
              </div>";
    }
} else {
    echo "No posts yet!";
}
?>
