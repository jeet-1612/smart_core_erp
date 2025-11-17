<style>
.status-draft {
    background-color: #fff3cd;
    color: #856404;
}

.status-posted {
    background-color: #d1edff;
    color: #004085;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .075);
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Journal Entries</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/accounts/create_journal_entry" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> New Journal Entry
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

<!-- Journal Entries Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Journal Entries</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($journal_entries)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Entry #</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th class="text-end">Debit Total</th>
                        <th class="text-end">Credit Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($journal_entries as $entry): ?>
                    <tr>
                        <td><strong><?php echo $entry->entry_number; ?></strong></td>
                        <td><?php echo date('M d, Y', strtotime($entry->entry_date)); ?></td>
                        <td><?php echo $entry->description; ?></td>
                        <td><?php echo $entry->reference ?: 'N/A'; ?></td>
                        <td class="text-end text-success">₹<?php echo number_format($entry->total_debit, 2); ?></td>
                        <td class="text-end text-danger">₹<?php echo number_format($entry->total_credit, 2); ?></td>
                        <td>
                            <span class="badge status-<?php echo $entry->status; ?>">
                                <?php echo ucfirst($entry->status); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/smart_core_erp/accounts/view_journal_entry/<?php echo $entry->id; ?>"
                                    class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($entry->status == 'draft'): ?>
                                <a href="/smart_core_erp/accounts/post_journal_entry/<?php echo $entry->id; ?>"
                                    class="btn btn-outline-success" title="Post Entry"
                                    onclick="return confirm('Are you sure you want to post this journal entry? This action cannot be undone.')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="/smart_core_erp/accounts/delete_journal_entry/<?php echo $entry->id; ?>"
                                    class="btn btn-outline-danger" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this journal entry? This action cannot be undone.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Journal Entries Found</h5>
            <p class="text-muted">Get started by creating your first journal entry.</p>
            <a href="/smart_core_erp/accounts/create_journal_entry" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Journal Entry
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Statistics -->
<?php if (!empty($journal_entries)): ?>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total Entries</h6>
                <h4 class="card-text"><?php echo count($journal_entries); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Posted Entries</h6>
                <h4 class="card-text">
                    <?php 
                                    $posted = array_filter($journal_entries, function($entry) {
                                        return $entry->status == 'posted';
                                    });
                                    echo count($posted);
                                    ?>
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Draft Entries</h6>
                <h4 class="card-text">
                    <?php 
                                    $draft = array_filter($journal_entries, function($entry) {
                                        return $entry->status == 'draft';
                                    });
                                    echo count($draft);
                                    ?>
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Total Amount</h6>
                <h4 class="card-text">
                    ₹<?php 
                                    $total = 0;
                                    foreach ($journal_entries as $entry) {
                                        $total += $entry->total_debit;
                                    }
                                    echo number_format($total, 2);
                                    ?>
                </h4>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>