<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/smart_core_erp/assets/css/auth.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- Left Side - Branding -->
            <div class="auth-branding">
                <div class="brand-content">
                    <a href="/smart_core_erp" class="brand-logo">
                        <i class="fas fa-brain"></i>
                        Smart-Core ERP
                    </a>
                    <h1>Join Smart-Core ERP</h1>
                    <p>Create your account and start managing your business more efficiently today</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <i class="fas fa-rocket"></i>
                            <span>Free 14-day trial</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure & reliable</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Setup in minutes</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-headset"></i>
                            <span>24/7 Support</span>
                        </div>
                    </div>

                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p>"Smart-Core ERP transformed our business operations. Easy to use and incredibly powerful!"</p>
                            <div class="testimonial-author">
                                <strong>Jeet</strong>
                                <span>CEO, TechSolutions Inc.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="auth-form-container">
                <div class="auth-form-wrapper">
                    <div class="form-header">
                        <h2>Create Account</h2>
                        <p>Fill in your details to get started</p>
                    </div>

                    <!-- Register Form -->
                    <form id="registerForm" class="auth-form">
                        <!-- Display Error/Success Messages -->
                        <div id="messageAlert" class="alert alert-danger d-none" role="alert"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName" class="form-label">
                                        <i class="fas fa-user"></i>
                                        First Name *
                                    </label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="John" required>
                                    <div class="invalid-feedback">Please enter your first name</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName" class="form-label">
                                        <i class="fas fa-user"></i>
                                        Last Name *
                                    </label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Doe" required>
                                    <div class="invalid-feedback">Please enter your last name</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address *
                            </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="john@company.com" required>
                            <div class="invalid-feedback">Please enter a valid email address</div>
                        </div>

                        <div class="form-group">
                            <label for="company" class="form-label">
                                <i class="fas fa-building"></i>
                                Company Name
                            </label>
                            <input type="text" class="form-control" id="company" name="company" placeholder="Your Company Name">
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 98765 43210">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock"></i>
                                        Password *
                                    </label>
                                    <div class="password-input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                        <button type="button" class="password-toggle" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Password must be at least 8 characters</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmPassword" class="form-label">
                                        <i class="fas fa-lock"></i>
                                        Confirm Password *
                                    </label>
                                    <div class="password-input-group">
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="••••••••" required>
                                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Passwords do not match</div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="password-strength mb-3">
                            <div class="strength-bar">
                                <div class="strength-fill" id="passwordStrength"></div>
                            </div>
                            <small class="strength-text" id="passwordText">Password strength</small>
                        </div>

                        <div class="form-group">
                            <label for="businessType" class="form-label">
                                <i class="fas fa-industry"></i>
                                Business Type
                            </label>
                            <select class="form-control" id="businessType" name="businessType">
                                <option value="">Select your business type</option>
                                <option value="retail">Retail</option>
                                <option value="manufacturing">Manufacturing</option>
                                <option value="wholesale">Wholesale</option>
                                <option value="services">Services</option>
                                <option value="ecommerce">E-commerce</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a> *
                                </label>
                                <div class="invalid-feedback">You must agree to the terms and conditions</div>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                <label class="form-check-label" for="newsletter">
                                    Send me product updates and tips
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-auth" id="registerBtn">
                            <span class="btn-text">Create Account</span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                                Creating Account...
                            </span>
                        </button>

                        <div class="auth-divider">
                            <span>Or sign up with</span>
                        </div>

                        <div class="social-login">
                            <button type="button" class="btn btn-google">
                                <i class="fab fa-google"></i>
                                Google
                            </button>
                            <button type="button" class="btn btn-microsoft">
                                <i class="fab fa-microsoft"></i>
                                Microsoft
                            </button>
                        </div>

                        <div class="auth-footer">
                            <p>Already have an account? 
                                <a href="/smart_core_erp/login" class="auth-link">Sign in here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/smart_core_erp/assets/js/auth.js"></script>
</body>
</html>