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

    <style>
        /* Sticky sidebar */
        .sticky {
            position: sticky;
            top: 0;
            z-index: 1000; /* Adjust as needed */
        }
    </style>

</head>
<body>
    <!-- left  -->
<div class="flex justify-between">
<div class="flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-white border-r rtl:border-r-0 rtl:border-l dark:bg-gray-900 dark:border-gray-700">
    <a href="#" class="mx-auto flex items-center justify-evenly">
        <img class="w-auto h-6  sm:h-7" src="images/ficon.png" alt="">
        <h2 class="text-xl">Friendzone</h2>
    </a>

    <div class="flex flex-col items-center mt-6 -mx-2">



                <!-- Display profile picture, username, and email -->
                <img class="object-cover border border-gray-900 w-24 h-24 mx-2 rounded-full" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                <h4 class="mx-2 mt-2 font-medium text-gray-800 dark:text-gray-200"><?php echo $username; ?></h4>
                <p class="mx-2 mt-1 text-sm font-medium text-gray-600 dark:text-gray-400"><?php echo $email; ?></p>
    </div>

    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav>
            <a id="posts-link" class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-200" href="#">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <span class="mx-4 font-medium">posts</span>
            </a>

            <a id="explore-link"  class="flex items-center px-4 py-2 mt-5 text-gray-600 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700" href="#">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <span class="mx-4 font-medium">Friends</span>
            </a>



        </nav>

        <!-- Logout button -->
        <form action="logout.php" method="post">
            <button type="submit" name="logout" class="flex items-center px-4 py-2 mt-5 text-gray-100 bg-gray-900 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="mx-4 font-medium">Logout</span>
            </button>
        </form>
    </div>
</div>
        <!-- right div -->
        <div id="content-div" style="padding: 20px; flex-grow: 1;">
            <!-- Content will be updated here -->
        </div>

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





</div>
</body>
</html>
