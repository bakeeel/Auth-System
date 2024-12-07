<?php
session_start();
require_once 'config.php';
require_once 'translations.php';

$lang = $_SESSION['lang'] ?? 'en';
$t = $translations[$lang];

// Sign Up Process
if (isset($_POST['signup'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header("Location: index.php?error=" . urlencode($t['passwords_not_match']));
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: index.php?error=" . urlencode($t['username_or_email_exists']));
        exit();
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: index.php?success=" . urlencode($t['account_created']));
    } else {
        header("Location: index.php?error=" . urlencode($t['registration_failed']));
    }
    exit();
}

// Sign In Process
if (isset($_POST['signin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Debug log
    error_log("Login attempt for username: " . $username);

    // Validate empty fields
    if (empty($username) || empty($password)) {
        header("Location: index.php?error=" . urlencode($t['please_fill_in_all_fields']));
        exit();
    }

    try {
        // Check user credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Debug log
        error_log("User found: " . ($user ? "Yes" : "No"));

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];

            // Debug log
            error_log("Login successful for user: " . $user['username']);

            header("Location: dashboard.php");
            exit();
        } else {
            // Debug log
            error_log("Password verification failed");
            header("Location: index.php?error=" . urlencode($t['invalid_username_or_password']));
            exit();
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        header("Location: index.php?error=" . urlencode($t['an_error_occurred']));
        exit();
    }
}

// Reset Password Process
if (isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        header("Location: reset-password.php?token=" . urlencode($token) . "&error=" . urlencode($t['passwords_not_match']));
        exit();
    }

    // Validate password strength
    if (strlen($new_password) < 8) {
        header("Location: reset-password.php?token=" . urlencode($token) . "&error=" . urlencode($t['password_must_be_at_least_8_characters']));
        exit();
    }

    try {
        // Verify token and get user
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW() LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            header("Location: index.php?error=" . urlencode($t['invalid_or_expired_reset_link']));
            exit();
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user['id']);

        if ($stmt->execute()) {
            header("Location: index.php?success=" . urlencode($t['password_has_been_reset']));
        } else {
            header("Location: reset-password.php?token=" . urlencode($token) . "&error=" . urlencode($t['failed_to_reset_password']));
        }
    } catch (Exception $e) {
        error_log("Password reset error: " . $e->getMessage());
        header("Location: reset-password.php?token=" . urlencode($token) . "&error=" . urlencode($t['an_error_occurred']));
    }
    exit();
}

// Forgot Password Process
if (isset($_POST['forgot_password'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (empty($email)) {
        header("Location: index.php?error=" . urlencode($t['please_enter_your_email']));
        exit();
    }

    try {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Save token to database
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expiry, $email);
            $stmt->execute();

            // Create reset link
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/login/reset-password.php?token=" . $token;

            // For demonstration, we'll show the link in the success message
            // In production, you should send this via email
            header("Location: index.php?success=" . urlencode($t['password_reset_link']) . urlencode($reset_link));
        } else {
            header("Location: index.php?error=" . urlencode($t['email_not_found']));
        }
    } catch (Exception $e) {
        error_log("Forgot password error: " . $e->getMessage());
        header("Location: index.php?error=" . urlencode($t['an_error_occurred']));
    }
    exit();
}

// Email Verification for Password Reset
if (isset($_POST['verify_email'])) {
    header('Content-Type: application/json');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $t['email_not_found']]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $t['an_error_occurred']]);
    }
    exit();
}

// Direct Password Reset
if (isset($_POST['reset_password_direct'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        header("Location: reset-password.php?error=" . urlencode($t['passwords_not_match']));
        exit();
    }

    try {
        // Get user by email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            header("Location: index.php?error=" . urlencode($t['email_not_found']));
            exit();
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user['id']);

        if ($stmt->execute()) {
            header("Location: index.php?success=" . urlencode($t['reset_success']));
        } else {
            header("Location: index.php?error=" . urlencode($t['reset_failed']));
        }
    } catch (Exception $e) {
        error_log("Password reset error: " . $e->getMessage());
        header("Location: index.php?error=" . urlencode($t['an_error_occurred']));
    }
    exit();
}
