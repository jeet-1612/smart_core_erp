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
                                        <select class="form-control product-select" name="items[0][product_id]"
                                            required>
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
                                <textarea class="form-control" id="terms_conditions" name="terms_conditions"
                                    rows="3"></textarea>
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    var itemCount = 1;

    // Add new item row
    $('#addItem').on('click', function (e) {
        e.preventDefault();

        var $prototype = $('.item-row').first();
        if ($prototype.length === 0) return;

        var $newRow = $prototype.clone();
        $newRow.addClass('item-row');

        // Update input/select/textarea names (replace first [0] with index)
        $newRow.find('input, select, textarea').each(function () {
            var name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[0\]/, '[' + itemCount + ']'));
            }
        });

        // Clear / set defaults
        $newRow.find('.product-select').val('');
        $newRow.find('.quantity').val('1');
        $newRow.find('.unit-price').val('');
        $newRow.find('.tax-rate').val('18');
        $newRow.find('.tax-amount').val('');
        $newRow.find('.item-total').val('');

        // Enable remove button
        $newRow.find('.remove-item').prop('disabled', false);

        $('#itemsBody').append($newRow);
        itemCount++;

        // Add event listeners to new row
        addRowEventListeners($newRow);
        calculateTotals();
    });

    // Add event listeners for a row (accepts jQuery row)
    function addRowEventListeners($row) {
        $row = $($row);

        var $productSelect = $row.find('.product-select');
        var $quantityInput = $row.find('.quantity');
        var $unitPriceInput = $row.find('.unit-price');
        var $taxRateInput = $row.find('.tax-rate');
        var $removeButton = $row.find('.remove-item');

        // Product select change
        $productSelect.off('change.item').on('change.item', function () {
            var $opt = $(this).find('option:selected');
            var price = $opt.data('price');
            var tax = $opt.data('tax');

            if (price !== undefined) $unitPriceInput.val(price);
            if (tax !== undefined) $taxRateInput.val(tax);

            calculateItemTotal($row);
        });

        // Quantity/unit-price/tax inputs
        $quantityInput.add($unitPriceInput).add($taxRateInput)
            .off('input.item').on('input.item', function () {
                calculateItemTotal($row);
            });

        // Remove item
        $removeButton.off('click.item').on('click.item', function (e) {
            e.preventDefault();
            if ($('.item-row').length > 1) {
                $row.remove();
                calculateTotals();
            }
        });

        // Calculate initial values for this row
        calculateItemTotal($row);
    }

    // Calculate item total for a row
    function calculateItemTotal($row) {
        $row = $($row);
        var quantity = parseFloat($row.find('.quantity').val()) || 0;
        var unitPrice = parseFloat($row.find('.unit-price').val()) || 0;
        var taxRate = parseFloat($row.find('.tax-rate').val()) || 0;

        var itemTotal = quantity * unitPrice;
        var taxAmount = itemTotal * (taxRate / 100);
        var grandTotal = itemTotal + taxAmount;

        $row.find('.tax-amount').val(taxAmount.toFixed(2));
        $row.find('.item-total').val(grandTotal.toFixed(2));

        calculateTotals();
    }

    // Calculate all totals
    function calculateTotals() {
        var subTotal = 0;
        var taxTotal = 0;

        $('.item-row').each(function () {
            var itemTotal = parseFloat($(this).find('.item-total').val()) || 0;
            var taxAmount = parseFloat($(this).find('.tax-amount').val()) || 0;

            subTotal += (itemTotal - taxAmount);
            taxTotal += taxAmount;
        });

        var grandTotal = subTotal + taxTotal;

        $('#subTotal').text('₹' + subTotal.toFixed(2));
        $('#taxTotal').text('₹' + taxTotal.toFixed(2));
        $('#grandTotal').text('₹' + grandTotal.toFixed(2));
    }

    // Wire events for initial row(s)
    $('.item-row').each(function () {
        var $q = $(this).find('.quantity');
        if ($q.length && ($q.val() === '' || $q.val() === undefined)) $q.val('1');
        var $tax = $(this).find('.tax-rate');
        if ($tax.length && ($tax.val() === '' || $tax.val() === undefined)) $tax.val('18');

        addRowEventListeners($(this));
    });

    // Form validation
    var $form = $('#salesForm');
    if ($form.length) {
        $form.on('submit', function (e) {
            var valid = true;

            // Check at least one valid item
            var hasValidItem = false;
            $('.item-row').each(function () {
                var productId = $(this).find('.product-select').val();
                var quantity = $(this).find('.quantity').val();
                if (productId && quantity) hasValidItem = true;
            });

            if (!hasValidItem) {
                valid = false;
                alert('Please add at least one item to the sales order.');
            }

            // Required fields
            $form.find('[required]').each(function () {
                if (!$(this).val() || !String($(this).val()).trim()) {
                    valid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!valid) e.preventDefault();
        });
    }
});
</script>
