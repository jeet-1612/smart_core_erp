<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo $invoice->invoice_number; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            .container { max-width: 100% !important; }
        }
        .invoice-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .invoice-header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .invoice-footer { border-top: 1px solid #ddd; padding-top: 20px; margin-top: 40px; }
        .total-section { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="no-print text-center my-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Close
            </button>
        </div>

        <div class="invoice-container">
            <!-- Header -->
            <div class="invoice-header">
                <div class="row">
                    <div class="col-6">
                        <h2>TAX INVOICE</h2>
                        <h4><?php echo $invoice->invoice_number; ?></h4>
                    </div>
                    <div class="col-6 text-end">
                        <h3>Smart-Core ERP</h3>
                        <p class="mb-0">123 Business Street</p>
                        <p class="mb-0">Mumbai, Maharashtra 400001</p>
                        <p class="mb-0">GSTIN: 07AABCU9603R1ZM</p>
                    </div>
                </div>
            </div>

            <!-- Customer and Invoice Details -->
            <div class="row mb-4">
                <div class="col-6">
                    <h5>Bill To:</h5>
                    <p class="mb-1"><strong><?php echo $invoice->customer_name; ?></strong></p>
                    <?php if ($invoice->contact_person): ?>
                        <p class="mb-1">Attn: <?php echo $invoice->contact_person; ?></p>
                    <?php endif; ?>
                    <?php if ($invoice->address): ?>
                        <p class="mb-1"><?php echo $invoice->address; ?></p>
                    <?php endif; ?>
                    <?php if ($invoice->city || $invoice->state || $invoice->zip_code): ?>
                        <p class="mb-1">
                            <?php echo $invoice->city; ?>, 
                            <?php echo $invoice->state; ?> - 
                            <?php echo $invoice->zip_code; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($invoice->tax_number): ?>
                        <p class="mb-0">GSTIN: <?php echo $invoice->tax_number; ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-6 text-end">
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Invoice Date:</strong></td>
                            <td><?php echo date('d/m/Y', strtotime($invoice->invoice_date)); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Due Date:</strong></td>
                            <td><?php echo date('d/m/Y', strtotime($invoice->due_date)); ?></td>
                        </tr>
                        <?php if ($invoice->reference): ?>
                        <tr>
                            <td><strong>Reference:</strong></td>
                            <td><?php echo $invoice->reference; ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Tax Rate</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        $subtotal = 0;
                        foreach ($invoice_items as $item): 
                            $item_subtotal = $item->quantity * $item->unit_price;
                            $item_tax = $item_subtotal * ($item->tax_rate / 100);
                            $subtotal += $item_subtotal;
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td>
                                <?php if ($item->product_name): ?>
                                    <strong><?php echo $item->product_name; ?></strong>
                                    <?php if ($item->description): ?>
                                        <br><small><?php echo $item->description; ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo $item->description ?: 'Service'; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo number_format($item->quantity, 2); ?></td>
                            <td class="text-end">₹<?php echo number_format($item->unit_price, 2); ?></td>
                            <td class="text-end"><?php echo number_format($item->tax_rate, 2); ?>%</td>
                            <td class="text-end">₹<?php echo number_format($item->line_total, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="row justify-content-end">
                <div class="col-md-6">
                    <div class="total-section">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>₹<?php echo number_format($invoice->subtotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax Amount:</span>
                            <span>₹<?php echo number_format($invoice->tax_amount, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5">
                            <strong>Total Amount:</strong>
                            <strong>₹<?php echo number_format($invoice->total_amount, 2); ?></strong>
                        </div>
                        <?php if ($invoice->balance_due > 0): ?>
                        <div class="d-flex justify-content-between text-danger">
                            <strong>Balance Due:</strong>
                            <strong>₹<?php echo number_format($invoice->balance_due, 2); ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Notes and Terms -->
            <?php if ($invoice->customer_notes || $invoice->terms_conditions): ?>
            <div class="invoice-footer">
                <?php if ($invoice->customer_notes): ?>
                    <div class="mb-3">
                        <strong>Customer Notes:</strong>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($invoice->customer_notes)); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($invoice->terms_conditions): ?>
                    <div>
                        <strong>Terms & Conditions:</strong>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($invoice->terms_conditions)); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="mb-0">Thank you for your business!</p>
                <p class="mb-0">For any queries, contact us at: support@smartcoreerp.com | Phone: +91-9876543210</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>