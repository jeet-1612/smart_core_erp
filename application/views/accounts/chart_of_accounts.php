<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Chart of Accounts</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="fas fa-plus"></i> Add Account
        </button>
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

<!-- Accounts Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Accounts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Account Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $account): ?>
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
                        <td><?php echo $account->description ?: 'N/A'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No accounts found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/smart_core_erp/accounts/add_account" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">Add New Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="account_code" class="form-label">Account Code *</label>
                        <input type="text" class="form-control" id="account_code" name="account_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_name" class="form-label">Account Name *</label>
                        <input type="text" class="form-control" id="account_name" name="account_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Account Type *</label>
                        <select class="form-select" id="account_type" name="account_type" required>
                            <option value="">Select Type</option>
                            <option value="asset">Asset</option>
                            <option value="liability">Liability</option>
                            <option value="equity">Equity</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="parent_account_id" class="form-label">Parent Account</label>
                        <select class="form-select" id="parent_account_id" name="parent_account_id">
                            <option value="">No Parent</option>
                            <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo $account->id; ?>">
                                <?php echo $account->account_code . ' - ' . $account->account_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Account</button>
                </div>
            </form>
        </div>
    </div>
</div>