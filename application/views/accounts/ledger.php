<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">General Ledger</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/accounts/ledger">
            <div class="row">
                <div class="col-md-4">
                    <label for="account_id" class="form-label">Select Account</label>
                    <select class="form-select" id="account_id" name="account_id" required>
                        <option value="">Choose Account...</option>
                        <?php foreach ($accounts as $account): ?>
                        <option value="<?php echo $account->id; ?>"
                            <?php echo isset($selected_account) && $selected_account->id == $account->id ? 'selected' : ''; ?>>
                            <?php echo $account->account_code . ' - ' . $account->account_name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?php echo $this->input->get('start_date'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $this->input->get('end_date'); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">View Ledger</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Ledger Entries -->
<?php if (isset($ledger_entries) && isset($selected_account)): ?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            Ledger: <?php echo $selected_account->account_code . ' - ' . $selected_account->account_name; ?>
            <small class="text-muted">
                (<?php echo $this->input->get('start_date') ? date('M d, Y', strtotime($this->input->get('start_date'))) : 'Beginning'; ?>
                to
                <?php echo $this->input->get('end_date') ? date('M d, Y', strtotime($this->input->get('end_date'))) : date('M d, Y'); ?>)
            </small>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($ledger_entries)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Entry #</th>
                        <th>Description</th>
                        <th class="text-end">Debit (₹)</th>
                        <th class="text-end">Credit (₹)</th>
                        <th class="text-end">Balance (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                        $running_balance = 0;
                                        foreach ($ledger_entries as $entry): 
                                            $running_balance += $entry->debit_amount - $entry->credit_amount;
                                        ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($entry->entry_date)); ?></td>
                        <td><?php echo $entry->entry_number; ?></td>
                        <td><?php echo $entry->item_description ?: $entry->description; ?></td>
                        <td class="text-end text-success">
                            <?php echo $entry->debit_amount > 0 ? number_format($entry->debit_amount, 2) : '-'; ?>
                        </td>
                        <td class="text-end text-danger">
                            <?php echo $entry->credit_amount > 0 ? number_format($entry->credit_amount, 2) : '-'; ?>
                        </td>
                        <td class="text-end <?php echo $running_balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo number_format($running_balance, 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Final Balance:</strong></td>
                        <td colspan="3" class="text-end">
                            <strong class="<?php echo $running_balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                                ₹<?php echo number_format($running_balance, 2); ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted text-center">No ledger entries found for the selected criteria.</p>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body text-center">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Select an account to view ledger entries</h5>
        <p class="text-muted">Choose an account from the dropdown above and click "View Ledger"</p>
    </div>
</div>
<?php endif; ?>