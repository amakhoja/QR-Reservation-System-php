<?php
session_start();
require_once 'config.php'; // Ensure this file contains correct database configuration

// Connect to the database
try {
    $pdo = new PDO("mysql:host=localhost;dbname=reservation;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Could not connect to the database :" . $e->getMessage());
}

$user = null; // Initialize $user variable
$userId = $_GET['id'] ?? null; // Use null coalescing operator to handle absence of 'id'

// Check if the 'id' GET variable is set and if it is numeric
if ($userId && is_numeric($userId)) {

    // Prepare a select statement to fetch user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if (!$user) {
        exit('User not found!');
    }
} else {
    exit('No ID specified!');
}

// Process form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Update user code remains the same...
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Check for existing bookings associated with this user
    $bookingStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = :userId");
    $bookingStmt->execute(['userId' => $userId]);
    $bookingsCount = $bookingStmt->fetchColumn();

    if ($bookingsCount > 0) {
        // Handle the case where the user has associated bookings
        echo 'Cannot delete user because they have associated bookings. Please delete the bookings first.';
    } else {
        // If no bookings are associated, proceed with user deletion
        $deleteStmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $deleteStmt->execute(['id' => $userId]);

        if ($deleteStmt->rowCount() > 0) {
            echo "User deleted successfully.";
            header("location: manage_users.php");
            exit;
        } else {
            echo "Error deleting user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit user, deleter</title>
    <link rel="stylesheet" href="delete_user.css"> <!-- Verify this CSS file path -->
</head>
<body>
    <div class="header">
        <div class="container">
          
            <nav>
                <a href="Home_page.php">Home</a>
                <a href="manage_users.php">Manage Users</a>
                
                <!-- Add more navigation links as needed -->
            </nav>
        </div>
    </div>

    <div class="content container">
       

        <?php if ($user): ?>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="role">Role:</label>
            <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($user['role']); ?>" required>
            <input type="submit" name="update" value="Update User">
            <!-- Delete button -->
            <input type="submit" name="delete" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
        </form>
        <?php else: ?>
        <p>User not found.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="container">
            &copy; 2024 Your Website Name. All rights reserved.
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Use</a>
            <!-- Add more links or information as needed -->
        </div>
    </div>
</body>
</html>
