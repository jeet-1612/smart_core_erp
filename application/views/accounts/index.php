<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Accounts Management</h1>
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

<!-- Financial Summary Cards -->
<div class="row">
    <div class="col-md-2 col-sm-6">
        <div class="card financial-card revenue">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Revenue</h6>
                        <h4 class="stat-number positive">
                            ₹<?php echo number_format($financial_summary['total_revenue'], 2); ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                    </div>
                </div>
                <small class="text-muted">Current Month</small>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6">
        <div class="card financial-card expense">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Expenses</h6>
                        <h4 class="stat-number negative">
                            ₹<?php echo number_format($financial_summary['total_expenses'], 2); ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-2x text-danger"></i>
                    </div>
                </div>
                <small class="text-muted">Current Month</small>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6">
        <div class="card financial-card receivable">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Accounts Receivable</h6>
                        <h4 class="stat-number">
                            ₹<?php echo number_format($financial_summary['accounts_receivable'], 2); ?></h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hand-holding-usd fa-2x text-warning"></i>
                    </div>
                </div>
                <small class="text-muted">Outstanding</small>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6">
        <div class="card financial-card payable">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Accounts Payable</h6>
                        <h4 class="stat-number">₹<?php echo number_format($financial_summary['accounts_payable'], 2); ?>
                        </h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-credit-card fa-2x text-info"></i>
                    </div>
                </div>
                <small class="text-muted">Due</small>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6">
        <div class="card financial-card profit">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Net Profit</h6>
                        <h4
                            class="stat-number <?php echo $financial_summary['net_profit'] >= 0 ? 'positive' : 'negative'; ?>">
                            ₹<?php echo number_format($financial_summary['net_profit'], 2); ?>
                        </h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                </div>
                <small class="text-muted">Current Month</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/accounts/create_journal_entry" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> New Journal Entry
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/accounts/financial_reports" class="btn btn-success w-100">
                            <i class="fas fa-chart-bar"></i> Financial Reports
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/accounts/ledger" class="btn btn-info w-100">
                            <i class="fas fa-book"></i> General Ledger
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/accounts/trial_balance" class="btn btn-warning w-100">
                            <i class="fas fa-balance-scale"></i> Trial Balance
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4 mb-3">
                        <a href="/smart_core_erp/accounts/chart_of_accounts" class="btn btn-secondary w-100">
                            <i class="fas fa-list"></i> Chart of Accounts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Journal Entries</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_transactions)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Entry #</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_transactions as $transaction): ?>
                            <tr>
                                <td><?php echo $transaction->entry_number; ?></td>
                                <td><?php echo date('M d, Y', strtotime($transaction->entry_date)); ?></td>
                                <td><?php echo $transaction->description; ?></td>
                                <td class="text-success">₹<?php echo number_format($transaction->total_debit, 2); ?>
                                </td>
                                <td class="text-danger">₹<?php echo number_format($transaction->total_credit, 2); ?>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?php echo ucfirst($transaction->status); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">No recent transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    // Delete confirmation
    $(document).on('click', '.delete-sales', function () {
        let salesId = $(this).data('id');
        let name = $(this).data('name');

        $('#salesName').text(name);
        $('#confirmDelete').attr('href', '/smart_core_erp/sales/delete/' + salesId);

        let modal = new bootstrap.Modal($('#deleteModal')[0]);
        modal.show();
    });

    // Initialize DataTable
    if ($('#salesTable').length) {
        $('#salesTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }
});
</script>
