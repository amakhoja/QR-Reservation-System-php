<?php
session_start();
require_once 'config.php'; // Database configuration file

// Database configuration settings
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = ''; // Assuming no password is set for the root user
$dbName     = 'reservation';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Could not connect to the database $dbName :" . $e->getMessage());
}

// Define variables and initialize with empty values
$username = $email = $password = $role = "";
$username_err = $email_err = $password_err = $role_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        // Check if email already exists
        $checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $checkEmail->bindParam(":email", $param_email, PDO::PARAM_STR);
        $param_email = trim($_POST["email"]);
        $checkEmail->execute();

        if ($checkEmail->rowCount() > 0) {
            $email_err = "This email is already taken.";
        } else {
            $email = $param_email;
        }
    }

    // Validate password
    if (isset($_POST["password"])) {
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter a password.";
        } elseif (strlen(trim($_POST["password"])) < 6) {
            $password_err = "Password must have at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
        }
    } else {
        $password_err = "Password is required.";
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please select a role.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($role_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);

            // Set parameters and hash the password
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_role = $role;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to manage users page
                header("location: manage_users.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <link rel="stylesheet" href="add_user.css"> <!-- Ensure the CSS path is correct -->
</head>
<body>
    <h2>Add New User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <!-- Email input -->
<div>
    <label>Email</label>
    <input type="email" name="email" value="<?php echo $email; ?>" required>
    <span class="error"><?php echo $email_err; ?></span>
</div>

        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Role</label>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="staff">user</option>
                <option value="staff">hotel Admin</option>
                <!-- Add other roles as needed -->
            </select>
            <span><?php echo $role_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Add User">
        </div>
    </form>
</body>
</html>
