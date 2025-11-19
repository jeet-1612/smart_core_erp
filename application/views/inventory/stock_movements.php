<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Stock Movements</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-download"></i> Export
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/inventory/stock_movements">
            <div class="row">
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Product</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">All Products</option>
                        <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product->id; ?>"
                            <?php echo $this->input->get('product_id') == $product->id ? 'selected' : ''; ?>>
                            <?php echo $product->product_code . ' - ' . $product->product_name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="movement_type" class="form-label">Type</label>
                    <select class="form-select" id="movement_type" name="movement_type">
                        <option value="">All Types</option>
                        <option value="in" <?php echo $this->input->get('movement_type') == 'in' ? 'selected' : ''; ?>>
                            Stock In</option>
                        <option value="out"
                            <?php echo $this->input->get('movement_type') == 'out' ? 'selected' : ''; ?>>Stock Out
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?php echo $this->input->get('start_date'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $this->input->get('end_date'); ?>">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stock Movements Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Stock Movement History</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($movements)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Reference</th>
                        <th>Adjusted By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movements as $movement): ?>
                    <tr>
                        <td><?php echo date('M d, Y H:i', strtotime($movement->created_at)); ?></td>
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
                        <td>
                            <strong><?php echo $movement->quantity; ?></strong>
                        </td>
                        <td><?php echo $movement->reason; ?></td>
                        <td><?php echo $movement->reference ?: 'N/A'; ?></td>
                        <td><?php echo $movement->first_name . ' ' . $movement->last_name; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-history fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Stock Movements Found</h5>
            <p class="text-muted">No stock movements match your filter criteria.</p>
        </div>
        <?php endif; ?>
    </div>
</div>