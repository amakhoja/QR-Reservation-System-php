<?php
// Your database connection details
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName     = 'reservation';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbName :" . $e->getMessage());
}

$user = null;

// Check if the 'id' GET variable is set and if it is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare a select statement
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    
    // Bind the ID to the prepared statement
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    
    // Execute the statement and fetch the user data
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if user exists
    if(!$user){
        exit('User not found!');
    }
} else {
    exit('No ID specified!');
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Prepare an update statement
    $query = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($query);

    // Bind parameters to statement
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    echo 'User updated successfully!';
    // Redirect to the user list or some other page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit_user.css">
</head>
<body>
    <div class="header">
        <nav>
            <a href="Home_page.php">Home</a>
            <a href="Admin.php">Dashboard</a>
            <!-- Additional navigation links as needed -->
        </nav>
    </div>

    <h2>Edit User</h2>
    <?php if ($user): ?>
    <form action="edit_user.php?id=<?php echo htmlspecialchars($userId); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($userId); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <label for="role">Role:</label>
        <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($user['role']); ?>" required>
        <input type="submit" value="Update User">
    </form>
    <?php else: ?>
    <p>User not found.</p>
    <?php endif; ?>

    <div class="footer">
        &copy; <?= date('Y'); ?> Your Website. All rights reserved.
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
    </div>
</body>
</html>
