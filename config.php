<?php
// Database configuration settings
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = ''; // Assuming no password is set for the root user
$dbName     = 'reservation';

// mysqli connection
$con = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if (!$con) {
    // Handle error - notify administrator, log to a file, show a generic error message, etc.
    error_log('MySQLi Connection Error: ' . mysqli_connect_error());
    // Optionally, you can redirect to a custom error page
    // header('Location: error_page.php');
    exit;
}

// PDO connection
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('PDO Connection Error: ' . $e->getMessage());
    // Optionally, redirect to a custom error page
    // header('Location: error_page.php');
    exit;
}
?>
