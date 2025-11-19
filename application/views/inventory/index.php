<style>
.stat-card {
    border-left: 4px solid;
    border-radius: 8px;
}

.stat-products {
    border-left-color: #28a745;
}

.stat-categories {
    border-left-color: #007bff;
}

.stat-value {
    border-left-color: #ffc107;
}

.stat-low {
    border-left-color: #fd7e14;
}

.stat-out {
    border-left-color: #dc3545;
}

.low-stock {
    background-color: #fff3cd;
}

.out-of-stock {
    background-color: #f8d7da;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
        <div class="card stat-card stat-products">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Products</h6>
                        <h4 class="stat-number text-success"><?php echo $inventory_summary['total_products']; ?>
                        </h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x text-success"></i>
                    </div>
                </div>
                <small class="text-muted">Active products</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
        <div class="card stat-card stat-categories">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Categories</h6>
                        <h4 class="stat-number text-primary"><?php echo $inventory_summary['total_categories']; ?>
                        </h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tags fa-2x text-primary"></i>
                    </div>
                </div>
                <small class="text-muted">Product categories</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
        <div class="card stat-card stat-value">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Stock Value</h6>
                        <h4 class="stat-number text-warning">
                            ₹<?php echo number_format($inventory_summary['total_stock_value'], 2); ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                    </div>
                </div>
                <small class="text-muted">Total inventory value</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
        <div class="card stat-card stat-low">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Low Stock</h6>
                        <h4 class="stat-number text-warning"><?php echo $inventory_summary['low_stock_count']; ?>
                        </h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                </div>
                <small class="text-muted">Below reorder level</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
        <div class="card stat-card stat-out">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Out of Stock</h6>
                        <h4 class="stat-number text-danger"><?php echo $inventory_summary['out_of_stock_count']; ?>
                        </h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
                <small class="text-muted">Zero stock items</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/inventory/products" class="btn btn-primary w-100">
                            <i class="fas fa-box"></i> Manage Products
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/inventory/categories" class="btn btn-success w-100">
                            <i class="fas fa-tags"></i> Categories
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/inventory/stock_adjustment" class="btn btn-warning w-100">
                            <i class="fas fa-exchange-alt"></i> Stock Adjustment
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/inventory/stock_movements" class="btn btn-info w-100">
                            <i class="fas fa-history"></i> Stock Movements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Low Stock Items -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Low Stock Items</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($low_stock_items)): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($low_stock_items as $item): ?>
                            <tr class="<?php echo $item->current_stock == 0 ? 'out-of-stock' : 'low-stock'; ?>">
                                <td>
                                    <strong><?php echo $item->product_code; ?></strong><br>
                                    <small><?php echo $item->product_name; ?></small>
                                </td>
                                <td><?php echo $item->current_stock; ?> <?php echo $item->unit_of_measure; ?></td>
                                <td><?php echo $item->reorder_level; ?> <?php echo $item->unit_of_measure; ?></td>
                                <td>
                                    <?php if ($item->current_stock == 0): ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                    <?php else: ?>
                                    <span class="badge bg-warning">Low Stock</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">No low stock items found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Stock Movements -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Stock Movements</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_movements)): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_movements as $movement): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $movement->product_code; ?></strong><br>
                                    <small><?php echo $movement->product_name; ?></small>
                                </td>
                                <td>
                                    <?php if ($movement->adjustment_type == 'in'): ?>
                                    <span class="badge bg-success">IN</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger">OUT</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $movement->quantity; ?></td>
                                <td><?php echo date('M d, H:i', strtotime($movement->created_at)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">No recent stock movements.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>