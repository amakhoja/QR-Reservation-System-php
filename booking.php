<?php
session_start();

// Check if the user is logged in and has an ID set in the session
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['id'])) {
    header('Location: sign-in.php');
    exit('User is not logged in or user ID is not set in the session.');
}

// Use $_SESSION['id'] since that's the key where the user ID is stored
$userId = $_SESSION['id'];

require 'vendor/autoload.php'; // Composer's autoload file

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

include("config.php"); // Database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $selectedRoom = filter_input(INPUT_POST, 'room', FILTER_SANITIZE_STRING);
    $selectedDate = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $uniqueIdentifier = uniqid('', true);

    // Define the absolute path for saving QR code images
    $qrCodeDir = __DIR__ . '/qr_codes/';
    $qrCodePath = $qrCodeDir . $uniqueIdentifier . '.png';

    // Check if GD extension is loaded
    if (!extension_loaded('gd')) {
        die("QR Code generation failed: GD extension is not loaded. Please check your PHP configuration.");
    }

    // Initialize QR code object
    $qrCode = new QrCode($uniqueIdentifier);
    $qrCode->setSize(300);

    // Initialize the PNG Writer
    $writer = new PngWriter();

    // Check if the directory exists and is writable
    if (!file_exists($qrCodeDir) && !mkdir($qrCodeDir, 0755, true) && !is_dir($qrCodeDir)) {
        die("Failed to create directories...");
    }

    // Write the QR code file
    try {
        $writer->write($qrCode)->saveToFile($qrCodePath);
        $_SESSION['qr_code_path'] = $qrCodePath;
    } catch (Exception $e) {
        die('QR Code generation failed: ' . $e->getMessage());
    }

    // Prepare the SQL statement
    $stmt = $con->prepare("INSERT INTO bookings (user_id, name, room, date, barcode_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $userName, $selectedRoom, $selectedDate, $uniqueIdentifier);

    if ($stmt->execute()) {
        $_SESSION['last_booking_id'] = $stmt->insert_id;
        header('Location: booking_details.php');
        exit;
    } else {
        die("Error: Failed to create booking. " . $stmt->error);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Meeting Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body, h1, form, label, select, input {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            color: #444;
            line-height: 1.6em;
            padding: 50px;
        }

        .container {
            background: #fff;
            padding: 20px;
            max-width: 600px;
            margin: 30px auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin-bottom: 8px;
            font-weight: 500;
        }

        form select, form input[type=date], form input[type=submit] {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Roboto', sans-serif;
        }

        form select:hover, form input[type=date]:hover {
            border-color: #5cb85c;
        }

        form input[type=submit] {
            background-color: #5cb85c;
            color: white;
            cursor: pointer;
        }

        form input[type=submit]:hover {
            background-color: #449d44;
        }

        @media (min-width: 600px) {
            form select, form input[type=date], form input[type=submit] {
                width: 48%;
                margin-right: 4%;
            }

            form input[type=submit] {
                width: auto;
                margin-right: 0;
            }
        }

        @media (max-width: 600px) {
            form select, form input[type=date], form input[type=submit] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
        <h1>Book a Meeting Room</h1>
        <form action="booking.php" method="post" id="bookingForm">
        <label for="name">Name:</label>
              <input type="text" id="name" name="name" required>
            
            <label for="room">Choose a room:</label>
            <select id="room" name="room" aria-label="Choose a room" required>
                <option value="">Select a room</option>
                <option value="small">Small Room (6 chairs)</option>
                <option value="medium">Medium Room (12 chairs)</option>
                <option value="large">Large Room (30 chairs)</option>
            </select>
         

             

            <label for="date">Choose a date:</label>
            <input type="date" id="date" name="date" aria-label="Choose a date" required>
            
            <input type="submit" value="Book">
        </form>
    </div>
    <script>
     
     document.getElementById('bookingForm').onsubmit = function(event) {
            var name = document.getElementById('name').value.trim();
            var room = document.getElementById('room').value;
            var date = document.getElementById('date').value;

            if (!name) {
                alert('Please enter your name.');
                event.preventDefault(); // Prevent form submission
                return false;
            }

            if (!room) {
                alert('Please select a room.');
                event.preventDefault(); // Prevent form submission
                return false;
            }

            if (!date) {
                alert('Please select a date.');
                event.preventDefault(); // Prevent form submission
                return false;
            }

    // Confirmation before booking
    var confirmBooking = confirm("Confirm booking for " + room + " on " + date + "?");
    if (!confirmBooking) {
        event.preventDefault(); // Prevent form submission if user cancels
        return false;
    }
    document.querySelector('form').onsubmit = function(event) {
    event.preventDefault(); // Prevent form submission to wait for AJAX call

    var room = document.getElementById('room').value;
    var date = document.getElementById('date').value;

    // Ensure a room and date are selected
    if (!room || !date) {
        alert('Please select both a room and a date.');
        return false;
    }

    // AJAX call to check availability
    fetch('check_availability.php', {
      method: 'POST',
      body: JSON.stringify({room: room, date: date}),
      headers: {
        'Content-Type': 'application/json'
      }
    }).then(response => response.json())
      .then(data => {
        if (!data.isAvailable) {
          alert('This room is not available on the selected date. Please choose another date.');
        } else {
          // Proceed with form submission or further processing here
          alert('Room is available! Booking now.');
          // For actual form submission, you might redirect or manually submit another form
          // For this example, just redirecting to home page or booking confirmation page
          window.location.href = 'home_page.php';
        }
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('There was an error checking the room availability.');
      });
};

};

    </script>
</body>
</html>

        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedRoom = $_POST['room'];
    $selectedDate = $_POST['date'];

    // Database connection details
    $servername = "your_servername"; // e.g., localhost
    $username = "your_username"; // e.g., root
    $password = "your_password";
    $dbname = "your_dbname"; // e.g., reservation

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO bookings (room, date) VALUES (?, ?)");
    $stmt->bind_param("ss", $room, $date);

    // Set parameters and execute
    $room = $selectedRoom;
    $date = $selectedDate;
    $stmt->execute();

    // Success message
    echo "<script>alert('You have successfully booked the " . htmlspecialchars($selectedRoom) . " for " . htmlspecialchars($selectedDate) . ".'); window.location.href='home_page.php';</script>";

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


    </div>
    <script>
        document.querySelector('form').onsubmit = function(event) {
    var room = document.getElementById('room').value;
    var date = document.getElementById('date').value;
    
    if (!room) {
        alert('Please select a room.');
        event.preventDefault(); // Prevent form submission
        return false;
    }
    
    if (!date) {
        alert('Please select a date.');
        event.preventDefault(); // Prevent form submission
        return false;
    }
    
    // Further validations or processing can be added here
};

    </script>
</body>
</html>
