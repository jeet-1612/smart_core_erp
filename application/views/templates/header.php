<?php 
$base_url = '/smart_core_erp/';
if (function_exists('base_url')) {
    $base_url = base_url();
} elseif (isset($this) && method_exists($this, 'config')) {
    $base_url = $this->config->item('base_url');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($meta_description) ? $meta_description : 'Smart-Core ERP - Complete Business Management Solution'; ?>">
    <meta name="keywords" content="<?php echo isset($meta_keywords) ? $meta_keywords : 'ERP, Business Management, Accounting, Sales, Purchase, Inventory'; ?>">
    <meta name="author" content="Smart-Core ERP">
    <title><?php echo isset($title) ? $title . ' - Smart-Core ERP' : 'Smart-Core ERP'; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo $base_url; ?>assets/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/dashboard.css">
    
    <!-- Additional page-specific CSS -->
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $base_url . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline CSS -->
    <?php if(isset($inline_css)): ?>
        <style>
            <?php echo $inline_css; ?>
        </style>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $base_url; ?>dashboard">
                <i class="fas fa-brain"></i>
                Smart-Core ERP
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'clients') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>clients">
                            <i class="fas fa-users"></i>
                            Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'vendors') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>vendors">
                            <i class="fas fa-truck"></i>
                            Vendors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'sales') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>sales">
                            <i class="fas fa-shopping-cart"></i>
                            Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'purchase') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>purchase">
                            <i class="fas fa-shopping-bag"></i>
                            Purchase
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'accounts') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>accounts">
                            <i class="fas fa-chart-bar"></i>
                            Accounts
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <?php echo isset($user['name']) ? $user['name'] : 'User'; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>profile"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_url; ?>settings"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $base_url; ?>auth/logout">
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
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'clients') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>clients">
                                <i class="fas fa-users"></i>
                                Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'vendors') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>vendors">
                                <i class="fas fa-truck"></i>
                                Vendors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'sales') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>sales">
                                <i class="fas fa-shopping-cart"></i>
                                Sales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'purchase') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>purchase">
                                <i class="fas fa-shopping-bag"></i>
                                Purchase
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'accounts') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>accounts">
                                <i class="fas fa-chart-bar"></i>
                                Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'inventory') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>inventory">
                                <i class="fas fa-box"></i>
                                Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'invoices') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>invoices">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'reports') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>reports">
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
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'users') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>admin/users">
                                <i class="fas fa-users-cog"></i>
                                User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($this->uri) && $this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'settings') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>admin/settings">
                                <i class="fas fa-cogs"></i>
                                System Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Breadcrumb -->
                <?php if(isset($breadcrumb) && !empty($breadcrumb)): ?>
                <nav aria-label="breadcrumb" class="mt-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                        <?php foreach($breadcrumb as $item): ?>
                            <?php if(isset($item['url'])): ?>
                                <li class="breadcrumb-item"><a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a></li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $item['title']; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>
                
                <!-- Flash Messages -->
                <?php if(isset($this->session) && $this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($this->session) && $this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($this->session) && $this->session->flashdata('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('warning'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($this->session) && $this->session->flashdata('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i> <?php echo $this->session->flashdata('info'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>