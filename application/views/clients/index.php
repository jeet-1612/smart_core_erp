<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Clients Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/clients/add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
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

<!-- Clients Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Clients</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="clientsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Client Code</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clients)): ?>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client->client_code; ?></td>
                        <td><?php echo $client->company_name; ?></td>
                        <td><?php echo $client->contact_person; ?></td>
                        <td><?php echo $client->email; ?></td>
                        <td><?php echo $client->phone; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $client->status == 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($client->status); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/smart_core_erp/clients/view/<?php echo $client->id; ?>"
                                    class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/smart_core_erp/clients/edit/<?php echo $client->id; ?>"
                                    class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-client"
                                    data-id="<?php echo $client->id; ?>"
                                    data-name="<?php echo $client->company_name; ?>" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No clients found. <a href="/smart_core_erp/clients/add">Add
                                your first client</a></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    // Delete confirmation
    $(document).on('click', '.delete-client', function () {
        let clientId = $(this).data('id');
        let name     = $(this).data('name');

        $('#clientName').text(name);
        $('#confirmDelete').attr('href', '/smart_core_erp/clients/delete/' + clientId);

        let modal = new bootstrap.Modal($('#deleteModal')[0]);
        modal.show();
    });

    // Initialize DataTable
    if ($('#clientsTable').length) {
        $('#clientsTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }

});
</script>
