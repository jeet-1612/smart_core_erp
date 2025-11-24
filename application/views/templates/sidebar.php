<?php 
$base_url = '/smart_core_erp/';
if (function_exists('base_url')) {
    $base_url = base_url();
} elseif (isset($this) && method_exists($this, 'config')) {
    $base_url = $this->config->item('base_url');
}
?>
<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-1">
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