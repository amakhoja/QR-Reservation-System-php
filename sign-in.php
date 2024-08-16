<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("config.php"); // Ensure this path is correct
session_start();

$error = ''; // Initialize the error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email/username and password have been submitted
    if (!empty($_POST['email_or_username']) && !empty($_POST['password'])) {
        $email_or_username = trim($_POST['email_or_username']);
        $password = $_POST['password']; // It's already ensured that $password is set

        // Prepare the statement to avoid SQL injection
        if ($stmt = $con->prepare("SELECT id, password, username, is_admin FROM users WHERE email = ? OR username = ?")) {
            $stmt->bind_param("ss", $email_or_username, $email_or_username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashedPassword, $username, $is_admin);
                $stmt->fetch();
                if (password_verify($password, $hashedPassword)) {
                    // Regenerate session ID upon successful login
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['username'] = $username;
                    $_SESSION['id'] = $id;
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['is_admin'] = $is_admin; // Set the admin status

                    // Redirect to home page or admin dashboard based on user role
                    if ($is_admin) {
                        header("Location: Admin.php");
                    } else {
                        header("Location: Home_page.php");
                    }
                    exit;
                } else {
                    $error = "Incorrect username/email or password.";
                }
            } else {
                $error = "Incorrect username/email or password.";
            }
            $stmt->close();
        }
    } else {
        $error = "Please enter both username/email and password.";
    }
}

// Consider placing your HTML login form here or redirect/include as necessary
// This ensures the form is displayed again in case of an error with a way to show $error to the user
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
    <link rel="stylesheet" href="./style.css">
    <title>Sign in</title>
</head>
<body style="background-color: white;">
    <!-- Navigation content here -->
    <header>
         <nav>
            <ul>
                <li><a href="Home_page.php">Home</a></li>
                <!-- Other navigation items here -->
            </ul>
        </nav>
    </header>
   
    <div class="Sign-container">
        <div class="form">
            <h2>Sign In</h2>

            <?php if($error != ''): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form class="Sign-form" action="" method="post">
                <label for="email_or_username">Username/Email</label>
                <input type="text" autocomplete="off" name="email_or_username" id="email_or_username" required>
                <label for="password">Password</label>
                <input type="password" autocomplete="off" name="password" id="password" required>
                <input class="submit" type="submit" value="Sign In">
                <p>Don't have an account? <a href="sign-up.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>