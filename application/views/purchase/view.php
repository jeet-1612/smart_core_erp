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
    0% {
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
    }

    70% {
        box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
    }
}

@media print {

    .btn-toolbar,
    .card:not(.print-this) {
        display: none !important;
    }

    .card.print-this {
        display: block !important;
        border: 1px solid #000 !important;
    }
}
</style>

<!-- Page Heading -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Purchase Order Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/smart_core_erp/purchase" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Purchase
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
        <!-- Purchase Order Details Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Purchase Order Information</h6>
                <div>
                    <span class="badge bg-<?php 
                        switch($purchase_order->status) {
                            case 'draft': echo 'secondary'; break;
                            case 'sent': echo 'info'; break;
                            case 'confirmed': echo 'primary'; break;
                            case 'received': echo 'success'; break;
                            case 'cancelled': echo 'danger'; break;
                            default: echo 'secondary';
                        }
                    ?> fs-6">
                        <?php echo ucfirst($purchase_order->status); ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">PO Number:</th>
                                <td><?php echo $purchase_order->po_number; ?></td>
                            </tr>
                            <tr>
                                <th>Vendor:</th>
                                <td>
                                    <strong><?php echo $purchase_order->company_name; ?></strong><br>
                                    <small class="text-muted"><?php echo $purchase_order->contact_person; ?></small>
                                </td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $purchase_order->email; ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo $purchase_order->phone; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Order Date:</th>
                                <td><?php echo date('M d, Y', strtotime($purchase_order->po_date)); ?></td>
                            </tr>
                            <tr>
                                <th>Expected Delivery:</th>
                                <td><?php echo $purchase_order->expected_delivery_date ? date('M d, Y', strtotime($purchase_order->expected_delivery_date)) : 'Not set'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>GSTIN:</th>
                                <td><?php echo $purchase_order->gstin ?: 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Created On:</th>
                                <td><?php echo date('M d, Y h:i A', strtotime($purchase_order->created_at)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($purchase_order->address): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Vendor Address</h6>
                        <p><?php echo nl2br($purchase_order->address); ?></p>
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
                            <?php if (!empty($purchase_order_items)): ?>
                            <?php foreach ($purchase_order_items as $item): ?>
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
                                    <strong>₹<?php echo number_format($purchase_order->sub_total, 2); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tax Amount:</strong></td>
                                <td colspan="2" class="text-end">
                                    <strong>₹<?php echo number_format($purchase_order->tax_amount, 2); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                <td colspan="2" class="text-end">
                                    <strong
                                        class="text-primary fs-5">₹<?php echo number_format($purchase_order->total_amount, 2); ?></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes and Terms -->
        <?php if ($purchase_order->notes || $purchase_order->terms_conditions): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if ($purchase_order->notes): ?>
                    <div class="col-md-6">
                        <h6>Notes</h6>
                        <p class="text-muted"><?php echo nl2br($purchase_order->notes); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($purchase_order->terms_conditions): ?>
                    <div class="col-md-6">
                        <h6>Terms & Conditions</h6>
                        <p class="text-muted"><?php echo nl2br($purchase_order->terms_conditions); ?></p>
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
                    <a href="/smart_core_erp/purchase/edit/<?php echo $purchase_order->id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Purchase Order
                    </a>

                    <?php if ($purchase_order->status == 'draft'): ?>
                    <button type="button" class="btn btn-info" id="sendOrder">
                        <i class="fas fa-paper-plane"></i> Send to Vendor
                    </button>
                    <?php endif; ?>

                    <?php if ($purchase_order->status == 'sent'): ?>
                    <button type="button" class="btn btn-primary" id="confirmOrder">
                        <i class="fas fa-check-circle"></i> Confirm Order
                    </button>
                    <?php endif; ?>

                    <?php if ($purchase_order->status == 'confirmed'): ?>
                    <button type="button" class="btn btn-success" id="markReceived">
                        <i class="fas fa-check-double"></i> Mark as Received
                    </button>
                    <?php endif; ?>

                    <?php if ($purchase_order->status != 'cancelled' && $purchase_order->status != 'received'): ?>
                    <button type="button" class="btn btn-danger" id="cancelOrder">
                        <i class="fas fa-times-circle"></i> Cancel Order
                    </button>
                    <?php endif; ?>

                    <button type="button" class="btn btn-outline-danger delete-purchase"
                        data-id="<?php echo $purchase_order->id; ?>"
                        data-name="<?php echo $purchase_order->po_number; ?>">
                        <i class="fas fa-trash"></i> Delete Purchase Order
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
                    <div
                        class="timeline-item <?php echo $purchase_order->status == 'draft' ? 'active' : 'completed'; ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Draft</h6>
                            <small class="text-muted">Order created</small>
                        </div>
                    </div>
                    <div
                        class="timeline-item <?php echo $purchase_order->status == 'sent' ? 'active' : ($purchase_order->status == 'confirmed' || $purchase_order->status == 'received' ? 'completed' : ''); ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Sent</h6>
                            <small class="text-muted">Sent to vendor</small>
                        </div>
                    </div>
                    <div
                        class="timeline-item <?php echo $purchase_order->status == 'confirmed' ? 'active' : ($purchase_order->status == 'received' ? 'completed' : ''); ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Confirmed</h6>
                            <small class="text-muted">Vendor confirmed</small>
                        </div>
                    </div>
                    <div class="timeline-item <?php echo $purchase_order->status == 'received' ? 'active' : ''; ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Received</h6>
                            <small class="text-muted">Items received</small>
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
                        <i class="fas fa-print"></i> Print Purchase Order
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="downloadPDF">
                        <i class="fas fa-file-pdf"></i> Download as PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" id="sendEmail">
                        <i class="fas fa-envelope"></i> Email to Vendor
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    var purchaseOrderId = <?php echo $purchase_order->id; ?>;

    /* -------------------------
       DELETE CONFIRMATION
    ------------------------- */
    $(document).on('click', '.delete-purchase', function () {

        var purchaseId = $(this).data('id');
        var name = $(this).data('name');

        $('#purchaseName').text(name);
        $('#confirmDelete').attr('href', '/smart_core_erp/purchase/delete/' + purchaseId);

        var modal = new bootstrap.Modal($('#deleteModal')[0]);
        modal.show();
    });

    /* -------------------------
       STATUS MODAL SETUP
    ------------------------- */
    var statusModal = new bootstrap.Modal($('#statusModal')[0]);
    var statusModalTitle = $('#statusModalTitle');
    var statusModalMessage = $('#statusModalMessage');
    var confirmStatusUpdate = $('#confirmStatusUpdate');

    function openStatusModal(title, message, statusCode) {
        statusModalTitle.text(title);
        statusModalMessage.text(message);

        confirmStatusUpdate.off('click').on('click', function () {
            updateOrderStatus(statusCode);
        });

        statusModal.show();
    }

    /* -------------------------
       STATUS BUTTON HANDLERS
    ------------------------- */

    $('#sendOrder').on('click', function () {
        openStatusModal(
            'Send Order to Vendor',
            'Are you sure you want to send this purchase order to the vendor?',
            'sent'
        );
    });

    $('#confirmOrder').on('click', function () {
        openStatusModal(
            'Confirm Order',
            'Are you sure you want to confirm this purchase order?',
            'confirmed'
        );
    });

    $('#markReceived').on('click', function () {
        openStatusModal(
            'Mark as Received',
            'Are you sure you want to mark this order as received? This will update your inventory.',
            'received'
        );
    });

    $('#cancelOrder').on('click', function () {
        openStatusModal(
            'Cancel Order',
            'Are you sure you want to cancel this purchase order? This action cannot be undone.',
            'cancelled'
        );
    });

    /* -------------------------
       UPDATE ORDER STATUS
    ------------------------- */
    function updateOrderStatus(newStatus) {
        $.ajax({
            url: '/smart_core_erp/purchase/update_status/' + purchaseOrderId,
            method: 'POST',
            data: {
                status: newStatus,
                ajax: true
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to update status: ' + response.message);
                }
            },
            error: function () {
                alert('An error occurred while updating status.');
            }
        });
    }

    /* -------------------------
       PRINT & EMAIL
    ------------------------- */

    $('#downloadPDF').on('click', function () {
        alert('PDF download functionality would be implemented here.');
    });

    $('#sendEmail').on('click', function () {
        alert('Email functionality would be implemented here.');
    });

});
</script>