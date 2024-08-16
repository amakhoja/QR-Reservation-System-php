<?php
session_start();
require 'config.php'; // Include your database configuration file

$bookingDetails = false; // Initialize $bookingDetails to false
$cancelMessage = ''; // Initialize cancel message to empty

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: sign-in.php');
    exit;
}

// Handle the cancel booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking']) && isset($_POST['booking_id'])) {
    $bookingId = filter_var($_POST['booking_id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare the SQL statement to update the booking status
    $stmt = $con->prepare("UPDATE bookings SET Status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $cancelMessage = "Booking cancelled successfully.";
    } else {
        $cancelMessage = "Failed to cancel booking or booking was already cancelled.";
    }

    $stmt->close();
}

if (isset($_SESSION['id']) && !$cancelMessage) {
    $userId = $_SESSION['id'];
    
    // Fetch booking details from the database
    $stmt = $con->prepare("SELECT id, name, room, date, barcode_id, seat_number FROM bookings WHERE user_id = ? AND (Status IS NULL OR Status != 'cancelled') ORDER BY date DESC LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bookingDetails = $result->fetch_assoc();
        $qrCodeFilename = $bookingDetails['barcode_id'] . '.png';
        $qrCodeWebPath = 'qr_codes/' . $qrCodeFilename;
    } else {
        $cancelMessage = "";
    }
    
    $stmt->close();
} else {
    echo "User ID not found in session.";
}

$con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <header>
    </div>    
    </script>
    <div class="header">
        <a href="home_page.php">Home</a>
      
        
    </div>
    </header>
    <meta charset="UTF-8">
    <title>Your Booking Details</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .header, .footer {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        .header a, .footer a {
            color: #fff;
            text-decoration: none;
            padding: 0 15px;
        }
        .header a:hover, .footer a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        p {
            font-size: 18px;
            line-height: 1.6;
        }
        img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 10px auto;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 20px 0;
            background-color: #5cb85c;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #449d44;
        }
    </style>

</head>
<body>
<div class="container">
    <h1>Booking Details</h1>
    <?php if ($bookingDetails): ?>
        <p>Name: <?php echo htmlspecialchars($bookingDetails['name']); ?></p>
        <p>Room: <?php echo htmlspecialchars($bookingDetails['room']); ?></p>
        <p>Date: <?php echo htmlspecialchars($bookingDetails['date']); ?></p>
        <p>Seat Number: <?php echo htmlspecialchars($bookingDetails['seat_number']); ?></p>
        <!-- Display other details as needed -->

        <?php if (file_exists(__DIR__ . '/qr_codes/' . $qrCodeFilename)): ?>
            <p>QR Code:</p>
            <img src="<?php echo htmlspecialchars($qrCodeWebPath); ?>" alt="QR Code">
        <?php else: ?>
            <p>QR Code image not found.</p>
        <?php endif; ?>
    <?php else: ?>
        <p></p>
    <?php endif; ?>
    
    <?php if (!empty($cancelMessage)): ?>
        <p><?php echo htmlspecialchars($cancelMessage); ?></p>
    <?php endif; ?>

    <?php if ($bookingDetails): ?>
        <!-- ... booking details here ... -->

        <!-- Cancel booking button -->
        <form method="post">
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($bookingDetails['id']); ?>">
            <input type="submit" name="cancel_booking" value="Cancel Booking">
        </form>
    <?php else: ?>
        <p>Booking details not found or booking has been cancelled.</p>
    <?php endif; ?>

    </div>
    
    <div class="footer">
        <a href="contact_page.php">Contact Us</a>
    </div> 
</body>
</html>
