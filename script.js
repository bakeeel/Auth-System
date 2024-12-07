function toggleForms(form) {
    const signInForm = document.getElementById('signinForm');
    const signUpForm = document.getElementById('signupForm');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');

    signInForm.style.display = 'none';
    signUpForm.style.display = 'none';
    forgotPasswordForm.style.display = 'none';

    switch(form) {
        case 'signin':
            signInForm.style.display = 'block';
            break;
        case 'signup':
            signUpForm.style.display = 'block';
            break;
        case 'forgot':
            forgotPasswordForm.style.display = 'block';
            break;
    }
}

function showForgotPassword() {
    toggleForms('forgot');
}

// Theme toggle functionality
function toggleTheme() {
    const body = document.body;
    const icon = document.querySelector('.theme-toggle i');
    
    // Toggle dark mode class
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');
    
    // Toggle icon
    if (body.classList.contains('dark-mode')) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
    
    // Save theme preference
    localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
}

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const icon = document.querySelector('.theme-toggle i');
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        body.classList.remove('light-mode');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        body.classList.add('light-mode');
        body.classList.remove('dark-mode');
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
});

// Password strength validation
document.querySelectorAll('input[type="password"]').forEach(input => {
    input.addEventListener('input', function() {
        if (this.name === 'password' && this.form.name === 'signup') {
            const strength = checkPasswordStrength(this.value);
            // You can add visual feedback for password strength here
        }
    });
});

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[!@#$%^&*(),.?":{}|<>]+/)) strength++;
    return strength;
}

// Password visibility toggle
function togglePasswordVisibility(icon) {
    const input = icon.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Verify email function
function verifyEmail(event) {
    event.preventDefault();
    const email = document.getElementById('resetEmail').value;
    
    // Store email for later use
    document.getElementById('confirmedEmail').value = email;
    
    // Show reset password form
    document.getElementById('forgotPasswordForm').style.display = 'none';
    document.getElementById('resetPasswordForm').style.display = 'block';
}

// Language Change Function
function changeLanguage(lang) {
    // Store language preference
    localStorage.setItem('preferredLanguage', lang);
    // Redirect with language parameter
    window.location.href = `index.php?lang=${lang}`;
}

// Toggle language dropdown
function toggleLanguageDropdown() {
    const dropdown = document.getElementById('languageDropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('languageDropdown');
    const selector = document.querySelector('.language-selector');
    
    if (dropdown && selector && !selector.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

// Initialize theme and language
document.addEventListener('DOMContentLoaded', function() {
    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.classList.toggle('dark-mode', savedTheme === 'dark');
});

// Toggle password visibility
function togglePasswordVisibility(icon) {
    const input = icon.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Toggle forms
function toggleForms(formType) {
    const signInForm = document.getElementById('signinForm');
    const signUpForm = document.getElementById('signupForm');
    const forgotForm = document.getElementById('forgotPasswordForm');
    
    signInForm.style.display = 'none';
    signUpForm.style.display = 'none';
    forgotForm.style.display = 'none';
    
    switch(formType) {
        case 'signin':
            signInForm.style.display = 'block';
            break;
        case 'signup':
            signUpForm.style.display = 'block';
            break;
        case 'forgot':
            forgotForm.style.display = 'block';
            break;
    }
}
