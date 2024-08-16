<?session_start();

// Include database configuration
require_once 'config.php'; // Make sure this path is correct

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: sign-in.php');
    exit('Access Denied. You must be an admin to view this page.');
}
// After successfully verifying the user's credentials
$_SESSION['user_logged_in'] = true;
$_SESSION['is_admin'] = $userFromDatabase['is_admin']; // This should be the value fetched from the database

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Administrator Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <script src="admin.js" defer></script>
</head>
<body>

    <nav id="main-navbar" role="navigation">
        <div class="container">
            <h1 class="logo">Admin Panel</h1>
            <div class="navbar-links">
                <a href="#" id="menu-toggle">&#9776; Menu</a>
                
            </div>
        </div>
    </nav>

    <div id="sidebar" class="active">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">Admin Dashboard</a>
            </li>
            <li>
            <a href="#" id="manage-users">Manage Users</a>


            </li>
            <li>
                <a href="#rooms">Manage Rooms</a>
            </li>
            <li>
                <a href="#reports">Reports</a>
            </li>
            <li>
                <a href="#analytics">Analytics</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
           
        </ul>
    </div>

    <header id="header" role="banner">
    <div class="container">
        <div class="header-left">
            
        </div>
        <!-- Adding a home button in the center -->
        <div class="header-center">
            <a href="Home_page.php" class="home-button">Home</a>
        </div>
        <div class="header-right">
            <span>Welcome, <strong>Admin Name</strong></span>
        </div>
    </div>
</header>


        <main id="content" role="main">
            <div class="container">
                <!-- Dynamic content goes here -->
                <section id="dashboard-home">
                    <h2>Dashboard</h2>
                    <p>Welcome to your admin dashboard. Here you can manage users, view reports, and analyze system data.</p>
                </section>
            </div>
        </main>

        <footer id="footer" role="contentinfo">
            <div class="container">
                &copy; <span id="year"></span> Admin Dashboard. 
            </div>
        </footer>
    </div>
<!-- ... other HTML content ... -->

<script src="admin.js" defer></script>
</body>
</html>

</body>
</html>
