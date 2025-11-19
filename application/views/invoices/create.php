<style>
.invoice-item {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
}

.invoice-item:last-child {
    margin-bottom: 0;
}

.remove-item {
    color: #dc3545;
    cursor: pointer;
}

.remove-item:hover {
    color: #bd2130;
}

.total-section {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 15px;
}

.customer-section {
    background-color: #e8f4fd;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
}
</style>

<div class="row">
    <!-- Page Header -->
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create Invoice</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/smart_core_erp/invoices" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Invoices
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Invoice Form -->
    <form method="POST" action="/smart_core_erp/invoices/process_invoice" id="invoiceForm">
        <div class="row">
            <div class="col-md-8">
                <!-- Customer Information -->
                <div class="customer-section">
                    <h5 class="card-title mb-3">Customer Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Customer *</label>
                                <select class="form-select" id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo $customer->id; ?>">
                                        <?php echo $customer->customer_name; ?>
                                        <?php if ($customer->contact_person): ?>
                                        (<?php echo $customer->contact_person; ?>)
                                        <?php endif; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date *</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date *</label>
                                <input type="date" class="form-control" id="due_date" name="due_date"
                                    value="<?php echo date('Y-m-d', strtotime('+15 days')); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reference" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference" name="reference"
                                    placeholder="Optional reference number">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Invoice Items</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="invoiceItems">
                            <!-- Items will be added here dynamically -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="text-center text-muted py-4">
                            <i class="fas fa-receipt fa-3x mb-3"></i>
                            <p>No items added yet. Click "Add Item" to start.</p>
                        </div>
                    </div>
                </div>

                <!-- Notes and Terms -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes & Terms</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_notes" class="form-label">Customer Notes</label>
                            <textarea class="form-control" id="customer_notes" name="customer_notes" rows="3"
                                placeholder="Notes for the customer (optional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                            <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="3"
                                placeholder="Terms and conditions (optional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Totals and Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Invoice Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="total-section mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal" class="fw-bold">₹0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax Amount:</span>
                                <span id="taxAmount" class="fw-bold">₹0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span><strong>Total Amount:</strong></span>
                                <span id="totalAmount" class="fw-bold fs-5 text-success">₹0.00</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                <i class="fas fa-save"></i> Save Invoice
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Validation Messages -->
                <div id="validationMessages" class="mt-3"></div>
            </div>
        </div>
    </form>
</div>

<!-- Item Template (Hidden) -->
<div id="itemTemplate" class="d-none">
    <div class="invoice-item">
        <div class="row">
            <div class="col-md-5">
                <div class="mb-3">
                    <label class="form-label">Product/Service *</label>
                    <select class="form-select product-select" name="items[INDEX][product_id]" required
                        onchange="updateProductDetails(this)">
                        <option value="">Select Product</option>
                        <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product->id; ?>" data-price="<?php echo $product->selling_price; ?>"
                            data-description="<?php echo $product->description; ?>">
                            <?php echo $product->product_name; ?>
                            (₹<?php echo number_format($product->selling_price, 2); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Qty *</label>
                    <input type="number" class="form-control quantity" name="items[INDEX][quantity]" step="0.01"
                        min="0.01" value="1" required onchange="calculateLineTotal(this)">
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Unit Price *</label>
                    <input type="number" class="form-control unit-price" name="items[INDEX][unit_price]" step="0.01"
                        min="0" required onchange="calculateLineTotal(this)">
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Tax Rate %</label>
                    <select class="form-select tax-rate" name="items[INDEX][tax_rate]"
                        onchange="calculateLineTotal(this)">
                        <option value="0">0%</option>
                        <?php foreach ($tax_rates as $tax): ?>
                        <option value="<?php echo $tax->tax_rate; ?>"><?php echo $tax->tax_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="remove-item text-center" title="Remove item">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control description" name="items[INDEX][item_description]"
                        placeholder="Item description">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="text-end">
                    <strong>Line Total: ₹<span class="line-total">0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
let itemCount = 0;

// Initialize with one empty item
document.addEventListener('DOMContentLoaded', function() {
    addItem();
});

// Add new item
document.getElementById('addItemBtn').addEventListener('click', function() {
    addItem();
});

function addItem() {
    const template = document.getElementById('itemTemplate').innerHTML;
    const newItem = template.replace(/INDEX/g, itemCount);

    const invoiceItems = document.getElementById('invoiceItems');
    const emptyState = document.getElementById('emptyState');

    invoiceItems.insertAdjacentHTML('beforeend', newItem);
    emptyState.style.display = 'none';

    // Add event listeners to new item
    const newItemElement = invoiceItems.lastElementChild;
    const removeBtn = newItemElement.querySelector('.remove-item');

    removeBtn.addEventListener('click', function() {
        newItemElement.remove();
        calculateTotals();
        if (invoiceItems.children.length === 0) {
            emptyState.style.display = 'block';
        }
    });

    // Auto-focus on product select
    newItemElement.querySelector('.product-select').focus();

    itemCount++;
    calculateTotals();
}

// Update product details when product is selected
function updateProductDetails(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const itemElement = selectElement.closest('.invoice-item');
    const unitPriceInput = itemElement.querySelector('.unit-price');
    const descriptionInput = itemElement.querySelector('.description');

    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        const description = selectedOption.getAttribute('data-description');

        unitPriceInput.value = price;
        descriptionInput.value = description || '';

        calculateLineTotal(selectElement);
    }
}

