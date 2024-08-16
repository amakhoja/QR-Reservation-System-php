<?php
session_start();
// Replace 'db_connect.php' with the actual path to your database connection file
require_once 'config.php';


$bookings = [];

try {
    // Prepare and execute query to fetch all bookings
    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY date DESC, StartTime DESC");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="manage_bookings.css"> 
</head>
<body>
    <h1>Manage Bookings</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>End Time</th>
                <th>Start Time</th>
                <th>Seat Number</th>
                <th>Date</th>
                <th>Room</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                <td><?php echo htmlspecialchars($booking['EndTime']); ?></td>
                <td><?php echo htmlspecialchars($booking['StartTime']); ?></td>
                <td><?php echo htmlspecialchars($booking['seat_number']); ?></td>
                <td><?php echo htmlspecialchars($booking['date']); ?></td>
                <td><?php echo htmlspecialchars($booking['room']); ?></td>
                <td>
                    <a href="edit_booking.php?id=<?php echo $booking['id']; ?>">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
