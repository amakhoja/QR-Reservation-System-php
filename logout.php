<?php
session_start();

// Only proceed if the logout action was submitted
if (isset($_POST['logout'])) {
    // Unset all session values
    $_SESSION = array();

    // Delete the session cookie if it exists
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy session
    session_destroy();

    // Redirect to the home page
    header('Location: Home_page.php');
    exit;
}
?>
