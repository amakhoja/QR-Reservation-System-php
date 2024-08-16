<?php
// Start session and include database configuration
session_start();
require_once 'config.php';

// Check for admin session here...

// Fetch all users from the database
try {
    $stmt = $pdo->query("SELECT id, username, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Query failed: " . $e->getMessage());
    die("Query failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage_users.css"> 
</head>
<body>
    <div class="header">
        <nav>
          
            <!-- Additional navigation links as needed -->
        </nav>
    </div>

    <h2>Manage Users</h2>
    
    <!-- Search form -->
    <form method="GET" action="search_users.php">
        <input type="text" name="search" placeholder="Insert Username">
        <input type="submit" value="Search">
    </form>
    
    <!-- Add user form -->
    <h3>Add User</h3>
    <form method="POST" action="add_user.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="staff">Hotel Staff</option>
            <!-- Add other roles as necessary -->
        </select>
        <input type="submit" name="add_user" value="Add User">
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']); ?></td>
                    <td><?= htmlspecialchars($user['username']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id']; ?>">Edit</a> |
                        <a href="delete_user.php?id=<?= $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No users found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="footer">
        &copy; <?= date('Y'); ?>   All rights reserved.
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="Home_page.php">Home</a>
            <a href="Admin.php">Dashboard</a>
          
    </div>
</body>
</html>
