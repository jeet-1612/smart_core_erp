<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/sales/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Sales Order
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
                            Total Sales Orders
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($sales_orders); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                            Total Sales Amount
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ₹<?php 
                                $total_amount = 0;
                                foreach ($sales_orders as $order) {
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
                                foreach ($sales_orders as $order) {
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
                            Confirmed Orders
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $confirmed_count = 0;
                                foreach ($sales_orders as $order) {
                                    if ($order->status == 'confirmed') $confirmed_count++;
                                }
                                echo $confirmed_count;
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

<!-- Sales Orders Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Sales Orders</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="salesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SO Number</th>
                        <th>Client</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sales_orders)): ?>
                        <?php foreach ($sales_orders as $order): ?>
                            <tr>
                                <td><?php echo $order->so_number; ?></td>
                                <td><?php echo $order->company_name; ?></td>
                                <td><?php echo date('M d, Y', strtotime($order->so_date)); ?></td>
                                <td><?php echo $order->delivery_date ? date('M d, Y', strtotime($order->delivery_date)) : 'N/A'; ?></td>
                                <td>₹<?php echo number_format($order->total_amount, 2); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        switch($order->status) {
                                            case 'draft': echo 'secondary'; break;
                                            case 'confirmed': echo 'success'; break;
                                            case 'shipped': echo 'info'; break;
                                            case 'delivered': echo 'primary'; break;
                                            case 'cancelled': echo 'danger'; break;
                                            default: echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst($order->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/smart_core_erp/sales/view/<?php echo $order->id; ?>" 
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/smart_core_erp/sales/edit/<?php echo $order->id; ?>" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($order->status == 'draft' || $order->status == 'confirmed'): ?>
                                        <a href="/smart_core_erp/sales/create_invoice/<?php echo $order->id; ?>" 
                                           class="btn btn-success btn-sm" title="Create Invoice">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <?php endif; ?>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm delete-sales" 
                                                data-id="<?php echo $order->id; ?>"
                                                data-name="<?php echo $order->so_number; ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No sales orders found. <a href="/smart_core_erp/sales/create">Create your first sales order</a></td>
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
                <p>Are you sure you want to delete sales order: <strong id="salesName"></strong>?</p>
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
    $(document).on('click', '.delete-sales', function (e) {
        e.preventDefault();

        var salesId = $(this).data('id');
        var name = $(this).data('name');

        $('#salesName').text(name);
        $('#confirmDelete').attr('href', '/smart_core_erp/sales/delete/' + salesId);

        var modalEl = $('#deleteModal')[0];
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            console.warn('#deleteModal element not found.');
        }
    });

    // Initialize DataTable (requires DataTables JS/CSS + jQuery)
    if ($('#salesTable').length) {
        $('#salesTable').DataTable({
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }

});
</script>