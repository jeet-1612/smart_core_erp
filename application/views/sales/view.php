<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales Order Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/sales" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sales
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

<div class="row">
    <div class="col-md-8">
        <!-- Sales Order Details Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Sales Order Information</h6>
                <div>
                    <span class="badge bg-<?php 
                        switch($sales_order->status) {
                            case 'draft': echo 'secondary'; break;
                            case 'confirmed': echo 'success'; break;
                            case 'shipped': echo 'info'; break;
                            case 'delivered': echo 'primary'; break;
                            case 'cancelled': echo 'danger'; break;
                            default: echo 'secondary';
                        }
                    ?> fs-6">
                        <?php echo ucfirst($sales_order->status); ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">SO Number:</th>
                                <td><?php echo $sales_order->so_number; ?></td>
                            </tr>
                            <tr>
                                <th>Client:</th>
                                <td>
                                    <strong><?php echo $sales_order->company_name; ?></strong><br>
                                    <small class="text-muted"><?php echo $sales_order->contact_person; ?></small>
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $sales_order->email; ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo $sales_order->phone; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Order Date:</th>
                                <td><?php echo date('M d, Y', strtotime($sales_order->so_date)); ?></td>
                            </tr>
                            <tr>
                                <th>Delivery Date:</th>
                                <td><?php echo $sales_order->delivery_date ? date('M d, Y', strtotime($sales_order->delivery_date)) : 'Not set'; ?></td>
                            </tr>
                            <tr>
                                <th>Created By:</th>
                                <td>System User</td>
                            </tr>
                            <tr>
                                <th>Created On:</th>
                                <td><?php echo date('M d, Y h:i A', strtotime($sales_order->created_at)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($sales_order->address): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Client Address</h6>
                        <p><?php echo nl2br($sales_order->address); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th width="100" class="text-center">Quantity</th>
                                <th width="120" class="text-end">Unit Price</th>
                                <th width="100" class="text-center">Tax Rate</th>
                                <th width="120" class="text-end">Tax Amount</th>
                                <th width="120" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sales_order_items)): ?>
                                <?php foreach ($sales_order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $item->product_name; ?></strong><br>
                                            <small class="text-muted">
                                                Code: <?php echo $item->product_code; ?>
                                                <?php if ($item->hsn_code): ?>
                                                | HSN: <?php echo $item->hsn_code; ?>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td class="text-center"><?php echo $item->quantity; ?></td>
                                        <td class="text-end">₹<?php echo number_format($item->unit_price, 2); ?></td>
                                        <td class="text-center"><?php echo $item->tax_rate; ?>%</td>
                                        <td class="text-end">₹<?php echo number_format($item->tax_amount, 2); ?></td>
                                        <td class="text-end">₹<?php echo number_format($item->total_amount, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Sub Total:</strong></td>
                                <td colspan="2" class="text-end">
                                    <strong>₹<?php echo number_format($sales_order->sub_total, 2); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tax Amount:</strong></td>
                                <td colspan="2" class="text-end">
                                    <strong>₹<?php echo number_format($sales_order->tax_amount, 2); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                <td colspan="2" class="text-end">
                                    <strong class="text-primary fs-5">₹<?php echo number_format($sales_order->total_amount, 2); ?></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        <?php if ($sales_order->notes || $sales_order->terms_conditions): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if ($sales_order->notes): ?>
                    <div class="col-md-6">
                        <h6>Notes</h6>
                        <p class="text-muted"><?php echo nl2br($sales_order->notes); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($sales_order->terms_conditions): ?>
                    <div class="col-md-6">
                        <h6>Terms & Conditions</h6>
                        <p class="text-muted"><?php echo nl2br($sales_order->terms_conditions); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <!-- Action Buttons Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/smart_core_erp/sales/edit/<?php echo $sales_order->id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Sales Order
                    </a>
                    
                    <?php if ($sales_order->status == 'draft' || $sales_order->status == 'confirmed'): ?>
                    <a href="/smart_core_erp/sales/create_invoice/<?php echo $sales_order->id; ?>" class="btn btn-success">
                        <i class="fas fa-file-invoice"></i> Create Invoice
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($sales_order->status == 'draft'): ?>
                    <button type="button" class="btn btn-info" id="confirmOrder">
                        <i class="fas fa-check-circle"></i> Confirm Order
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($sales_order->status == 'confirmed'): ?>
                    <button type="button" class="btn btn-info" id="markShipped">
                        <i class="fas fa-shipping-fast"></i> Mark as Shipped
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($sales_order->status == 'shipped'): ?>
                    <button type="button" class="btn btn-primary" id="markDelivered">
                        <i class="fas fa-truck-loading"></i> Mark as Delivered
                    </button>
                    <?php endif; ?>
                    
                    <?php if ($sales_order->status != 'cancelled'): ?>
                    <button type="button" class="btn btn-danger" id="cancelOrder">
                        <i class="fas fa-times-circle"></i> Cancel Order
                    </button>
                    <?php endif; ?>
                    
                    <button type="button" class="btn btn-outline-danger delete-sales" 
                            data-id="<?php echo $sales_order->id; ?>"
                            data-name="<?php echo $sales_order->so_number; ?>">
                        <i class="fas fa-trash"></i> Delete Sales Order
                    </button>
                </div>
            </div>
        </div>

        <!-- Order Status Timeline -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item <?php echo $sales_order->status == 'draft' ? 'active' : 'completed'; ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Draft</h6>
                            <small class="text-muted">Order created</small>
                        </div>
                    </div>
                    <div class="timeline-item <?php echo $sales_order->status == 'confirmed' ? 'active' : ($sales_order->status == 'shipped' || $sales_order->status == 'delivered' ? 'completed' : ''); ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Confirmed</h6>
                            <small class="text-muted">Order confirmed</small>
                        </div>
                    </div>
                    <div class="timeline-item <?php echo $sales_order->status == 'shipped' ? 'active' : ($sales_order->status == 'delivered' ? 'completed' : ''); ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Shipped</h6>
                            <small class="text-muted">Order shipped to client</small>
                        </div>
                    </div>
                    <div class="timeline-item <?php echo $sales_order->status == 'delivered' ? 'active' : ''; ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Delivered</h6>
                            <small class="text-muted">Order delivered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Options -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Print Options</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Sales Order
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="downloadPDF">
                        <i class="fas fa-file-pdf"></i> Download as PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" id="sendEmail">
                        <i class="fas fa-envelope"></i> Email to Client
                    </button>
                </div>
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

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalTitle">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="statusModalMessage">Are you sure you want to update the order status?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #e9ecef;
    border: 3px solid #fff;
}

.timeline-item.completed .timeline-marker {
    background-color: #28a745;
}

.timeline-item.active .timeline-marker {
    background-color: #007bff;
    animation: pulse 2s infinite;
}

.timeline-content h6 {
    margin-bottom: 5px;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
}

@media print {
    .btn-toolbar, .card:not(.print-this) {
        display: none !important;
    }
    
    .card.print-this {
        display: block !important;
        border: 1px solid #000 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesOrderId = <?php echo $sales_order->id; ?>;
    
    // Delete confirmation
    const deleteButton = document.querySelector('.delete-sales');
    const salesName = document.getElementById('salesName');
    const confirmDelete = document.getElementById('confirmDelete');
    
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const salesId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            salesName.textContent = name;
            confirmDelete.href = '/smart_core_erp/sales/delete/' + salesId;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    }
    
    // Status update handlers
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    const statusModalTitle = document.getElementById('statusModalTitle');
    const statusModalMessage = document.getElementById('statusModalMessage');
    const confirmStatusUpdate = document.getElementById('confirmStatusUpdate');
    
    // Confirm Order
    const confirmOrderBtn = document.getElementById('confirmOrder');
    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener('click', function() {
            statusModalTitle.textContent = 'Confirm Order';
            statusModalMessage.textContent = 'Are you sure you want to confirm this sales order? This action cannot be undone.';
            confirmStatusUpdate.onclick = function() { updateOrderStatus('confirmed'); };
            statusModal.show();
        });
    }
    
    // Mark as Shipped
    const markShippedBtn = document.getElementById('markShipped');
    if (markShippedBtn) {
        markShippedBtn.addEventListener('click', function() {
            statusModalTitle.textContent = 'Mark as Shipped';
            statusModalMessage.textContent = 'Are you sure you want to mark this order as shipped?';
            confirmStatusUpdate.onclick = function() { updateOrderStatus('shipped'); };
            statusModal.show();
        });
    }
    
    // Mark as Delivered
    const markDeliveredBtn = document.getElementById('markDelivered');
    if (markDeliveredBtn) {
        markDeliveredBtn.addEventListener('click', function() {
            statusModalTitle.textContent = 'Mark as Delivered';
            statusModalMessage.textContent = 'Are you sure you want to mark this order as delivered?';
            confirmStatusUpdate.onclick = function() { updateOrderStatus('delivered'); };
            statusModal.show();
        });
    }
    
    // Cancel Order
    const cancelOrderBtn = document.getElementById('cancelOrder');
    if (cancelOrderBtn) {
        cancelOrderBtn.addEventListener('click', function() {
            statusModalTitle.textContent = 'Cancel Order';
            statusModalMessage.textContent = 'Are you sure you want to cancel this sales order? This action cannot be undone.';
            confirmStatusUpdate.onclick = function() { updateOrderStatus('cancelled'); };
            statusModal.show();
        });
    }
    
    // Update order status function
    function updateOrderStatus(newStatus) {
        fetch('/smart_core_erp/sales/update_status/' + salesOrderId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'status=' + newStatus + '&ajax=true'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to update status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating status.');
        });
    }
    
    // Print functionality
    document.getElementById('downloadPDF').addEventListener('click', function() {
        alert('PDF download functionality would be implemented here.');
    });
    
    document.getElementById('sendEmail').addEventListener('click', function() {
        alert('Email functionality would be implemented here.');
    });
});
</script>