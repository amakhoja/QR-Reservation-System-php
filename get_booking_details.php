<?php
// Database configuration
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName     = 'reservation';

// Connect to the database
$con = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check for errors
if ($con->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection error: ' . $con->connect_error]);
    exit;
}

// Get booking ID from POST request
$bookingId = isset($_POST['id']) ? $_POST['id'] : '';

if (empty($bookingId)) {
    echo json_encode(['success' => false, 'message' => 'No Booking ID provided']);
    exit;
}

// Prepare SQL statement to prevent SQL injection
$stmt = $con->prepare("SELECT id, user_id, date, room, status FROM bookings WHERE id = ?");
$stmt->bind_param("i", $bookingId);

// Execute the statement
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetch the booking data
        $bookingData = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $bookingData]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Query failed: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$con->close();
?>
