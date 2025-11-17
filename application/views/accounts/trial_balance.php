<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Trial Balance</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print"></i> Print
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary ms-2">
            <i class="fas fa-download"></i> Export
        </button>
    </div>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/accounts/trial_balance">
            <div class="row">
                <div class="col-md-4">
                    <label for="date" class="form-label">As of Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $selected_date; ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Generate</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Trial Balance Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            Trial Balance
            <small class="text-muted">(As of <?php echo date('M d, Y', strtotime($selected_date)); ?>)</small>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($trial_balance)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Account Code</th>
                        <th>Account Name</th>
                        <th>Account Type</th>
                        <th class="text-end">Debit (₹)</th>
                        <th class="text-end">Credit (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_debit = 0;
                        $total_credit = 0;
                        foreach ($trial_balance as $account): 
                            $total_debit += $account->total_debit;
                            $total_credit += $account->total_credit;
                        ?>
                    <tr>
                        <td><strong><?php echo $account->account_code; ?></strong></td>
                        <td><?php echo $account->account_name; ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    switch($account->account_type) {
                                        case 'asset': echo 'bg-success'; break;
                                        case 'liability': echo 'bg-danger'; break;
                                        case 'equity': echo 'bg-warning'; break;
                                        case 'income': echo 'bg-info'; break;
                                        case 'expense': echo 'bg-primary'; break;
                                        default: echo 'bg-secondary';
                                    }
                                ?>">
                                <?php echo ucfirst($account->account_type); ?>
                            </span>
                        </td>
                        <td class="text-end text-success">
                            <?php echo $account->total_debit > 0 ? number_format($account->total_debit, 2) : '-'; ?>
                        </td>
                        <td class="text-end text-danger">
                            <?php echo $account->total_credit > 0 ? number_format($account->total_credit, 2) : '-'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong
                                class="text-success">₹<?php echo number_format($total_debit, 2); ?></strong></td>
                        <td class="text-end"><strong
                                class="text-danger">₹<?php echo number_format($total_credit, 2); ?></strong></td>
                    </tr>
                    <tr
                        class="<?php echo abs($total_debit - $total_credit) < 0.01 ? 'table-success' : 'table-danger'; ?>">
                        <td colspan="3" class="text-end"><strong>Difference:</strong></td>
                        <td colspan="2" class="text-end">
                            <strong>
                                ₹<?php echo number_format($total_debit - $total_credit, 2); ?>
                                <?php if (abs($total_debit - $total_credit) < 0.01): ?>
                                <span class="badge bg-success">Balanced</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Not Balanced</span>
                                <?php endif; ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted text-center">No trial balance data found for the selected date.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Cards -->
<?php if (!empty($trial_balance)): ?>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Total Debit</h6>
                <h4 class="card-text">₹<?php echo number_format($total_debit, 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h6 class="card-title">Total Credit</h6>
                <h4 class="card-text">₹<?php echo number_format($total_credit, 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Difference</h6>
                <h4 class="card-text">₹<?php echo number_format($total_debit - $total_credit, 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div
            class="card text-white <?php echo abs($total_debit - $total_credit) < 0.01 ? 'bg-success' : 'bg-warning'; ?>">
            <div class="card-body">
                <h6 class="card-title">Status</h6>
                <h4 class="card-text">
                    <?php echo abs($total_debit - $total_credit) < 0.01 ? 'Balanced' : 'Not Balanced'; ?>
                </h4>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>