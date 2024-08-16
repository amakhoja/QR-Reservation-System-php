<?php
// Start the session
session_start();

// Include database configuration
require_once 'config.php'; // Ensure the database configuration file path is correct

// Authentication and Authorization checks
// Make sure the user is logged in and is an admin before allowing an update
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'], $_POST['username'], $_POST['email'])) {
    $userId = $_POST['userId'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Create a database connection
    $con = new mysqli("localhost", "root", "", "reservation");

    // Check connection
    if ($con->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection error: ' . $con->connect_error]);
        exit;
    }

    // Prepare SQL statement to update user details
    $stmt = $con->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $userId);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Incomplete form data']);
}
?>
