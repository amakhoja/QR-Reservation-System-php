<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php'; // Ensure this file contains correct database connection details

// Initialize $bookingId with a default value
$bookingId = 0;

// Check if 'id' is set in the URL (GET request) and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookingId = intval($_GET['id']);
} else {
    // Redirect to an error page if 'id' is not set or is not numeric
    header("Location: error.php"); // Replace 'error.php' with your error page
    exit;
}

$booking = null;

// Fetch booking data from the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = :id");
    $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // No booking found with the given ID
        header("Location: error.php"); // Replace 'error.php' with your error page
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Process form submission for updating booking data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assume all necessary POST variables are set and sanitized
    // Update the booking details in the database
    try {
        $updateStmt = $pdo->prepare("UPDATE bookings SET status = :status, EndTime = :EndTime, StartTime = :StartTime, seat_number = :seat_number, date = :date, room_id = :room_id WHERE id = :id");
        
        // Bind parameters from the form
        $updateStmt->bindParam(':status', $_POST['status']);
        $updateStmt->bindParam(':EndTime', $_POST['EndTime']);
        $updateStmt->bindParam(':StartTime', $_POST['StartTime']);
        $updateStmt->bindParam(':seat_number', $_POST['seat_number']);
        $updateStmt->bindParam(':date', $_POST['date']);
        $updateStmt->bindParam(':room_id', $_POST['room_id']);
        $updateStmt->bindParam(':id', $bookingId, PDO::PARAM_INT);

        // Execute the update statement
        $updateStmt->execute();

        // Check if the update was successful
        if ($updateStmt->rowCount() > 0) {
            // Booking updated successfully
            echo "Booking updated successfully!";
        } else {
            // No changes were made to the booking
            echo "No changes were made to the booking.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="admin.css"> <!-- Make sure this path to CSS is correct -->
</head>
<body>
    <h2>Edit Booking</h2>
    <?php if ($booking): ?>
        <form action="edit_booking.php?id=<?= $bookingId ?>" method="post">
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" value="<?= htmlspecialchars($booking['status']); ?>" required>
            <!-- Repeat for other fields... -->
            <input type="submit" value="Update Booking">
        </form>
    <?php else: ?>
        <p>No booking found to edit.</p>
    <?php endif; ?>
</body>
</html>
