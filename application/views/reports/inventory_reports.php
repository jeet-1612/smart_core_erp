<style>
.report-header {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.low-stock {
    background-color: #fff3cd;
}

.out-of-stock {
    background-color: #f8d7da;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .075);
}

.stock-status {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
}

.status-in-stock {
    background-color: #28a745;
}

.status-low-stock {
    background-color: #ffc107;
}

.status-out-of-stock {
    background-color: #dc3545;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Reports</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="export-buttons">
            <a href="/smart_core_erp/reports/export_report?report_type=inventory_<?php echo $report_type; ?>&format=pdf"
                class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="/smart_core_erp/reports/export_report?report_type=inventory_<?php echo $report_type; ?>&format=excel"
                class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="/smart_core_erp/reports/print_report?report_type=inventory_<?php echo $report_type; ?>"
                class="btn btn-sm btn-primary" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
</div>

<!-- Report Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/reports/inventory_reports">
            <div class="row">
                <div class="col-md-4">
                    <label for="report_type" class="form-label">Report Type</label>
                    <select class="form-select" id="report_type" name="report_type" onchange="this.form.submit()">
                        <option value="stock_summary" <?php echo $report_type == 'stock_summary' ? 'selected' : ''; ?>>
                            Stock
                            Summary</option>
                        <option value="low_stock" <?php echo $report_type == 'low_stock' ? 'selected' : ''; ?>>Low Stock
                            Alert
                        </option>
                        <option value="stock_movements"
                            <?php echo $report_type == 'stock_movements' ? 'selected' : ''; ?>>Stock
                            Movements</option>
                        <option value="stock_valuation"
                            <?php echo $report_type == 'stock_valuation' ? 'selected' : ''; ?>>Stock
                            Valuation</option>
                    </select>
                </div>
                <?php if ($report_type == 'stock_summary'): ?>
                <div class="col-md-4">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">All Categories</option>
                        <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>"
                            <?php echo $category_id == $category->id ? 'selected' : ''; ?>>
                            <?php echo $category->category_name; ?>
                        </option>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <option value="1">General</option>
                        <?php endif; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Header -->
<div class="report-header">
    <div class="row">
        <div class="col-md-6">
            <h4>
                <?php
                                switch($report_type) {
                                    case 'stock_summary': echo 'Stock Summary Report'; break;
                                    case 'low_stock': echo 'Low Stock Alert Report'; break;
                                    case 'stock_movements': echo 'Stock Movements Report'; break;
                                    case 'stock_valuation': echo 'Stock Valuation Report'; break;
                                }
                                ?>
            </h4>
            <p class="text-muted mb-0">Generated: <?php echo date('F d, Y g:i A'); ?></p>
        </div>
        <div class="col-md-6 text-end">
            <p class="mb-0">Report Type: <?php echo ucfirst(str_replace('_', ' ', $report_type)); ?>
                Report</p>
        </div>
    </div>
</div>

<!-- Report Content -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($report_data)): ?>

        <?php if ($report_type == 'stock_summary'): ?>
        <!-- Stock Summary Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-end">Current Stock</th>
                        <th class="text-end">Reorder Level</th>
                        <th class="text-end">Cost Price</th>
                        <th class="text-end">Selling Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data as $product): ?>
                    <tr class="<?php 
                                                echo $product->current_stock == 0 ? 'out-of-stock' : 
                                                     ($product->current_stock <= $product->reorder_level ? 'low-stock' : ''); 
                                            ?>">
                        <td>
                            <strong><?php echo $product->product_name; ?></strong>
                            <?php if ($product->product_code): ?>
                            <br><small class="text-muted"><?php echo $product->product_code; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->category_name ?: 'N/A'; ?></td>
                        <td class="text-end"><?php echo $product->current_stock; ?>
                            <?php echo $product->unit_of_measure; ?></td>
                        <td class="text-end"><?php echo $product->reorder_level; ?>
                            <?php echo $product->unit_of_measure; ?></td>
                        <td class="text-end">₹<?php echo number_format($product->cost_price, 2); ?></td>
                        <td class="text-end">₹<?php echo number_format($product->selling_price, 2); ?>
                        </td>
                        <td>
                            <?php if ($product->current_stock == 0): ?>
                            <span class="stock-status status-out-of-stock"></span>
                            <span class="text-danger">Out of Stock</span>
                            <?php elseif ($product->current_stock <= $product->reorder_level): ?>
                            <span class="stock-status status-low-stock"></span>
                            <span class="text-warning">Low Stock</span>
                            <?php else: ?>
                            <span class="stock-status status-in-stock"></span>
                            <span class="text-success">In Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php elseif ($report_type == 'low_stock'): ?>
        <!-- Low Stock Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-end">Current Stock</th>
                        <th class="text-end">Reorder Level</th>
                        <th class="text-end">Required Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data as $product): ?>
                    <tr class="<?php echo $product->current_stock == 0 ? 'out-of-stock' : 'low-stock'; ?>">
                        <td>
                            <strong><?php echo $product->product_name; ?></strong>
                            <?php if ($product->product_code): ?>
                            <br><small class="text-muted"><?php echo $product->product_code; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->category_name ?: 'N/A'; ?></td>
                        <td class="text-end"><?php echo $product->current_stock; ?>
                            <?php echo $product->unit_of_measure; ?></td>
                        <td class="text-end"><?php echo $product->reorder_level; ?>
                            <?php echo $product->unit_of_measure; ?></td>
                        <td class="text-end">
                            <?php 
                                                    $required = $product->reorder_level - $product->current_stock;
                                                    echo $required > 0 ? $required : 0; 
                                                    ?> <?php echo $product->unit_of_measure; ?>
                        </td>
                        <td>
                            <?php if ($product->current_stock == 0): ?>
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

        <?php elseif ($report_type == 'stock_movements'): ?>
        <!-- Stock Movements Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th class="text-end">Quantity</th>
                        <th>Reason</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data as $movement): ?>
                    <tr>
                        <td><?php echo date('M d, Y H:i', strtotime($movement->created_at)); ?></td>
                        <td>
                            <strong><?php echo $movement->product_name; ?></strong>
                            <?php if ($movement->product_code): ?>
                            <br><small class="text-muted"><?php echo $movement->product_code; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($movement->adjustment_type == 'in'): ?>
                            <span class="badge bg-success">STOCK IN</span>
                            <?php else: ?>
                            <span class="badge bg-danger">STOCK OUT</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end"><?php echo $movement->quantity; ?></td>
                        <td><?php echo $movement->reason; ?></td>
                        <td><?php echo $movement->reference ?: 'N/A'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php elseif ($report_type == 'stock_valuation'): ?>
        <!-- Stock Valuation Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-end">Current Stock</th>
                        <th class="text-end">Cost Price</th>
                        <th class="text-end">Stock Value</th>
                        <th class="text-end">Selling Price</th>
                        <th class="text-end">Potential Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                            $total_stock_value = 0;
                                            $total_potential_value = 0;
                                            ?>
                    <?php foreach ($report_data as $product): ?>
                    <tr>
                        <td>
                            <strong><?php echo $product->product_name; ?></strong>
                            <?php if ($product->product_code): ?>
                            <br><small class="text-muted"><?php echo $product->product_code; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->category_name ?: 'N/A'; ?></td>
                        <td class="text-end"><?php echo $product->current_stock; ?>
                            <?php echo $product->unit_of_measure; ?></td>
                        <td class="text-end">₹<?php echo number_format($product->cost_price, 2); ?></td>
                        <td class="text-end">
                            <strong>₹<?php echo number_format($product->stock_value, 2); ?></strong>
                        </td>
                        <td class="text-end">₹<?php echo number_format($product->selling_price, 2); ?>
                        </td>
                        <td class="text-end">
                            ₹<?php echo number_format($product->current_stock * $product->selling_price, 2); ?>
                        </td>
                    </tr>
                    <?php 
                                                $total_stock_value += $product->stock_value;
                                                $total_potential_value += ($product->current_stock * $product->selling_price);
                                            ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4">Total</th>
                        <th class="text-end">₹<?php echo number_format($total_stock_value, 2); ?></th>
                        <th></th>
                        <th class="text-end">₹<?php echo number_format($total_potential_value, 2); ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php endif; ?>

        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Data Available</h5>
            <p class="text-muted">No inventory data found for the selected criteria.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Statistics -->
<?php if (!empty($report_data)): ?>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h6 class="card-title">Total Items</h6>
                <h4 class="card-text"><?php echo count($report_data); ?></h4>
            </div>
        </div>
    </div>
    <?php if ($report_type == 'stock_valuation'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h6 class="card-title">Total Stock Value</h6>
                <h4 class="card-text">₹<?php echo number_format($total_stock_value, 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h6 class="card-title">Potential Value</h6>
                <h4 class="card-text">₹<?php echo number_format($total_potential_value, 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <h6 class="card-title">Profit Potential</h6>
                <h4 class="card-text">
                    ₹<?php echo number_format($total_potential_value - $total_stock_value, 2); ?></h4>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>