<?php
session_start();
require_once 'config.php';

// Verify token
if (!isset($_GET['token'])) {
    header("Location: index.php?error=Invalid reset link");
    exit();
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW() LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: index.php?error=Invalid or expired reset link");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dark-mode">
    <button class="theme-toggle" onclick="toggleTheme()">
        <i class="fas fa-sun"></i>
    </button>
    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="process.php" method="post">
                <h2>Reset Password</h2>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
                </div>
                <button type="submit" name="reset_password">Reset Password</button>
                <p class="toggle-form">
                    <a href="index.php">Back to Login</a>
                </p>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
