

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Journal Entry Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/accounts/journal_entries" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Journal Entries
        </a>
    </div>
</div>

<?php if ($journal_entry): ?>
<!-- Journal Entry Header -->
<div class="journal-header">
    <div class="row">
        <div class="col-md-6">
            <h4><?php echo $journal_entry->entry_number; ?></h4>
            <p class="text-muted"><?php echo $journal_entry->description; ?></p>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge status-badge 
                                <?php echo $journal_entry->status == 'posted' ? 'bg-success' : 'bg-warning'; ?>">
                <?php echo ucfirst($journal_entry->status); ?>
            </span>
            <p class="mt-2 mb-1">
                <strong>Date:</strong> <?php echo date('F d, Y', strtotime($journal_entry->entry_date)); ?>
            </p>
            <?php if ($journal_entry->reference): ?>
            <p class="mb-1">
                <strong>Reference:</strong> <?php echo $journal_entry->reference; ?>
            </p>
            <?php endif; ?>
            <p class="mb-0">
                <strong>Created:</strong> <?php echo date('M d, Y g:i A', strtotime($journal_entry->created_at)); ?>
            </p>
        </div>
    </div>
</div>

<!-- Entry Items -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Entry Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Account</th>
                        <th>Account Code</th>
                        <th>Description</th>
                        <th class="text-end">Debit Amount</th>
                        <th class="text-end">Credit Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                    $total_debit = 0;
                                    $total_credit = 0;
                                    foreach ($journal_entry_items as $item): 
                                        $total_debit += $item->debit_amount;
                                        $total_credit += $item->credit_amount;
                                    ?>
                    <tr>
                        <td><?php echo $item->account_name; ?></td>
                        <td><code><?php echo $item->account_code; ?></code></td>
                        <td><?php echo $item->description ?: 'N/A'; ?></td>
                        <td class="text-end debit-amount">
                            <?php echo $item->debit_amount > 0 ? '₹' . number_format($item->debit_amount, 2) : '-'; ?>
                        </td>
                        <td class="text-end credit-amount">
                            <?php echo $item->credit_amount > 0 ? '₹' . number_format($item->credit_amount, 2) : '-'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end debit-amount">
                            <strong>₹<?php echo number_format($total_debit, 2); ?></strong></td>
                        <td class="text-end credit-amount">
                            <strong>₹<?php echo number_format($total_credit, 2); ?></strong></td>
                    </tr>
                    <tr
                        class="<?php echo abs($total_debit - $total_credit) < 0.01 ? 'table-success' : 'table-danger'; ?>">
                        <td colspan="3" class="text-end"><strong>Difference:</strong></td>
                        <td colspan="2" class="text-end">
                            <strong>₹<?php echo number_format($total_debit - $total_credit, 2); ?></strong>
                            <?php if (abs($total_debit - $total_credit) < 0.01): ?>
                            <span class="badge bg-success ms-2">Balanced</span>
                            <?php else: ?>
                            <span class="badge bg-danger ms-2">Not Balanced</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <div>
                <?php if ($journal_entry->status == 'draft'): ?>
                <a href="/smart_core_erp/accounts/post_journal_entry/<?php echo $journal_entry->id; ?>"
                    class="btn btn-success"
                    onclick="return confirm('Are you sure you want to post this journal entry? This action cannot be undone.')">
                    <i class="fas fa-check"></i> Post Entry
                </a>
                <a href="/smart_core_erp/accounts/delete_journal_entry/<?php echo $journal_entry->id; ?>"
                    class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this journal entry? This action cannot be undone.')">
                    <i class="fas fa-trash"></i> Delete Entry
                </a>
                <?php endif; ?>
            </div>
            <div>
                <button class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Journal Entry Not Found</h4>
    <p>The requested journal entry could not be found.</p>
    <hr>
    <a href="/smart_core_erp/accounts/journal_entries" class="btn btn-outline-danger">
        Back to Journal Entries
    </a>
</div>
<?php endif; ?>