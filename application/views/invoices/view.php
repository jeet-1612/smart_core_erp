<style>
.invoice-header {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.status-badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

.amount-due {
    font-size: 1.2rem;
    font-weight: bold;
}

.payment-section {
    background-color: #e8f5e8;
    border-radius: 5px;
    padding: 15px;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Invoice Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/invoices" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Invoices
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

<?php if ($invoice): ?>
<!-- Invoice Header -->
<div class="invoice-header">
    <div class="row">
        <div class="col-md-6">
            <h4>Invoice: <?php echo $invoice->invoice_number; ?></h4>
            <p class="text-muted mb-0">Created:
                <?php echo date('F d, Y', strtotime($invoice->created_at)); ?></p>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge status-badge 
                                <?php 
                                switch($invoice->status) {
                                    case 'draft': echo 'bg-warning'; break;
                                    case 'sent': echo 'bg-info'; break;
                                    case 'paid': echo 'bg-success'; break;
                                    case 'cancelled': echo 'bg-danger'; break;
                                    default: echo 'bg-secondary';
                                }
                                ?>">
                <?php echo ucfirst($invoice->status); ?>
            </span>
            <p class="mt-2 mb-1">
                <strong>Invoice Date:</strong>
                <?php echo date('F d, Y', strtotime($invoice->invoice_date)); ?>
            </p>
            <p class="mb-1">
                <strong>Due Date:</strong> <?php echo date('F d, Y', strtotime($invoice->due_date)); ?>
            </p>
            <?php if ($invoice->reference): ?>
            <p class="mb-0">
                <strong>Reference:</strong> <?php echo $invoice->reference; ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Bill To</h5>
            </div>
            <div class="card-body">
                <h6><?php echo $invoice->customer_name; ?></h6>
                <?php if ($invoice->contact_person): ?>
                <p class="mb-1">Attn: <?php echo $invoice->contact_person; ?></p>
                <?php endif; ?>
                <?php if ($invoice->email): ?>
                <p class="mb-1">Email: <?php echo $invoice->email; ?></p>
                <?php endif; ?>
                <?php if ($invoice->phone): ?>
                <p class="mb-1">Phone: <?php echo $invoice->phone; ?></p>
                <?php endif; ?>
                <?php if ($invoice->address): ?>
                <p class="mb-1">Address: <?php echo $invoice->address; ?></p>
                <?php endif; ?>
                <?php if ($invoice->city || $invoice->state || $invoice->zip_code): ?>
                <p class="mb-1">
                    <?php echo $invoice->city; ?>,
                    <?php echo $invoice->state; ?>
                    <?php echo $invoice->zip_code; ?>
                </p>
                <?php endif; ?>
                <?php if ($invoice->tax_number): ?>
                <p class="mb-0">Tax Number: <?php echo $invoice->tax_number; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Invoice Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Tax Rate</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                            $subtotal = 0;
                                            foreach ($invoice_items as $item): 
                                                $item_subtotal = $item->quantity * $item->unit_price;
                                                $item_tax = $item_subtotal * ($item->tax_rate / 100);
                                                $subtotal += $item_subtotal;
                                            ?>
                            <tr>
                                <td>
                                    <?php if ($item->product_name): ?>
                                    <strong><?php echo $item->product_name; ?></strong>
                                    <?php if ($item->product_code): ?>
                                    <br><small class="text-muted"><?php echo $item->product_code; ?></small>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <strong>Service</strong>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $item->description ?: 'N/A'; ?></td>
                                <td class="text-end"><?php echo number_format($item->quantity, 2); ?>
                                </td>
                                <td class="text-end">₹<?php echo number_format($item->unit_price, 2); ?>
                                </td>
                                <td class="text-end"><?php echo number_format($item->tax_rate, 2); ?>%
                                </td>
                                <td class="text-end">₹<?php echo number_format($item->line_total, 2); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        <?php if ($invoice->customer_notes || $invoice->terms_conditions): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Notes & Terms</h5>
            </div>
            <div class="card-body">
                <?php if ($invoice->customer_notes): ?>
                <h6>Customer Notes:</h6>
                <p><?php echo nl2br(htmlspecialchars($invoice->customer_notes)); ?></p>
                <?php endif; ?>

                <?php if ($invoice->terms_conditions): ?>
                <h6>Terms & Conditions:</h6>
                <p><?php echo nl2br(htmlspecialchars($invoice->terms_conditions)); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <!-- Invoice Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Invoice Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₹<?php echo number_format($invoice->subtotal, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax Amount:</span>
                    <span>₹<?php echo number_format($invoice->tax_amount, 2); ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span><strong>Total Amount:</strong></span>
                    <span><strong>₹<?php echo number_format($invoice->total_amount, 2); ?></strong></span>
                </div>
                <div
                    class="d-flex justify-content-between amount-due <?php echo $invoice->balance_due > 0 ? 'text-danger' : 'text-success'; ?>">
                    <span><strong>Balance Due:</strong></span>
                    <span><strong>₹<?php echo number_format($invoice->balance_due, 2); ?></strong></span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/smart_core_erp/invoices/print_invoice/<?php echo $invoice->id; ?>"
                        class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-print"></i> Print Invoice
                    </a>
                    <a href="/smart_core_erp/invoices/edit/<?php echo $invoice->id; ?>"
                        class="btn btn-outline-secondary">
                        <i class="fas fa-edit"></i> Edit Invoice
                    </a>

                    <?php if ($invoice->status == 'draft'): ?>
                    <a href="/smart_core_erp/invoices/update_status/<?php echo $invoice->id; ?>/sent"
                        class="btn btn-outline-info">
                        <i class="fas fa-paper-plane"></i> Mark as Sent
                    </a>
                    <?php endif; ?>

                    <?php if ($invoice->status != 'paid' && $invoice->status != 'cancelled'): ?>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fas fa-credit-card"></i> Record Payment
                    </button>
                    <a href="/smart_core_erp/invoices/update_status/<?php echo $invoice->id; ?>/cancelled"
                        class="btn btn-outline-danger"
                        onclick="return confirm('Are you sure you want to cancel this invoice?')">
                        <i class="fas fa-times"></i> Cancel Invoice
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <?php if ($invoice->status == 'paid' || $invoice->balance_due < $invoice->total_amount): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment History</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Payment history would be displayed here.</p>
                <!-- You can add payment history display here -->
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/smart_core_erp/invoices/record_payment/<?php echo $invoice->id; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">Payment Amount *</label>
                        <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="0.01"
                            min="0.01" max="<?php echo $invoice->balance_due; ?>"
                            value="<?php echo $invoice->balance_due; ?>" required>
                        <div class="form-text">Maximum:
                            ₹<?php echo number_format($invoice->balance_due, 2); ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date *</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method *</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="card">Card</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reference" class="form-label">Reference</label>
                        <input type="text" class="form-control" id="reference" name="reference"
                            placeholder="Payment reference number">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php else: ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Invoice Not Found</h4>
    <p>The requested invoice could not be found.</p>
    <hr>
    <a href="/smart_core_erp/invoices" class="btn btn-outline-danger">
        Back to Invoices
    </a>
</div>
<?php endif; ?>