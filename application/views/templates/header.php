<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/smart_core_erp/assets/css/dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/smart_core_erp/dashboard">
                <i class="fas fa-brain"></i>
                Smart-Core ERP
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/smart_core_erp/dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/smart_core_erp/clients">
                            <i class="fas fa-users"></i>
                            Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/smart_core_erp/vendors">
                            <i class="fas fa-truck"></i>
                            Vendors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/smart_core_erp/sales">
                            <i class="fas fa-shopping-cart"></i>
                            Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/smart_core_erp/purchase">
                            <i class="fas fa-shopping-bag"></i>
                            Purchase
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/smart_core_erp/accounts">
                            <i class="fas fa-chart-bar"></i>
                            Accounts
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <?php echo $user['name']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/smart_core_erp/logout">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="/smart_core_erp/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/smart_core_erp/clients">
                                <i class="fas fa-users"></i>
                                Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/smart_core_erp/vendors">
                                <i class="fas fa-truck"></i>
                                Vendors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/smart_core_erp/sales">
                                <i class="fas fa-shopping-cart"></i>
                                Sales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/smart_core_erp/purchase">
                                <i class="fas fa-shopping-bag"></i>
                                Purchase
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/smart_core_erp/accounts">
                                <i class="fas fa-chart-bar"></i>
                                Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-box"></i>
                                Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-line"></i>
                                Reports
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Administration</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users-cog"></i>
                                User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cogs"></i>
                                System Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">