<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Purchase Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/purchase/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Purchase Order
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

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Purchase Orders
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($purchase_orders); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Purchase Amount
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ₹<?php 
                                $total_amount = 0;
                                foreach ($purchase_orders as $order) {
                                    $total_amount += $order->total_amount;
                                }
                                echo number_format($total_amount, 2);
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Orders
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $pending_count = 0;
                                foreach ($purchase_orders as $order) {
                                    if ($order->status == 'draft') $pending_count++;
                                }
                                echo $pending_count;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Received Orders
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $received_count = 0;
                                foreach ($purchase_orders as $order) {
                                    if ($order->status == 'received') $received_count++;
                                }
                                echo $received_count;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Orders Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Purchase Orders</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="purchaseTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Vendor</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($purchase_orders)): ?>
                        <?php foreach ($purchase_orders as $order): ?>
                            <tr>
                                <td><?php echo $order->po_number; ?></td>
                                <td><?php echo $order->company_name; ?></td>
                                <td><?php echo date('M d, Y', strtotime($order->po_date)); ?></td>
                                <td><?php echo $order->expected_delivery_date ? date('M d, Y', strtotime($order->expected_delivery_date)) : 'N/A'; ?></td>
                                <td>₹<?php echo number_format($order->total_amount, 2); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        switch($order->status) {
                                            case 'draft': echo 'secondary'; break;
                                            case 'sent': echo 'info'; break;
                                            case 'confirmed': echo 'primary'; break;
                                            case 'received': echo 'success'; break;
                                            case 'cancelled': echo 'danger'; break;
                                            default: echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst($order->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/smart_core_erp/purchase/view/<?php echo $order->id; ?>" 
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/smart_core_erp/purchase/edit/<?php echo $order->id; ?>" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm delete-purchase" 
                                                data-id="<?php echo $order->id; ?>"
                                                data-name="<?php echo $order->po_number; ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No purchase orders found. <a href="/smart_core_erp/purchase/create">Create your first purchase order</a></td>
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
                <p>Are you sure you want to delete purchase order: <strong id="purchaseName"></strong>?</p>
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
    // Delete confirmation (delegated so it works for dynamic rows too)
    $(document).on('click', '.delete-purchase', function (e) {
        e.preventDefault();

        var purchaseId = $(this).data('id');
        var name = $(this).data('name');

        $('#purchaseName').text(name);
        $('#confirmDelete').attr('href', '/smart_core_erp/purchase/delete/' + purchaseId);

        var modalEl = $('#deleteModal')[0];
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            console.warn('Delete modal element (#deleteModal) not found.');
        }
    });

    // Initialize DataTable
    if ($('#purchaseTable').length) {
        $('#purchaseTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }

});
</script>