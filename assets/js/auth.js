// Register Page Specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    
    // Register page specific elements
    if (registerForm) {
        initializeRegisterPage();
    }
    
    // Common auth functionality
    initializeAuthCommon();
});

// Initialize register page
function initializeRegisterPage() {
    const registerForm = document.getElementById('registerForm');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const registerBtn = document.getElementById('registerBtn');
    const messageAlert = document.getElementById('messageAlert');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordText = document.getElementById('passwordText');

    // Password visibility toggle
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            togglePasswordVisibility(passwordInput, this);
        });
    }

    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', function() {
            togglePasswordVisibility(confirmPasswordInput, this);
        });
    }

    // Password strength indicator
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });
    }

    // Form validation and submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateRegisterForm()) {
                registerUser();
            }
        });
    }

    // Real-time form validation for register form
    const inputs = registerForm.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateRegisterField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateRegisterField(this);
            }
        });
    });

    // Confirm password validation
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordMatch();
        });
    }

    // Terms agreement validation
    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms) {
        agreeTerms.addEventListener('change', function() {
            validateTermsAgreement();
        });
    }
}

// Common auth functionality
function initializeAuthCommon() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        // Login form functionality (from previous code)
        initializeLoginPage();
    }
}

// Initialize login page (from previous code, extracted as function)
function initializeLoginPage() {
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');
    const messageAlert = document.getElementById('messageAlert');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            togglePasswordVisibility(passwordInput, this);
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateLoginForm()) {
                loginUser();
            }
        });
    }

    // Social login handlers
    const googleBtn = document.querySelector('.btn-google');
    const microsoftBtn = document.querySelector('.btn-microsoft');
    
    if (googleBtn) {
        googleBtn.addEventListener('click', function() {
            showAlert('Google registration would be implemented here', 'info');
        });
    }
    
    if (microsoftBtn) {
        microsoftBtn.addEventListener('click', function() {
            showAlert('Microsoft registration would be implemented here', 'info');
        });
    }
}

// Toggle password visibility
function togglePasswordVisibility(inputElement, toggleButton) {
    const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
    inputElement.setAttribute('type', type);
    
    const icon = toggleButton.querySelector('i');
    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// Update password strength indicator
function updatePasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordText');
    
    if (!strengthBar || !strengthText) return;
    
    let strength = 0;
    let text = 'Password strength';
    let className = '';
    
    // Length check
    if (password.length >= 8) strength++;
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength++;
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength++;
    
    // Number check
    if (/[0-9]/.test(password)) strength++;
    
    // Special character check
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    switch(strength) {
        case 0:
        case 1:
            text = 'Weak';
            className = 'weak';
            break;
        case 2:
        case 3:
            text = 'Medium';
            className = 'medium';
            break;
        case 4:
        case 5:
            text = 'Strong';
            className = 'strong';
            break;
    }
    
    strengthBar.className = 'strength-fill ' + className;
    strengthText.textContent = text;
}

// Validate register form
function validateRegisterForm() {
    let isValid = true;
    const requiredFields = [
        'firstName', 'lastName', 'email', 'password', 'confirmPassword'
    ];
    
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!validateRegisterField(field)) {
            isValid = false;
        }
    });
    
    // Validate password match
    if (!validatePasswordMatch()) {
        isValid = false;
    }
    
    // Validate terms agreement
    if (!validateTermsAgreement()) {
        isValid = false;
    }
    
    return isValid;
}

// Validate individual register field
function validateRegisterField(field) {
    const value = field.value.trim();
    const fieldId = field.id;
    
    switch(fieldId) {
        case 'firstName':
        case 'lastName':
            if (!value) {
                showFieldError(field, 'This field is required');
                return false;
            }
            if (value.length < 2) {
                showFieldError(field, 'Must be at least 2 characters');
                return false;
            }
            break;
            
        case 'email':
            if (!value) {
                showFieldError(field, 'Email is required');
                return false;
            }
            if (!isValidEmail(value)) {
                showFieldError(field, 'Please enter a valid email address');
                return false;
            }
            break;
            
        case 'password':
            if (!value) {
                showFieldError(field, 'Password is required');
                return false;
            }
            if (value.length < 8) {
                showFieldError(field, 'Password must be at least 8 characters');
                return false;
            }
            break;
            
        case 'confirmPassword':
            if (!value) {
                showFieldError(field, 'Please confirm your password');
                return false;
            }
            break;
    }
    
    showFieldSuccess(field);
    return true;
}

// Validate password match
function validatePasswordMatch() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (!password || !confirmPassword) return true;
    
    if (password.value !== confirmPassword.value) {
        showFieldError(confirmPassword, 'Passwords do not match');
        return false;
    } else {
        showFieldSuccess(confirmPassword);
        return true;
    }
}

// Validate terms agreement
function validateTermsAgreement() {
    const agreeTerms = document.getElementById('agreeTerms');
    const termsFeedback = agreeTerms.parentNode.querySelector('.invalid-feedback');
    
    if (!agreeTerms.checked) {
        agreeTerms.classList.add('is-invalid');
        if (termsFeedback) {
            termsFeedback.style.display = 'block';
        }
        return false;
    } else {
        agreeTerms.classList.remove('is-invalid');
        if (termsFeedback) {
            termsFeedback.style.display = 'none';
        }
        return true;
    }
}

// Register user function
function registerUser() {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const company = document.getElementById('company').value;
    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;
    const businessType = document.getElementById('businessType').value;
    const newsletter = document.getElementById('newsletter').checked;
    
    const registerBtn = document.getElementById('registerBtn');
    const btnText = registerBtn.querySelector('.btn-text');
    const btnLoading = registerBtn.querySelector('.btn-loading');
    
    // Show loading state
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    registerBtn.disabled = true;
    
    // Simulate API call (replace with actual API call)
    setTimeout(() => {
        // This is a simulation - replace with actual registration
        if (email && password) {
            showAlert('Account created successfully! Redirecting to login...', 'success');
            
            // Redirect to login after successful registration
            setTimeout(() => {
                window.location.href = '/smart_core_erp/login';
            }, 2000);
            
        } else {
            showAlert('Registration failed. Please try again.');
            
            // Reset loading state
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            registerBtn.disabled = false;
        }
    }, 2000);
}

// Common functions (from previous implementation)
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.style.display = 'block';
    }
}

function showFieldSuccess(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.style.display = 'none';
    }
}

function showAlert(message, type = 'danger') {
    const messageAlert = document.getElementById('messageAlert');
    if (!messageAlert) return;
    
    messageAlert.textContent = message;
    messageAlert.className = `alert alert-${type}`;
    messageAlert.classList.remove('d-none');
    
    setTimeout(() => {
        messageAlert.classList.add('d-none');
    }, 5000);
}