<!-- body.php -->
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
    <title>Friendzone</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

 

</head>
<body class="bg-gray-100">
    <!-- left  -->
    <div class="flex flex-col justify-between  px-4 py-8 overflow-y-auto bg-gray-900 rounded-b-full rtl:border-r-0 rtl:border-l dark:bg-gray-900 dark:border-gray-700">
        <a href="#" class="mx-auto flex items-center justify-evenly">
            <img class="w-auto px-2 bg-white py-2 h-10 sm:h-10 rounded-full mr-2" src="images/ficon.png" alt="">
            <h2 class="text-xl text-white">Friendzone</h2>
        </a>

        <div class="flex flex-col items-center justify-center mt-8">
            <!-- Display profile picture, username, and email -->
            <img class="object-cover border border-gray-900 w-24 h-24 rounded-full" src="<?php echo $profilePic; ?>" alt="Profile Picture">
            <h4 class="mt-2 font-medium text-gray-100 dark:text-gray-200"><?php echo $username; ?></h4>
            <p class="mt-1 text-sm font-medium text-gray-100 dark:text-gray-400"><?php echo $email; ?></p>
        </div>

        <div class="flex justify-center mt-8">
            <nav class="flex justify-center">
                <a id="posts-link" class="flex mr-2 justify-center items-center px-4 py-2 text-gray-700 hover:text-gray-100  transition-colors duration-300 transform rounded-lg  bg-gray-100 hover:bg-gray-800 " href="#">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="mx-4 font-medium">Posts</span>
                </a>

                <a id="explore-link" class="flex ml-2 justify-center items-center px-4 py-2 text-gray-700 hover:text-gray-100  transition-colors duration-300 transform rounded-lg  bg-gray-100 hover:bg-gray-800 " href="#">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="mx-4 font-medium">Friends</span>
                </a>
            </nav>
        </div>

        <!-- Logout button -->
        <form action="logout.php" method="post" class="flex justify-center mt-4">
            <button type="submit" name="logout" class="flex justify-center items-center px-4 py-2 text-gray-100 bg-gray-900 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="mx-2 font-medium flex justify-center">Logout</span>
            </button>
        </form>
    </div>

    <!-- right div -->
    <div id="content-div" style="padding: 20px; flex-grow: 1;"></div>

    <!-- body.php -->
    <script>
        // Function to load posts content into the content-div
        function loadposts() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("posts.php content retrieved successfully");
                        document.getElementById('content-div').innerHTML = xhr.responseText;
                    } else {
                        // Handle error
                        console.error('Error loading posts.php');
                    }
                }
            };
            xhr.open('GET', 'posts.php', true);
            xhr.send();
        }

        // Load posts content by default
        window.onload = loadposts;

        // Event listener for posts link
        document.getElementById('posts-link').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            console.log("posts link clicked");
            loadposts(); // Load posts content
        });

        // Event listener for explore link
        document.getElementById('explore-link').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            console.log("Explore link clicked");

            // Load friends.php into the content-div
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Friends.php content retrieved successfully");
                        document.getElementById('content-div').innerHTML = xhr.responseText;
                    } else {
                        // Handle error
                        console.error('Error loading friends.php');
                    }
                }
            };
            xhr.open('GET', 'friends.php', true);
            xhr.send();
        });
    </script>
</body>
</html>
