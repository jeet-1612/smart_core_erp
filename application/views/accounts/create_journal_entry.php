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

    const entryItems = document.getElementById('entryItems');
    const emptyState = document.getElementById('emptyState');

    entryItems.insertAdjacentHTML('beforeend', newItem);
    emptyState.style.display = 'none';

    // Add event listeners to new item
    const newItemElement = entryItems.lastElementChild;
    const debitInput = newItemElement.querySelector('.debit-amount');
    const creditInput = newItemElement.querySelector('.credit-amount');
    const removeBtn = newItemElement.querySelector('.remove-item');

    debitInput.addEventListener('input', calculateTotals);
    creditInput.addEventListener('input', calculateTotals);
    removeBtn.addEventListener('click', function() {
        newItemElement.remove();
        calculateTotals();
        if (entryItems.children.length === 0) {
            emptyState.style.display = 'block';
        }
    });

    // Auto-focus on account select
    newItemElement.querySelector('.account-select').focus();

    itemCount++;
    calculateTotals();
}

// Calculate totals
function calculateTotals() {
    let totalDebit = 0;
    let totalCredit = 0;

    document.querySelectorAll('.entry-item').forEach(function(item) {
        const debit = parseFloat(item.querySelector('.debit-amount').value) || 0;
        const credit = parseFloat(item.querySelector('.credit-amount').value) || 0;

        totalDebit += debit;
        totalCredit += credit;
    });

    document.getElementById('totalDebit').textContent = '₹' + totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = '₹' + totalCredit.toFixed(2);

    const difference = totalDebit - totalCredit;
    document.getElementById('difference').textContent = '₹' + Math.abs(difference).toFixed(2);

    // Update validation
    validateForm(totalDebit, totalCredit);
}

// Validate form
function validateForm(totalDebit, totalCredit) {
    const submitBtn = document.getElementById('submitBtn');
    const validationMessages = document.getElementById('validationMessages');
    const items = document.querySelectorAll('.entry-item');

    let messages = [];

    // Check if there are items
    if (items.length === 0) {
        messages.push('<div class="alert alert-warning">Add at least one journal entry item.</div>');
    }

    // Check if debits equal credits
    if (Math.abs(totalDebit - totalCredit) > 0.01) {
        messages.push(
            '<div class="alert alert-danger">Journal entry must balance. Total debits must equal total credits.</div>'
        );
    }

    // Check if all items have either debit or credit
    let validItems = true;
    document.querySelectorAll('.entry-item').forEach(function(item) {
        const debit = parseFloat(item.querySelector('.debit-amount').value) || 0;
        const credit = parseFloat(item.querySelector('.credit-amount').value) || 0;
        const account = item.querySelector('.account-select').value;

        if (!account) {
            validItems = false;
        }
        if (debit === 0 && credit === 0) {
            validItems = false;
        }
        if (debit > 0 && credit > 0) {
            validItems = false;
        }
    });

    if (!validItems) {
        messages.push(
            '<div class="alert alert-warning">All items must have an account and either debit or credit amount (not both).</div>'
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
        document.getElementById('journalEntryForm').reset();
        document.getElementById('entryItems').innerHTML = '';
        document.getElementById('emptyState').style.display = 'block';
        itemCount = 0;
        addItem();
        calculateTotals();
    }
}

// Form submission validation
document.getElementById('journalEntryForm').addEventListener('submit', function(e) {
    const totalDebit = parseFloat(document.getElementById('totalDebit').textContent.replace('₹', '')) || 0;
    const totalCredit = parseFloat(document.getElementById('totalCredit').textContent.replace('₹', '')) || 0;

    if (Math.abs(totalDebit - totalCredit) > 0.01) {
        e.preventDefault();
        alert('Journal entry must balance. Total debits must equal total credits.');
        return false;
    }

    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
});

// Auto-calculate: when debit is entered, clear credit and vice versa
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('debit-amount') && parseFloat(e.target.value) > 0) {
        const creditInput = e.target.closest('.row').querySelector('.credit-amount');
        creditInput.value = '';
    }
    if (e.target.classList.contains('credit-amount') && parseFloat(e.target.value) > 0) {
        const debitInput = e.target.closest('.row').querySelector('.debit-amount');
        debitInput.value = '';
    }
});
</script>