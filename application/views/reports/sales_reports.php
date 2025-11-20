<style>
.report-header {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .075);
}

.export-buttons .btn {
    margin-right: 5px;
    margin-bottom: 5px;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales Reports</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="export-buttons">
            <a href="/smart_core_erp/reports/export_report?report_type=sales_<?php echo $report_type; ?>&format=pdf"
                class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="/smart_core_erp/reports/export_report?report_type=sales_<?php echo $report_type; ?>&format=excel"
                class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="/smart_core_erp/reports/print_report?report_type=sales_<?php echo $report_type; ?>"
                class="btn btn-sm btn-primary" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
</div>

<!-- Report Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/reports/sales_reports">
            <div class="row">
                <div class="col-md-3">
                    <label for="report_type" class="form-label">Report Type</label>
                    <select class="form-select" id="report_type" name="report_type" onchange="this.form.submit()">
                        <option value="daily" <?php echo $report_type == 'daily' ? 'selected' : ''; ?>>
                            Daily Sales</option>
                        <option value="customer" <?php echo $report_type == 'customer' ? 'selected' : ''; ?>>
                            Customer-wise
                        </option>
                        <option value="product" <?php echo $report_type == 'product' ? 'selected' : ''; ?>>Product-wise
                        </option>
                        <option value="tax" <?php echo $report_type == 'tax' ? 'selected' : ''; ?>>Tax
                            Summary</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $end_date; ?>">
                </div>
                <?php if ($report_type == 'customer'): ?>
                <div class="col-md-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select class="form-select" id="customer_id" name="customer_id">
                        <option value="">All Customers</option>
                        <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer->id; ?>"
                            <?php echo $customer_id == $customer->id ? 'selected' : ''; ?>>
                            <?php echo $customer->customer_name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Header -->
<div class="report-header">
    <div class="row">
        <div class="col-md-6">
            <h4>
                <?php
                                switch($report_type) {
                                    case 'daily': echo 'Daily Sales Report'; break;
                                    case 'customer': echo 'Customer Sales Report'; break;
                                    case 'product': echo 'Product Sales Report'; break;
                                    case 'tax': echo 'Tax Summary Report'; break;
                                }
                                ?>
            </h4>
            <p class="text-muted mb-0">
                Period: <?php echo date('M d, Y', strtotime($start_date)); ?> to
                <?php echo date('M d, Y', strtotime($end_date)); ?>
            </p>
        </div>
        <div class="col-md-6 text-end">
            <p class="mb-1">Generated: <?php echo date('F d, Y g:i A'); ?></p>
            <p class="mb-0">Report Type: <?php echo ucfirst($report_type); ?> Report</p>
        </div>
    </div>
</div>

<!-- Report Content -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($report_data)): ?>

        <?php if ($report_type == 'daily'): ?>
        <!-- Daily Sales Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th class="text-end">Invoices</th>
                        <th class="text-end">Sales Amount</th>
                        <th class="text-end">Tax Amount</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                            $total_invoices = 0;
                                            $total_sales = 0;
                                            $total_tax = 0;
                                            $grand_total = 0;
                                            ?>
                    <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row->date)); ?></td>
                        <td class="text-end"><?php echo $row->invoice_count; ?></td>
                        <td class="text-end">
                            ₹<?php echo number_format($row->total_sales - $row->total_tax, 2); ?></td>
                        <td class="text-end">₹<?php echo number_format($row->total_tax, 2); ?></td>
                        <td class="text-end">
                            <strong>₹<?php echo number_format($row->total_sales, 2); ?></strong>
                        </td>
                    </tr>
                    <?php 
                                                $total_invoices += $row->invoice_count;
                                                $total_sales += ($row->total_sales - $row->total_tax);
                                                $total_tax += $row->total_tax;
                                                $grand_total += $row->total_sales;
                                            ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>Total</th>
                        <th class="text-end"><?php echo $total_invoices; ?></th>
                        <th class="text-end">₹<?php echo number_format($total_sales, 2); ?></th>
                        <th class="text-end">₹<?php echo number_format($total_tax, 2); ?></th>
                        <th class="text-end">
                            <strong>₹<?php echo number_format($grand_total, 2); ?></strong>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php elseif ($report_type == 'customer'): ?>
        <!-- Customer Sales Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th class="text-end">Invoices</th>
                        <th class="text-end">Sales Amount</th>
                        <th class="text-end">Tax Amount</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                            $total_invoices = 0;
                                            $total_sales = 0;
                                            $total_tax = 0;
                                            $grand_total = 0;
                                            ?>
                    <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?php echo $row->customer_name; ?></td>
                        <td class="text-end"><?php echo $row->invoice_count; ?></td>
                        <td class="text-end">
                            ₹<?php echo number_format($row->total_sales - $row->total_tax, 2); ?></td>
                        <td class="text-end">₹<?php echo number_format($row->total_tax, 2); ?></td>
                        <td class="text-end">
                            <strong>₹<?php echo number_format($row->total_sales, 2); ?></strong>
                        </td>
                    </tr>
                    <?php 
                                                $total_invoices += $row->invoice_count;
                                                $total_sales += ($row->total_sales - $row->total_tax);
                                                $total_tax += $row->total_tax;
                                                $grand_total += $row->total_sales;
                                            ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>Total</th>
                        <th class="text-end"><?php echo $total_invoices; ?></th>
                        <th class="text-end">₹<?php echo number_format($total_sales, 2); ?></th>
                        <th class="text-end">₹<?php echo number_format($total_tax, 2); ?></th>
                        <th class="text-end">
                            <strong>₹<?php echo number_format($grand_total, 2); ?></strong>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php elseif ($report_type == 'product'): ?>
        <!-- Product Sales Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Code</th>
                        <th class="text-end">Quantity Sold</th>
                        <th class="text-end">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                            $total_quantity = 0;
                                            $total_sales = 0;
                                            ?>
                    <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?php echo $row->product_name; ?></td>
                        <td><?php echo $row->product_code; ?></td>
                        <td class="text-end"><?php echo number_format($row->total_quantity, 2); ?></td>
                        <td class="text-end">
                            <strong>₹<?php echo number_format($row->total_sales, 2); ?></strong>
                        </td>
                    </tr>
                    <?php 
                                                $total_quantity += $row->total_quantity;
                                                $total_sales += $row->total_sales;
                                            ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="text-end"><?php echo number_format($total_quantity, 2); ?></th>
                        <th class="text-end">
                            <strong>₹<?php echo number_format($total_sales, 2); ?></strong>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php elseif ($report_type == 'tax'): ?>
        <!-- Tax Summary Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tax Rate</th>
                        <th class="text-end">Taxable Amount</th>
                        <th class="text-end">Tax Amount</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                            $total_taxable = 0;
                                            $total_tax = 0;
                                            $grand_total = 0;
                                            ?>
                    <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?php echo number_format($row->tax_rate, 2); ?>%</td>
                        <td class="text-end">₹<?php echo number_format($row->taxable_amount, 2); ?></td>
                        <td class="text-end">₹<?php echo number_format($row->tax_amount, 2); ?></td>
                        <td class="text-end">
                            <strong>₹<?php echo number_format($row->taxable_amount + $row->tax_amount, 2); ?></strong>
                        </td>
                    </tr>
                    <?php 
                                                $total_taxable += $row->taxable_amount;
                                                $total_tax += $row->tax_amount;
                                                $grand_total += ($row->taxable_amount + $row->tax_amount);
                                            ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>Total</th>
                        <th class="text-end">₹<?php echo number_format($total_taxable, 2); ?></th>
                        <th class="text-end">₹<?php echo number_format($total_tax, 2); ?></th>
                        <th class="text-end">
                            <strong>₹<?php echo number_format($grand_total, 2); ?></strong>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php endif; ?>

        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Data Available</h5>
            <p class="text-muted">No sales data found for the selected criteria.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Statistics -->
<?php if (!empty($report_data)): ?>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h6 class="card-title">Total Records</h6>
                <h4 class="card-text"><?php echo count($report_data); ?></h4>
            </div>
        </div>
    </div>
    <?php if ($report_type == 'daily' || $report_type == 'customer'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h6 class="card-title">Total Invoices</h6>
                <h4 class="card-text"><?php echo $total_invoices; ?></h4>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($report_type == 'product'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h6 class="card-title">Total Quantity</h6>
                <h4 class="card-text"><?php echo number_format($total_quantity, 2); ?></h4>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <h6 class="card-title">Total Sales</h6>
                <h4 class="card-text">
                    ₹<?php 
                                    if ($report_type == 'daily' || $report_type == 'customer') {
                                        echo number_format($grand_total, 2);
                                    } elseif ($report_type == 'product') {
                                        echo number_format($total_sales, 2);
                                    } elseif ($report_type == 'tax') {
                                        echo number_format($grand_total, 2);
                                    }
                                    ?>
                </h4>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>