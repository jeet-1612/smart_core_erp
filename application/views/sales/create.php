<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Sales Order</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/sales" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sales
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

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Sales Order Information</h6>
            </div>
            <div class="card-body">
                <form id="salesForm" action="/smart_core_erp/sales/process_create" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="client_id" class="form-label">Client *</label>
                                <select class="form-control" id="client_id" name="client_id" required>
                                    <option value="">Select Client</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?php echo $client->id; ?>">
                                            <?php echo $client->company_name . ' - ' . $client->contact_person; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="so_date" class="form-label">Order Date *</label>
                                <input type="date" class="form-control" id="so_date" name="so_date" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h6 class="text-primary mt-4 mb-3">Order Items</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="30%">Product</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Unit Price (₹)</th>
                                    <th width="10%">Tax Rate (%)</th>
                                    <th width="15%">Tax Amount (₹)</th>
                                    <th width="15%">Total (₹)</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="item-row">
                                    <td>
                                        <select class="form-control product-select" name="items[0][product_id]" required>
                                            <option value="">Select Product</option>
                                            <?php foreach ($products as $product): ?>
                                                <option value="<?php echo $product->id; ?>" 
                                                        data-price="<?php echo $product->unit_price; ?>"
                                                        data-tax="<?php echo $product->tax_rate; ?>">
                                                    <?php echo $product->product_name . ' (' . $product->product_code . ')'; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity" name="items[0][quantity]" 
                                               min="1" step="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control unit-price" name="items[0][unit_price]" 
                                               step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control tax-rate" name="items[0][tax_rate]" 
                                               step="0.01" value="18" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tax-amount" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control item-total" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Sub Total:</strong></td>
                                    <td colspan="3">
                                        <strong id="subTotal">₹0.00</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tax Amount:</strong></td>
                                    <td colspan="3">
                                        <strong id="taxTotal">₹0.00</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                    <td colspan="3">
                                        <strong id="grandTotal">₹0.00</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <button type="button" class="btn btn-success btn-sm" id="addItem">
                                            <i class="fas fa-plus"></i> Add Item
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                                <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Sales Order
                        </button>
                        <a href="/smart_core_erp/sales" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;
    
    // Add new item row
    document.getElementById('addItem').addEventListener('click', function() {
        const newRow = document.querySelector('.item-row').cloneNode(true);
        newRow.classList.add('item-row');
        
        // Update input names
        const inputs = newRow.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', '[' + itemCount + ']'));
            }
        });
        
        // Clear values
        newRow.querySelector('.product-select').value = '';
        newRow.querySelector('.quantity').value = '1';
        newRow.querySelector('.unit-price').value = '';
        newRow.querySelector('.tax-rate').value = '18';
        newRow.querySelector('.tax-amount').value = '';
        newRow.querySelector('.item-total').value = '';
        
        // Enable remove button
        newRow.querySelector('.remove-item').disabled = false;
        
        document.getElementById('itemsBody').appendChild(newRow);
        itemCount++;
        
        // Add event listeners to new row
        addRowEventListeners(newRow);
    });
    
    // Add event listeners to a row
    function addRowEventListeners(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity');
        const unitPriceInput = row.querySelector('.unit-price');
        const taxRateInput = row.querySelector('.tax-rate');
        const taxAmountInput = row.querySelector('.tax-amount');
        const itemTotalInput = row.querySelector('.item-total');
        const removeButton = row.querySelector('.remove-item');
        
        // Product select change
        productSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                unitPriceInput.value = selectedOption.getAttribute('data-price');
                taxRateInput.value = selectedOption.getAttribute('data-tax');
                calculateItemTotal(row);
            }
        });
        
        // Quantity, unit price, tax rate change
        [quantityInput, unitPriceInput, taxRateInput].forEach(input => {
            input.addEventListener('input', function() {
                calculateItemTotal(row);
            });
        });
        
        // Remove item
        removeButton.addEventListener('click', function() {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateTotals();
            }
        });
    }
    
    // Calculate item total
    function calculateItemTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const taxRate = parseFloat(row.querySelector('.tax-rate').value) || 0;
        
        const itemTotal = quantity * unitPrice;
        const taxAmount = itemTotal * (taxRate / 100);
        const grandTotal = itemTotal + taxAmount;
        
        row.querySelector('.tax-amount').value = taxAmount.toFixed(2);
        row.querySelector('.item-total').value = grandTotal.toFixed(2);
        
        calculateTotals();
    }
    
    // Calculate all totals
    function calculateTotals() {
        let subTotal = 0;
        let taxTotal = 0;
        let grandTotal = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const itemTotal = parseFloat(row.querySelector('.item-total').value) || 0;
            const taxAmount = parseFloat(row.querySelector('.tax-amount').value) || 0;
            
            subTotal += (itemTotal - taxAmount);
            taxTotal += taxAmount;
        });
        
        grandTotal = subTotal + taxTotal;
        
        document.getElementById('subTotal').textContent = '₹' + subTotal.toFixed(2);
        document.getElementById('taxTotal').textContent = '₹' + taxTotal.toFixed(2);
        document.getElementById('grandTotal').textContent = '₹' + grandTotal.toFixed(2);
    }
    
    // Add event listeners to initial row
    addRowEventListeners(document.querySelector('.item-row'));
    
    // Form validation
    const form = document.getElementById('salesForm');
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        // Check if at least one item is added
        const items = document.querySelectorAll('.item-row');
        let hasValidItem = false;
        
        items.forEach(item => {
            const productId = item.querySelector('.product-select').value;
            const quantity = item.querySelector('.quantity').value;
            if (productId && quantity) {
                hasValidItem = true;
            }
        });
        
        if (!hasValidItem) {
            valid = false;
            alert('Please add at least one item to the sales order.');
        }
        
        // Check required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>