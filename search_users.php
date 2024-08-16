<?php
session_start();
require_once 'config.php'; // Database configuration file

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Could not connect to the database $dbName :" . $e->getMessage());
}

$searchTerm = '';
$users = [];

// Check if the search term exists in the URL query
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);

    // Prepare a SELECT statement to fetch users matching the search term
    $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE username LIKE :searchTerm OR email LIKE :searchTerm");
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
    
    // Execute the prepared statement
    $stmt->execute();

    // Fetch all matching records
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="search_users.css"> <!-- Make sure this CSS file exists and is correctly linked -->
</head>
<body>
    <h2>Search Results for '<?php echo htmlspecialchars($searchTerm); ?>'</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
        <?php if (empty($users)): ?>
            <tr>
                <td colspan="4">No users found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
