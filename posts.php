<?php
session_start();

// Prevent page caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['user_data'])) {
    // Extract user data from the session
    $user = $_SESSION['user_data'];
    
    // Check if 'username' and 'profile_pic' are set in the session and use them
    $username = isset($user['username']) ? htmlspecialchars($user['username']) : 'No Username';
    $profilePic = isset($user['profile_pic']) && !empty($user['profile_pic']) ? $user['profile_pic'] : 'images/default.png';
    $email = isset($user['email']) ? htmlspecialchars($user['email']) : 'No Email';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- Include Tailwind CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body class="bg-gray-100">
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
    <!-- Display profile picture and username -->
    <div class="flex items-center mb-6">
        <img class="w-10 h-10 rounded-full mr-4" src="<?php echo $profilePic; ?>" alt="Profile Picture">
        <h2 class="text-lg font-medium text-gray-700"><?php echo $username; ?></h2>
    </div>

    <form action="submit_post.php" method="post" enctype="multipart/form-data" class="space-y-4">
        <div class="flex items-center">
            <i class="fas fa-pencil-alt text-gray-900 mr-2"></i>
            <label for="postContent" class="block text-sm font-medium text-gray-700">What's on your mind?</label>
        </div>
        <input type="text" id="postContent" name="postContent" required class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 placeholder-gray-500">

        <div class="flex items-center">
            <i class="fas fa-image text-gray-900 mr-2"></i>
            <label for="postImage" class="block text-sm font-medium text-gray-700">Upload Image:</label>
        </div>
        <input type="file" id="postImage" name="postImage" accept="image/*" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">

        <button type="submit" name="submitPost" class="w-full bg-gray-900 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">Post</button>
    </form>
</div>
    <!-- Post display section -->
    <?php
include 'db_connect.php';
$sql = "SELECT p.content, p.image, p.post_id, u.username, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='max-w-md mx-auto bg-white p-4 rounded-lg shadow-lg mt-4 relative'>
                <button class='absolute right-2 top-2 text-gray-600 hover:text-gray-800'>
                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
                        <path stroke-linecap='round' stroke-linejoin='round' d='M6 18L18 6M6 6l12 12' />
                    </svg>
                </button>
                <div class='flex items-center space-x-4'>
                    <img src='" . htmlspecialchars($row['profile_pic']) . "' alt='Profile Pic' class='w-12 h-12 rounded-full'>
                    <h3 class='text-lg font-bold'>" . htmlspecialchars($row['username']) . "</h3>
                </div>
                <img src='" . htmlspecialchars($row['image']) . "' alt='Post Image' class='mt-3 rounded'>
                <p class='mt-2 text-gray-600'>" . htmlspecialchars($row['content']) . "</p>
                <div class='flex justify-between items-center mt-4'>
                <div class='flex space-x-4'>
                <a href='#' class='flex items-center text-gray-900  rounded-lg'>
                    <i class='fas fa-thumbs-up mr-1'></i>
                    
                </a>
                <a href='#' class='flex items-center text-gray-900'>
                    <i class='fas fa-comment mr-1'></i>
                    
                </a>
            </div>
                </div>
              </div>";
    }
} else {
    echo "<p class='text-center text-gray-500 mt-10'>No posts yet!</p>";
}
?>

    <!-- JavaScript for deleting posts -->
    <script>
    function deletePost(postId) {
        if (confirm('Are you sure you want to delete this post?')) {
            fetch('delete_post.php', {
                method: 'POST',
                body: JSON.stringify({ post_id: postId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Post deleted successfully!');
                    location.reload(); // Reload the page to update the list of posts
                } else {
                    alert('Failed to delete the post.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }
    </script>
</body>
</html>
