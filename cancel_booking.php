<?php
session_start();

// Check if the user is logged in and the request is POST
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] && $_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config.php'; // Adjust the path as needed

    $bookingId = $_POST['booking_id'];
    $userId = $_SESSION['user_id']; // Make sure the booking belongs to the user

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bookingId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Booking cancelled successfully.";
    } else {
        echo "Error cancelling booking or booking not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Not authorized or wrong request method.";
}
?>
