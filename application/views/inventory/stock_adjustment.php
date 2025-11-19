<style>
.product-info {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Stock Adjustment</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/inventory/stock_movements" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-history"></i> View History
        </a>
    </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $this->session->flashdata('success'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stock Adjustment Form -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Adjust Stock Level</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="/smart_core_erp/inventory/process_stock_adjustment" id="stockAdjustmentForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product *</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="">Choose Product...</option>
                            <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product->id; ?>"
                                data-stock="<?php echo $product->current_stock; ?>"
                                data-unit="<?php echo $product->unit_of_measure; ?>">
                                <?php echo $product->product_code . ' - ' . $product->product_name; ?>
                                (Stock: <?php echo $product->current_stock; ?>
                                <?php echo $product->unit_of_measure; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type *</label>
                        <select class="form-select" id="adjustment_type" name="adjustment_type" required>
                            <option value="in">Stock In (Add)</option>
                            <option value="out">Stock Out (Remove)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div id="productInfo" class="product-info d-none">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Current Stock:</strong>
                        <span id="currentStock">0</span>
                        <span id="stockUnit"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>New Stock After Adjustment:</strong>
                        <span id="newStock">0</span>
                        <span id="newStockUnit"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Status:</strong>
                        <span id="stockStatus" class="badge"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity *</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" step="1"
                            required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="reference" class="form-label">Reference</label>
                        <input type="text" class="form-control" id="reference" name="reference"
                            placeholder="Optional reference number">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Adjustment *</label>
                <textarea class="form-control" id="reason" name="reason" rows="3"
                    placeholder="Explain why this adjustment is necessary" required></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Process Adjustment</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const adjustmentType = document.getElementById('adjustment_type');
    const quantityInput = document.getElementById('quantity');
    const productInfo = document.getElementById('productInfo');
    const currentStockSpan = document.getElementById('currentStock');
    const stockUnitSpan = document.getElementById('stockUnit');
    const newStockSpan = document.getElementById('newStock');
    const newStockUnitSpan = document.getElementById('newStockUnit');
    const stockStatusSpan = document.getElementById('stockStatus');
    const submitBtn = document.getElementById('submitBtn');

    function updateStockInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const currentStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const unit = selectedOption.getAttribute('data-unit') || 'PCS';
        const quantity = parseInt(quantityInput.value) || 0;
        const isStockIn = adjustmentType.value === 'in';

        if (productSelect.value) {
            productInfo.classList.remove('d-none');
            currentStockSpan.textContent = currentStock;
            stockUnitSpan.textContent = unit;
            newStockUnitSpan.textContent = unit;

            let newStock;
            if (isStockIn) {
                newStock = currentStock + quantity;
            } else {
                newStock = currentStock - quantity;
            }

            newStockSpan.textContent = newStock;

            // Update status badge
            if (newStock <= 0) {
                stockStatusSpan.className = 'badge bg-danger';
                stockStatusSpan.textContent = 'Out of Stock';
            } else if (newStock <= 10) { // Assuming reorder level is 10
                stockStatusSpan.className = 'badge bg-warning';
                stockStatusSpan.textContent = 'Low Stock';
            } else {
                stockStatusSpan.className = 'badge bg-success';
                stockStatusSpan.textContent = 'In Stock';
            }

            // Validate stock out
            if (!isStockIn && quantity > currentStock) {
                submitBtn.disabled = true;
                stockStatusSpan.className = 'badge bg-danger';
                stockStatusSpan.textContent = 'Insufficient Stock';
            } else {
                submitBtn.disabled = false;
            }
        } else {
            productInfo.classList.add('d-none');
            submitBtn.disabled = true;
        }
    }

    productSelect.addEventListener('change', updateStockInfo);
    adjustmentType.addEventListener('change', updateStockInfo);
    quantityInput.addEventListener('input', updateStockInfo);

    // Form submission validation
    document.getElementById('stockAdjustmentForm').addEventListener('submit', function(e) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const currentStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const isStockIn = adjustmentType.value === 'in';

        if (!isStockIn && quantity > currentStock) {
            e.preventDefault();
            alert('Error: Cannot remove more stock than available. Current stock: ' + currentStock);
            return false;
        }

        if (!confirm('Are you sure you want to process this stock adjustment?')) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    });
});
</script>