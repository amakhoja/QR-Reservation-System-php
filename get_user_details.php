<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Include database configuration
require_once 'config.php'; // Update path as needed




// Check if the user ID is provided in the POST request
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $userId = $_POST['id'];

    // Establish a new database connection using mysqli
    $con = new mysqli("localhost", "root", "", "reservation");

    // Check connection
    if ($con->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $con->connect_error]);
        exit;
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $con->prepare("SELECT id, username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId); // 'i' specifies the variable type => 'integer'

    // Execute the statement
    $stmt->execute();
    
    // Store the result so we can check if the record exists
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        // Bind the result variables
        $stmt->bind_result($id, $username, $email);
        
        // Fetch value
        $stmt->fetch();
        
        // Send a JSON response
        echo json_encode(['success' => true, 'id' => $id, 'username' => $username, 'email' => $email]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    
    // Close statement and connection
    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No user ID provided']);
}
    ?>
