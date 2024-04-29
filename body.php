

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
?>


<!-- body.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Inside body.php -->

<!-- ... other HTML content ... -->

<form action="logout.php" method="post">
    <button type="submit" name="logout">Log Out</button>
</form>

<!-- ... rest of your body content ... -->

    
</body>
</html>
