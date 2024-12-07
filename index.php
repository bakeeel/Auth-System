<?php
session_start();
require_once 'translations.php';

// Set language from GET parameter or session or default to English
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

$lang = $_SESSION['lang'];
$t = $translations[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['sign_in']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="<?php echo $lang === 'ar' ? 'rtl' : ''; ?>">
    <button class="theme-toggle " onclick="toggleTheme()">
        <i class="fas fa-moon"></i>
    </button>
    <div class="language-selector">
        <div class="selected-language" onclick="toggleLanguageDropdown()">
            <img src="https://flagcdn.com/w20/<?php echo $lang === 'ar' ? 'sa' : 'gb' ?>.png"
                alt="<?php echo $lang === 'ar' ? 'Arabic' : 'English' ?>">
            <span><?php echo $lang === 'ar' ? 'العربية' : 'English' ?></span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="language-dropdown" id="languageDropdown">
            <a href="#" onclick="changeLanguage('en')" class="language-option <?php echo $lang === 'en' ? 'active' : ''; ?>">
                <img src="https://flagcdn.com/w20/gb.png" alt="English">
                <span>English</span>
            </a>
            <a href="#" onclick="changeLanguage('ar')" class="language-option <?php echo $lang === 'ar' ? 'active' : ''; ?>">
                <img src="https://flagcdn.com/w20/sa.png" alt="Arabic">
                <span>العربية</span>
            </a>
        </div>
    </div>
    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Sign In Form -->
        <form id="signinForm" class="form-container" method="post" action="process.php">
            <h2><?php echo $t['sign_in']; ?></h2>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="<?php echo $t['username']; ?>" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="<?php echo $t['password']; ?>" required>
                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
            </div>
            <div class="forgot-password">
                <a href="#" onclick="showForgotPassword()"><?php echo $t['forgot_password']; ?></a>
            </div>
            <button type="submit" name="signin"><?php echo $t['sign_in']; ?></button>
            <p class="toggle-form"><?php echo $t['dont_have_account']; ?> <a href="#" onclick="toggleForms('signup')"><?php echo $t['sign_up']; ?></a></p>
        </form>

        <!-- Sign Up Form -->
        <form id="signupForm" class="form-container" method="post" action="process.php" style="display: none;">
            <h2><?php echo $t['create_account']; ?></h2>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="<?php echo $t['full_name']; ?>" required>
            </div>
            <div class="input-group">
                <i class="fas fa-user-circle"></i>
                <input type="text" name="username" placeholder="<?php echo $t['username']; ?>" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="<?php echo $t['email']; ?>" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="<?php echo $t['password']; ?>" required>
                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="<?php echo $t['confirm_password']; ?>" required>
                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
            </div>
            <button type="submit" name="signup"><?php echo $t['sign_up']; ?></button>

            <p class="toggle-form"><?php echo $t['already_have_account']; ?> <a href="#" onclick="toggleForms('signin')"><?php echo $t['sign_in']; ?></a></p>
        </form>

        <!-- Forgot Password Form -->
        <form id="forgotPasswordForm" class="form-container" style="display: none;">
            <h2><?php echo $t['reset_password']; ?></h2>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="resetEmail" placeholder="<?php echo $t['enter_email']; ?>" required>
            </div>
            <button type="button" onclick="verifyEmail(event)"><?php echo $t['verify_email']; ?></button>
            <p class="toggle-form"><a href="#" onclick="toggleForms('signin')"><?php echo $t['back_to_sign_in']; ?></a></p>
        </form>

        <!-- Reset Password Form -->
        <form id="resetPasswordForm" class="form-container" method="post" action="process.php" style="display: none;">
            <h2><?php echo $t['set_new_password']; ?></h2>
            <input type="hidden" name="email" id="confirmedEmail">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="new_password" placeholder="<?php echo $t['new_password']; ?>" required>
                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="<?php echo $t['confirm_new_password']; ?>" required>
                <i class="fas fa-eye password-toggle" onclick="togglePasswordVisibility(this)"></i>
            </div>
            <button type="submit" name="reset_password_direct"><?php echo $t['reset_password']; ?></button>
            <p class="toggle-form"><a href="#" onclick="toggleForms('signin')"><?php echo $t['back_to_sign_in']; ?></a></p>
        </form>
    </div>

    <script src="script.js"></script>
</body>
<footer class="text-center text-lg-start" style="background-color: #7c3aed;">
    <div class="container d-flex justify-content-center py-5">

        <button type="button" class="btn btn-primary btn-lg btn-floating mx-2 m-44" style="background-color: #54456b;">
            <a href="https://github.com/bakeeel" style="color: white;" target="_blank"> <i class="fab fa-github"></i></a>
        </button>

        <button type="button" class="btn btn-primary btn-lg btn-floating mx-2 m-44" style="background-color: #54456b;">
            <a href="https://www.linkedin.com/in/bakeel-murshed/" style="color: white;" target="_blank"><i class="fab fa-linkedin"></i></a>

        </button>

        <button type="button" class="btn btn-primary btn-lg btn-floating mx-2 m-44" style="background-color: #54456b;">
            <a href="https://bakeelmurshed.com/" style="color: white;" target="_blank"> <i class="fas fa-code"></i> </a>
        </button>

        <button type="button" class="btn btn-primary btn-lg btn-floating mx-2 m-44" style="background-color: #54456b;">
            <i class="fab fa-twitter"></i>
        </button>
    </div>

    <!-- Copyright -->
    <div class="text-center text-white p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        2024 Copyright:
        <a class="text-white" target="_blank" href="https://bakeelmurshed.com/">bakeelmurshed</a>
    </div>
    <!-- Copyright -->
</footer>

</html>