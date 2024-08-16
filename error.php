<?php
session_start();

// Default error message
$defaultErrorMessage = "An error occurred. Please try again later.";

// Check for an error message passed as a session variable
if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    // Clear the error message from the session to avoid showing the same message on page refresh or navigation
    unset($_SESSION['error_message']);
} elseif (isset($_GET['msg'])) {
    // Sanitize the message passed in the query string to avoid XSS attacks
    $errorMessage = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $errorMessage = $defaultErrorMessage;
}

// Optional: Log the error message to a file or error logging system here

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="admin.css"> <!-- Ensure the CSS path is correct -->
</head>
<body>
    <div class="container">
        <h1>Error</h1>
        <p><?= $errorMessage; ?></p>
        <a href="Admin.php">Return to Dashboard</a> <!-- Replace 'dashboard.php' with the actual dashboard page -->
    </div>
</body>
</html>
