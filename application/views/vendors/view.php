<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Vendor Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/vendors" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Vendors
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Vendor Information</h6>
                <div>
                    <span class="badge bg-<?php echo $vendor->status == 'active' ? 'success' : 'danger'; ?>">
                        <?php echo ucfirst($vendor->status); ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Vendor Code:</th>
                                <td><?php echo $vendor->vendor_code; ?></td>
                            </tr>
                            <tr>
                                <th>Company Name:</th>
                                <td><?php echo $vendor->company_name; ?></td>
                            </tr>
                            <tr>
                                <th>Contact Person:</th>
                                <td><?php echo $vendor->contact_person; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $vendor->email; ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo $vendor->phone; ?></td>
                            </tr>
                            <tr>
                                <th>Mobile:</th>
                                <td><?php echo $vendor->mobile; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">GSTIN:</th>
                                <td><?php echo $vendor->gstin ?: 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>PAN Number:</th>
                                <td><?php echo $vendor->pan_number ?: 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Created On:</th>
                                <td><?php echo date('M d, Y', strtotime($vendor->created_at)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($vendor->address): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Address</h6>
                        <p><?php echo nl2br($vendor->address); ?></p>
                        <p>
                            <?php echo $vendor->city; ?>, 
                            <?php echo $vendor->state; ?> - 
                            <?php echo $vendor->pin_code; ?><br>
                            <?php echo $vendor->country; ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($vendor->bank_name): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-primary">Bank Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Bank Name:</th>
                                <td><?php echo $vendor->bank_name; ?></td>
                            </tr>
                            <tr>
                                <th>Account Number:</th>
                                <td><?php echo $vendor->bank_account_number; ?></td>
                            </tr>
                            <tr>
                                <th>IFSC Code:</th>
                                <td><?php echo $vendor->ifsc_code; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="/smart_core_erp/vendors/edit/<?php echo $vendor->id; ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Vendor
                        </a>
                        <button type="button" class="btn btn-danger delete-vendor" 
                                data-id="<?php echo $vendor->id; ?>"
                                data-name="<?php echo $vendor->company_name; ?>">
                            <i class="fas fa-trash"></i> Delete Vendor
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
                        <i class="fas fa-shopping-bag"></i> Create Purchase Order
                    </a>
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-file-invoice"></i> View Purchase History
                    </a>
                    <a href="#" class="btn btn-info">
                        <i class="fas fa-rupee-sign"></i> Make Payment
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
                <!-- You can add recent purchase orders, payments, etc. here -->
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
                <p>Are you sure you want to delete vendor: <strong id="vendorName"></strong>?</p>
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
    const deleteButton = document.querySelector('.delete-vendor');
    const vendorName = document.getElementById('vendorName');
    const confirmDelete = document.getElementById('confirmDelete');
    
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const vendorId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            vendorName.textContent = name;
            confirmDelete.href = '/smart_core_erp/vendors/delete/' + vendorId;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    }
});
</script>