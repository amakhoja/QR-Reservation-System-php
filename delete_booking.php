<?php
// Start the session and include database configuration
require_once 'config.php'; // Ensure this path is correct for your setup

if (isset($_POST['id'])) {
    $bookingId = $_POST['id'];

    // Establish a new database connection
    $con = new mysqli("localhost", "root", "", "reservation");

    // Check connection
    if ($con->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $con->connect_error]);
        exit;
    }

    // Prepare the statement to delete the booking
    $stmt = $con->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);

    // Attempt to execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete booking']);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided']);
}
?>
