<style>
.stat-card {
    border-left: 4px solid;
    border-radius: 8px;
}

.stat-total {
    border-left-color: #007bff;
}

.stat-amount {
    border-left-color: #28a745;
}

.stat-due {
    border-left-color: #ffc107;
}

.status-draft {
    background-color: #fff3cd;
    color: #856404;
}

.status-sent {
    background-color: #d1edff;
    color: #004085;
}

.status-paid {
    background-color: #d4edda;
    color: #155724;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .075);
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Invoices</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/invoices/create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Create Invoice
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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-total">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Invoices</h6>
                        <h4 class="stat-number text-primary">
                            <?php echo $invoice_stats['total_invoices']; ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-amount">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Amount</h6>
                        <h4 class="stat-number text-success">
                            ₹<?php echo number_format($invoice_stats['total_amount'], 2); ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card stat-due">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Due</h6>
                        <h4 class="stat-number text-warning">
                            ₹<?php echo number_format($invoice_stats['total_due'], 2); ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/invoices">
            <div class="row">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="draft" <?php echo $this->input->get('status') == 'draft' ? 'selected' : ''; ?>>
                            Draft</option>
                        <option value="sent" <?php echo $this->input->get('status') == 'sent' ? 'selected' : ''; ?>>Sent
                        </option>
                        <option value="paid" <?php echo $this->input->get('status') == 'paid' ? 'selected' : ''; ?>>Paid
                        </option>
                        <option value="cancelled"
                            <?php echo $this->input->get('status') == 'cancelled' ? 'selected' : ''; ?>>
                            Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?php echo $this->input->get('start_date'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $this->input->get('end_date'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="/smart_core_erp/invoices" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Invoices Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Invoices</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($invoices)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Balance Due</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><strong><?php echo $invoice->invoice_number; ?></strong></td>
                        <td>
                            <?php echo $invoice->customer_name; ?>
                            <?php if ($invoice->email): ?>
                            <br><small class="text-muted"><?php echo $invoice->email; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($invoice->invoice_date)); ?></td>
                        <td><?php echo date('M d, Y', strtotime($invoice->due_date)); ?></td>
                        <td class="text-end">₹<?php echo number_format($invoice->total_amount, 2); ?>
                        </td>
                        <td class="text-end">
                            <strong class="<?php echo $invoice->balance_due > 0 ? 'text-danger' : 'text-success'; ?>">
                                ₹<?php echo number_format($invoice->balance_due, 2); ?>
                            </strong>
                        </td>
                        <td>
                            <span class="badge status-<?php echo $invoice->status; ?>">
                                <?php echo ucfirst($invoice->status); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/smart_core_erp/invoices/view/<?php echo $invoice->id; ?>"
                                    class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/smart_core_erp/invoices/edit/<?php echo $invoice->id; ?>"
                                    class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle"
                                        data-bs-toggle="dropdown" title="More Actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item"
                                                href="/smart_core_erp/invoices/print_invoice/<?php echo $invoice->id; ?>"
                                                target="_blank">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        </li>
                                        <?php if ($invoice->status == 'draft'): ?>
                                        <li>
                                            <a class="dropdown-item"
                                                href="/smart_core_erp/invoices/update_status/<?php echo $invoice->id; ?>/sent">
                                                <i class="fas fa-paper-plane"></i> Mark as Sent
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        <?php if ($invoice->status != 'paid' && $invoice->status != 'cancelled'): ?>
                                        <li>
                                            <a class="dropdown-item"
                                                href="/smart_core_erp/invoices/update_status/<?php echo $invoice->id; ?>/cancelled">
                                                <i class="fas fa-times"></i> Cancel
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger"
                                                href="/smart_core_erp/invoices/delete_invoice/<?php echo $invoice->id; ?>"
                                                onclick="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Invoices Found</h5>
            <p class="text-muted">Get started by creating your first invoice.</p>
            <a href="/smart_core_erp/invoices/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Invoice
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>