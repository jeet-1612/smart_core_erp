<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Client Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/clients" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Clients
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
                <div>
                    <span class="badge bg-<?php echo $client->status == 'active' ? 'success' : 'danger'; ?>">
                        <?php echo ucfirst($client->status); ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Client Code:</th>
                                <td><?php echo $client->client_code; ?></td>
                            </tr>
                            <tr>
                                <th>Company Name:</th>
                                <td><?php echo $client->company_name; ?></td>
                            </tr>
                            <tr>
                                <th>Contact Person:</th>
                                <td><?php echo $client->contact_person; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $client->email; ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo $client->phone; ?></td>
                            </tr>
                            <tr>
                                <th>Mobile:</th>
                                <td><?php echo $client->mobile; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">GSTIN:</th>
                                <td><?php echo $client->gstin ?: 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>PAN Number:</th>
                                <td><?php echo $client->pan_number ?: 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Credit Limit:</th>
                                <td>₹<?php echo number_format($client->credit_limit, 2); ?></td>
                            </tr>
                            <tr>
                                <th>Outstanding:</th>
                                <td>₹<?php echo number_format($client->outstanding_balance, 2); ?></td>
                            </tr>
                            <tr>
                                <th>Created On:</th>
                                <td><?php echo date('M d, Y', strtotime($client->created_at)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($client->address): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Address</h6>
                        <p><?php echo nl2br($client->address); ?></p>
                        <p>
                            <?php echo $client->city; ?>, 
                            <?php echo $client->state; ?> - 
                            <?php echo $client->pin_code; ?><br>
                            <?php echo $client->country; ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="/smart_core_erp/clients/edit/<?php echo $client->id; ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Client
                        </a>
                        <button type="button" class="btn btn-danger delete-client" 
                                data-id="<?php echo $client->id; ?>"
                                data-name="<?php echo $client->company_name; ?>">
                            <i class="fas fa-trash"></i> Delete Client
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-file-invoice"></i> Create Invoice
                    </a>
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-shopping-cart"></i> New Sale Order
                    </a>
                    <a href="#" class="btn btn-info">
                        <i class="fas fa-history"></i> View Transactions
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">No recent activity</p>
                <!-- You can add recent transactions, invoices, etc. here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete client: <strong id="clientName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    const deleteButton = document.querySelector('.delete-client');
    const clientName = document.getElementById('clientName');
    const confirmDelete = document.getElementById('confirmDelete');
    
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const clientId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            clientName.textContent = name;
            confirmDelete.href = '/smart_core_erp/clients/delete/' + clientId;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    }
});
</script>