// Calculate line total for an item
function calculateLineTotal(inputElement) {
    const itemElement = inputElement.closest('.invoice-item');
    const quantity = parseFloat(itemElement.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(itemElement.querySelector('.unit-price').value) || 0;
    const taxRate = parseFloat(itemElement.querySelector('.tax-rate').value) || 0;
    const lineTotalSpan = itemElement.querySelector('.line-total');

    const subtotal = quantity * unitPrice;
    const taxAmount = subtotal * (taxRate / 100);
    const lineTotal = subtotal + taxAmount;

    lineTotalSpan.textContent = lineTotal.toFixed(2);
    calculateTotals();
}

// Calculate totals for the entire invoice
function calculateTotals() {
    let subtotal = 0;
    let taxAmount = 0;

    document.querySelectorAll('.invoice-item').forEach(function(item) {
        const quantity = parseFloat(item.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(item.querySelector('.unit-price').value) || 0;
        const taxRate = parseFloat(item.querySelector('.tax-rate').value) || 0;

        const itemSubtotal = quantity * unitPrice;
        const itemTax = itemSubtotal * (taxRate / 100);

        subtotal += itemSubtotal;
        taxAmount += itemTax;
    });

    const totalAmount = subtotal + taxAmount;

    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('taxAmount').textContent = '₹' + taxAmount.toFixed(2);
    document.getElementById('totalAmount').textContent = '₹' + totalAmount.toFixed(2);

    // Update validation
    validateForm();
}

// Validate form
function validateForm() {
    const submitBtn = document.getElementById('submitBtn');
    const validationMessages = document.getElementById('validationMessages');
    const items = document.querySelectorAll('.invoice-item');
    const customerId = document.getElementById('customer_id').value;

    let messages = [];

    // Check if customer is selected
    if (!customerId) {
        messages.push('<div class="alert alert-warning">Please select a customer.</div>');
    }

    // Check if there are items
    if (items.length === 0) {
        messages.push('<div class="alert alert-warning">Add at least one invoice item.</div>');
    }

    // Check if all items have valid data
    let validItems = true;
    document.querySelectorAll('.invoice-item').forEach(function(item) {
        const productId = item.querySelector('.product-select').value;
        const quantity = parseFloat(item.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(item.querySelector('.unit-price').value) || 0;

        if (!productId || quantity <= 0 || unitPrice <= 0) {
            validItems = false;
        }
    });

    if (!validItems) {
        messages.push(
            '<div class="alert alert-warning">All items must have a product, valid quantity, and unit price.</div>'
        );
    }

    // Update validation messages
    validationMessages.innerHTML = messages.join('');

    // Enable/disable submit button
    submitBtn.disabled = messages.length > 0 || items.length === 0;
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        document.getElementById('invoiceForm').reset();
        document.getElementById('invoiceItems').innerHTML = '';
        document.getElementById('emptyState').style.display = 'block';
        itemCount = 0;
        addItem();
        calculateTotals();
    }
}

// Form submission validation
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('.invoice-item');

    if (items.length === 0) {
        e.preventDefault();
        alert('Please add at least one item to the invoice.');
        return false;
    }

    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
});

// Add event listeners for form validation
document.getElementById('customer_id').addEventListener('change', validateForm);
</script>