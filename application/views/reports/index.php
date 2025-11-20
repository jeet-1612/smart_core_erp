<style>
.stat-card {
    border-left: 4px solid;
    border-radius: 8px;
}

.stat-sales {
    border-left-color: #28a745;
}

.stat-inventory {
    border-left-color: #007bff;
}

.stat-financial {
    border-left-color: #ffc107;
}

.stat-receivable {
    border-left-color: #dc3545;
}

.report-card {
    transition: transform 0.2s;
}

.report-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reports Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-sales">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Sales</h6>
                        <h4 class="stat-number text-success">
                            ₹<?php echo number_format($sales_summary['total_sales'], 2); ?></h4>
                        <small class="text-muted">
                            <?php if ($sales_summary['sales_growth'] >= 0): ?>
                            <span class="text-success">↑
                                <?php echo number_format($sales_summary['sales_growth'], 1); ?>%</span>
                            <?php else: ?>
                            <span class="text-danger">↓
                                <?php echo number_format(abs($sales_summary['sales_growth']), 1); ?>%</span>
                            <?php endif; ?>
                            from last month
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-inventory">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Inventory Value</h6>
                        <h4 class="stat-number text-primary">
                            ₹<?php echo number_format($inventory_summary['total_stock_value'], 2); ?>
                        </h4>
                        <small class="text-muted">
                            <?php echo $inventory_summary['total_products']; ?> products
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-financial">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Monthly Revenue</h6>
                        <h4 class="stat-number text-warning">
                            ₹<?php echo number_format($financial_summary['current_month_revenue'], 2); ?>
                        </h4>
                        <small class="text-muted">
                            <?php if ($financial_summary['revenue_growth'] >= 0): ?>
                            <span class="text-success">↑
                                <?php echo number_format($financial_summary['revenue_growth'], 1); ?>%</span>
                            <?php else: ?>
                            <span class="text-danger">↓
                                <?php echo number_format(abs($financial_summary['revenue_growth']), 1); ?>%</span>
                            <?php endif; ?>
                            growth
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-receivable">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Receivables</h6>
                        <h4 class="stat-number text-danger">
                            ₹<?php echo number_format($financial_summary['accounts_receivable'], 2); ?>
                        </h4>
                        <small class="text-muted">Outstanding invoices</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hand-holding-usd fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Report Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Sales Reports</h5>
                <p class="card-text text-muted">Daily sales, customer-wise, product-wise, and tax
                    reports</p>
                <a href="/smart_core_erp/reports/sales_reports" class="btn btn-primary">View Reports</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                <h5 class="card-title">Inventory Reports</h5>
                <p class="card-text text-muted">Stock summary, low stock alerts, movements, and
                    valuation</p>
                <a href="/smart_core_erp/reports/inventory_reports" class="btn btn-success">View
                    Reports</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-chart-pie fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Financial Reports</h5>
                <p class="card-text text-muted">P&L, balance sheet, cash flow, and accounts receivable
                </p>
                <a href="/smart_core_erp/reports/financial_reports" class="btn btn-warning">View
                    Reports</a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Sales Trend - Last 6 Months</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Inventory Status</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="inventoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Alerts -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Alerts</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php if ($inventory_summary['low_stock_count'] > 0): ?>
                    <a href="/smart_core_erp/reports/inventory_reports?report_type=low_stock"
                        class="list-group-item list-group-item-warning">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <?php echo $inventory_summary['low_stock_count']; ?> products are low on stock
                    </a>
                    <?php endif; ?>

                    <?php if ($inventory_summary['out_of_stock_count'] > 0): ?>
                    <a href="/smart_core_erp/reports/inventory_reports?report_type=low_stock"
                        class="list-group-item list-group-item-danger">
                        <i class="fas fa-times-circle text-danger"></i>
                        <?php echo $inventory_summary['out_of_stock_count']; ?> products are out of
                        stock
                    </a>
                    <?php endif; ?>

                    <?php if ($financial_summary['accounts_receivable'] > 0): ?>
                    <a href="/smart_core_erp/reports/financial_reports?report_type=accounts_receivable"
                        class="list-group-item list-group-item-info">
                        <i class="fas fa-clock text-info"></i>
                        ₹<?php echo number_format($financial_summary['accounts_receivable'], 2); ?> in
                        outstanding invoices
                    </a>
                    <?php endif; ?>

                    <?php if ($sales_summary['total_invoices'] == 0): ?>
                    <div class="list-group-item list-group-item-light">
                        <i class="fas fa-info-circle text-secondary"></i>
                        No sales recorded for current month
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
// Sample Chart Data - In real application, you would fetch this via AJAX
const salesChart = new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Sales (₹)',
            data: [120000, 150000, 180000, 140000, 200000, 220000],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

const inventoryChart = new Chart(document.getElementById('inventoryChart'), {
    type: 'doughnut',
    data: {
        labels: ['In Stock', 'Low Stock', 'Out of Stock'],
        datasets: [{
            data: [
                <?php echo $inventory_summary['total_products'] - $inventory_summary['low_stock_count'] - $inventory_summary['out_of_stock_count']; ?>,
                <?php echo $inventory_summary['low_stock_count']; ?>,
                <?php echo $inventory_summary['out_of_stock_count']; ?>
            ],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>