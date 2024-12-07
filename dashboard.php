<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user information
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Welcome</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">
    <!-- Welcome Popup -->
    <div class="welcome-popup" id="welcomePopup">
        <div class="popup-content">
            <i class="fas fa-code popup-icon"></i>
            <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
            <p>Happy coding! 🚀</p>
            <button onclick="closePopup()">Get Started</button>
        </div>
    </div>

    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-code"></i> Developer Dashboard</h1>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <main class="dashboard-content">
        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-project-diagram"></i>
                <h3>Projects</h3>
                <p>Manage your coding projects</p>
            </div>
            <div class="card">
                <i class="fas fa-tasks"></i>
                <h3>Tasks</h3>
                <p>Track your development tasks</p>
            </div>
            <div class="card">
                <i class="fas fa-code-branch"></i>
                <h3>Repository</h3>
                <p>Access your code repositories</p>
            </div>
            <div class="card">
                <i class="fas fa-bug"></i>
                <h3>Debug</h3>
                <p>Track and fix issues</p>
            </div>
        </div>
    </main>

    <script>
        // Show popup when page loads
        window.addEventListener('load', () => {
            document.getElementById('welcomePopup').classList.add('show');
        });

        // Close popup
        function closePopup() {
            document.getElementById('welcomePopup').classList.remove('show');
        }
    </script>
</body>
</html>
