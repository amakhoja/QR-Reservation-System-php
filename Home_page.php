<?php
session_start(); // Start or resume a session
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assume $db is your database connection
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example SQL query, use prepared statements to prevent SQL injection
    $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: welcome.php");
    } else {
        // Handle login failure
        echo "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="path_to_css/home-style.css">

    <meta charset="UTF-8">
    <meta name="description" content="Efficient meeting room reservation system with real-time availability checks and easy booking.">
    <meta name="keywords" content="meeting room, reservation system, booking, real-time availability">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Room Reservation System</title>
    <link rel="stylesheet" href="home-style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="sign-up.php">Register</a></li>
                <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                    <li>
                        <form action="logout.php" method="post" style="display: inline;">
                            <button type="submit" name="logout" class="nav-button">Logout</button>
                        </form>
                    </li>
                <?php endif; ?>
                <li><a href="Admin.php">Admin</a></li>
                <li><a href="booking.php">Book Now</a></li>
                <li><a href="#contact">Contact Us</a></li>
                <li><a href="booking_details.php">My Booking</a></li>
                <?php if (!isset($_SESSION['user_logged_in'])): ?>
                    <li><a href="sign-in.php">Sign In</a></li>
                <?php else: ?>
                    <li>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <article id="booking">
            <h2>Effortless Booking</h2>
            <p>Book meeting rooms with ease using our interactive calendar. Check availability and make reservations instantly with just a few clicks.</p>
        </article>
        <article id="availability">
            <h2>Real-Time Availability</h2>
            <p>View real-time room availability through our dynamic scheduling interface. Avoid double bookings or scheduling conflicts with live updates.</p>
        </article>
        <article id="interface">
            <h2>User-Friendly Interface</h2>
            <p>Our intuitive interface now includes a guided tour for first-time users. Accessible from any device, making managing your reservations simpler than ever.</p>
        </article>
    </main>
    <footer>
        <aside id="contact">
            <p>Contact us for more information or support. We're here to help you streamline your meeting room bookings.</p>
        </aside>
    </footer>
</body>
</html>

    <script>
        // Example JavaScript for a basic feature such as a guided tour or alert
        document.addEventListener('DOMContentLoaded', function() {
            // Example functionality for initiating a guided tour or similar
            var startTour = function() {
                alert('Starting the guided tour!');
            };
            // Assuming there's a button or link with id="start-tour" for initiating the tour
            var tourButton = document.getElementById('start-tour');
            if (tourButton) {
                tourButton.addEventListener('click', startTour);
            }
        });
    </script>
    <script>
        //navigation Enhancmennnt 
        document.addEventListener('DOMContentLoaded', function() {
    var navButtons = document.querySelectorAll('.nav-button');

    navButtons.forEach(function(button) {
        button.style.cursor = 'pointer'; // Change cursor on hover to indicate clickable
        button.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#f0f0f0'; // Change to your desired hover background color
        });
        button.addEventListener('mouseout', function() {
            this.style.backgroundColor = ''; // Reset background color on mouse out
        });
        button.addEventListener('click', function() {
            this.style.backgroundColor = '#ccc'; // Change to your desired click background color
            // Redirect to the button's href attribute value if necessary
            // window.location.href = this.getAttribute('href');
        });
    });
});
    </script>
  <script>
        // JavaScript for enhanced navigation interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Code for guided tour or other features
        });

        // JavaScript for enhanced button interactivity
        document.querySelectorAll('.nav-button').forEach(function(button) {
            button.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#f0f0f0';
            });
            button.addEventListener('mouseout', function() {
                this.style.backgroundColor = '';
            });
            button.addEventListener('click', function() {
                this.style.backgroundColor = '#ccc';
                // Additional functionality for button click
            });
        });
    </script>
</body>
</html>