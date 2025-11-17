<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Journal Entry</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/accounts/journal_entries" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Journal Entries
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

<!-- Journal Entry Form -->
<form method="POST" action="/smart_core_erp/accounts/process_journal_entry" id="journalEntryForm">
    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="entry_date" class="form-label">Entry Date *</label>
                                <input type="date" class="form-control" id="entry_date" name="entry_date"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reference" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference" name="reference"
                                    placeholder="Optional reference number">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Enter journal entry description" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Entry Items -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Entry Items</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="entryItems">
                        <!-- Items will be added here dynamically -->
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="text-center text-muted py-4">
                        <i class="fas fa-receipt fa-3x mb-3"></i>
                        <p>No items added yet. Click "Add Item" to start.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Totals and Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="total-section mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Debit:</span>
                            <span id="totalDebit" class="text-success fw-bold">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Credit:</span>
                            <span id="totalCredit" class="text-danger fw-bold">₹0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Difference:</span>
                            <span id="difference" class="fw-bold">₹0.00</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                            <i class="fas fa-save"></i> Save Journal Entry
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

<!-- Item Template (Hidden) -->
<div id="itemTemplate" class="d-none">
    <div class="entry-item">
        <div class="row">
            <div class="col-md-5">
                <div class="mb-3">
                    <label class="form-label">Account *</label>
                    <select class="form-select account-select" name="items[INDEX][account_id]" required>
                        <option value="">Select Account</option>
                        <?php foreach ($accounts as $account): ?>
                        <option value="<?php echo $account->id; ?>">
                            <?php echo $account->account_code . ' - ' . $account->account_name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Debit Amount</label>
                    <input type="number" class="form-control debit-amount" name="items[INDEX][debit_amount]" step="0.01"
                        min="0" placeholder="0.00">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Credit Amount</label>
                    <input type="number" class="form-control credit-amount" name="items[INDEX][credit_amount]"
                        step="0.01" min="0" placeholder="0.00">
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
                    <input type="text" class="form-control" name="items[INDEX][item_description]"
                        placeholder="Item description (optional)">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function() {
    var itemCount = 0;

    // Initialize with one empty item
    addItem();

    // Add new item
    $('#addItemBtn').on('click', function() {
        addItem();
    });

    function addItem() {
        var template = $('#itemTemplate').html();
        var newHtml = template.replace(/INDEX/g, itemCount);

        var $entryItems = $('#entryItems');
        var $emptyState = $('#emptyState');

        $entryItems.append(newHtml);
        $emptyState.hide();

        var $newItem = $entryItems.children().last();

        // Auto-focus on account select
        $newItem.find('.account-select').focus();

        itemCount++;
        calculateTotals();
    }

    // Delegated input listeners for debit/credit to recalc totals
    $('#entryItems').on('input', '.debit-amount, .credit-amount', function() {
        calculateTotals();
    });

    // Delegated remove button
    $('#entryItems').on('click', '.remove-item', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.entry-item');
        $item.remove();
        calculateTotals();
        if ($('#entryItems').children().length === 0) {
            $('#emptyState').show();
        }
    });

    // Calculate totals
    function calculateTotals() {
        var totalDebit = 0;
        var totalCredit = 0;

        $('.entry-item').each(function() {
            var debit = parseFloat($(this).find('.debit-amount').val()) || 0;
            var credit = parseFloat($(this).find('.credit-amount').val()) || 0;

            totalDebit += debit;
            totalCredit += credit;
        });

        $('#totalDebit').text('₹' + totalDebit.toFixed(2));
        $('#totalCredit').text('₹' + totalCredit.toFixed(2));

        var difference = totalDebit - totalCredit;
        $('#difference').text('₹' + Math.abs(difference).toFixed(2));

        validateForm(totalDebit, totalCredit);
    }

    // Validate form
    function validateForm(totalDebit, totalCredit) {
        var $submitBtn = $('#submitBtn');
        var $validationMessages = $('#validationMessages');
        var $items = $('.entry-item');

        var messages = [];

        // Check if there are items
        if ($items.length === 0) {
            messages.push('<div class="alert alert-warning">Add at least one journal entry item.</div>');
        }

        // Check if debits equal credits
        if (Math.abs(totalDebit - totalCredit) > 0.01) {
            messages.push('<div class="alert alert-danger">Journal entry must balance. Total debits must equal total credits.</div>');
        }

        // Check if all items have either debit or credit and account present
        var validItems = true;
        $items.each(function() {
            var debit = parseFloat($(this).find('.debit-amount').val()) || 0;
            var credit = parseFloat($(this).find('.credit-amount').val()) || 0;
            var account = $(this).find('.account-select').val();

            if (!account) validItems = false;
            if (debit === 0 && credit === 0) validItems = false;
            if (debit > 0 && credit > 0) validItems = false;
        });

        if (!validItems) {
            messages.push('<div class="alert alert-warning">All items must have an account and either debit or credit amount (not both).</div>');
        }

        $validationMessages.html(messages.join(''));

        // Enable/disable submit button
        $submitBtn.prop('disabled', messages.length > 0 || $items.length === 0);
    }

    // Reset form
    window.resetForm = function() { // keep same function name if used elsewhere
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            $('#journalEntryForm')[0].reset();
            $('#entryItems').empty();
            $('#emptyState').show();
            itemCount = 0;
            addItem();
            calculateTotals();
        }
    };

    // Form submission validation
    $('#journalEntryForm').on('submit', function(e) {
        var totalDebit = parseFloat($('#totalDebit').text().replace('₹', '')) || 0;
        var totalCredit = parseFloat($('#totalCredit').text().replace('₹', '')) || 0;

        if (Math.abs(totalDebit - totalCredit) > 0.01) {
            e.preventDefault();
            alert('Journal entry must balance. Total debits must equal total credits.');
            return false;
        }

        // Show loading state
        var $submitBtn = $('#submitBtn');
        $submitBtn.prop('disabled', true);
        $submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    });

    // Auto-calculate: when debit is entered, clear credit and vice versa (delegated)
    $(document).on('input', '.debit-amount', function() {
        var val = parseFloat($(this).val()) || 0;
        if (val > 0) {
            $(this).closest('.row').find('.credit-amount').val('');
        }
    });

    $(document).on('input', '.credit-amount', function() {
        var val = parseFloat($(this).val()) || 0;
        if (val > 0) {
            $(this).closest('.row').find('.debit-amount').val('');
        }
    });
});
</script>