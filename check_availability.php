<?php
// Include your database configuration
include("config.php");

header('Content-Type: application/json');

// Get the JSON POSTed data
$data = json_decode(file_get_contents("php://input"), true);

$room = mysqli_real_escape_string($con, $data['room']);
$date = mysqli_real_escape_string($con, $data['date']);

// Prepare the SQL query to check room availability
$query = "SELECT COUNT(*) FROM bookings WHERE room = '$room' AND date = '$date'";

$result = mysqli_query($con, $query);

if (!$result) {
    echo json_encode(['error' => "Query failed: " . mysqli_error($con)]);
    exit;
}

$row = mysqli_fetch_array($result, MYSQLI_NUM);
$isAvailable = $row[0] == 0; // Room is available if count is 0

echo json_encode(['isAvailable' => $isAvailable]);

// Close the connection
mysqli_close($con);
?>
