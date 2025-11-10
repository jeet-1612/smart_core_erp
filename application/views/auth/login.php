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
                    <h1>Welcome Back</h1>
                    <p>Sign in to your account to continue managing your business efficiently</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Manage Sales & Purchases</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Track Inventory</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Generate Reports</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Handle Accounting</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="auth-form-container">
                <div class="auth-form-wrapper">
                    <div class="form-header">
                        <h2>Sign In</h2>
                        <p>Enter your credentials to access your account</p>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm" class="auth-form">
                        <!-- Display Error/Success Messages -->
                        <div id="messageAlert" class="alert alert-danger d-none" role="alert"></div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            <div class="invalid-feedback">Please enter a valid email address</div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Please enter your password</div>
                        </div>

                        <div class="form-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="/smart_core_erp/forgot-password" class="forgot-password">
                                Forgot Password?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-auth" id="loginBtn">
                            <span class="btn-text">Sign In</span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                                Signing In...
                            </span>
                        </button>

                        <div class="auth-divider">
                            <span>Or continue with</span>
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
                            <p>Don't have an account? 
                                <a href="/smart_core_erp/register" class="auth-link">Create an account</a>
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