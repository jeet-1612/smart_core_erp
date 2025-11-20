<style>
.report-header {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.financial-section {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
}

.positive {
    color: #28a745;
}

.negative {
    color: #dc3545;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .075);
}

.amount-cell {
    font-weight: bold;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Financial Reports</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="export-buttons">
            <a href="/smart_core_erp/reports/export_report?report_type=financial_<?php echo $report_type; ?>&format=pdf"
                class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="/smart_core_erp/reports/export_report?report_type=financial_<?php echo $report_type; ?>&format=excel"
                class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="/smart_core_erp/reports/print_report?report_type=financial_<?php echo $report_type; ?>"
                class="btn btn-sm btn-primary" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
</div>

<!-- Report Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/smart_core_erp/reports/financial_reports">
            <div class="row">
                <div class="col-md-4">
                    <label for="report_type" class="form-label">Report Type</label>
                    <select class="form-select" id="report_type" name="report_type" onchange="this.form.submit()">
                        <option value="profit_loss" <?php echo $report_type == 'profit_loss' ? 'selected' : ''; ?>>
                            Profit & Loss
                        </option>
                        <option value="balance_sheet" <?php echo $report_type == 'balance_sheet' ? 'selected' : ''; ?>>
                            Balance
                            Sheet</option>
                        <option value="cash_flow" <?php echo $report_type == 'cash_flow' ? 'selected' : ''; ?>>Cash Flow
                        </option>
                        <option value="accounts_receivable"
                            <?php echo $report_type == 'accounts_receivable' ? 'selected' : ''; ?>>
                            Accounts Receivable</option>
                        <option value="accounts_payable"
                            <?php echo $report_type == 'accounts_payable' ? 'selected' : ''; ?>>Accounts
                            Payable</option>
                    </select>
                </div>
                <?php if ($report_type != 'balance_sheet'): ?>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $end_date; ?>">
                </div>
                <?php else: ?>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">As of Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="<?php echo $end_date; ?>">
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
                                    case 'profit_loss': echo 'Profit & Loss Statement'; break;
                                    case 'balance_sheet': echo 'Balance Sheet'; break;
                                    case 'cash_flow': echo 'Cash Flow Statement'; break;
                                    case 'accounts_receivable': echo 'Accounts Receivable Report'; break;
                                    case 'accounts_payable': echo 'Accounts Payable Report'; break;
                                }
                                ?>
            </h4>
            <p class="text-muted mb-0">
                <?php if ($report_type == 'balance_sheet'): ?>
                As of: <?php echo date('M d, Y', strtotime($end_date)); ?>
                <?php else: ?>
                Period: <?php echo date('M d, Y', strtotime($start_date)); ?> to
                <?php echo date('M d, Y', strtotime($end_date)); ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-6 text-end">
            <p class="mb-1">Generated: <?php echo date('F d, Y g:i A'); ?></p>
            <p class="mb-0">Report Type: <?php echo ucfirst(str_replace('_', ' ', $report_type)); ?></p>
        </div>
    </div>
</div>

<!-- Report Content -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($report_data) || $report_type == 'accounts_payable'): ?>

        <?php if ($report_type == 'profit_loss'): ?>
        <!-- Profit & Loss Report -->
        <div class="financial-section">
            <h5 class="text-center mb-4">PROFIT & LOSS STATEMENT</h5>

            <!-- Revenue Section -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <strong>REVENUE</strong>
                </div>
                <div class="col-md-4 text-end">
                    <strong>Amount (₹)</strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-8">
                    Sales Revenue
                </div>
                <div class="col-md-4 text-end">
                    ₹<?php echo number_format($report_data['revenue'], 2); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-8">
                    <strong>Total Revenue</strong>
                </div>
                <div class="col-md-4 text-end">
                    <strong>₹<?php echo number_format($report_data['revenue'], 2); ?></strong>
                </div>
            </div>

            <!-- Cost of Goods Sold -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <strong>COST OF GOODS SOLD</strong>
                </div>
                <div class="col-md-4 text-end">
                    <strong>Amount (₹)</strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-8">
                    Cost of Goods Sold
                </div>
                <div class="col-md-4 text-end">
                    ₹<?php echo number_format($report_data['cogs'], 2); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-8">
                    <strong>Gross Profit</strong>
                </div>
                <div class="col-md-4 text-end">
                    <strong class="<?php echo $report_data['gross_profit'] >= 0 ? 'positive' : 'negative'; ?>">
                        ₹<?php echo number_format($report_data['gross_profit'], 2); ?>
                    </strong>
                </div>
            </div>

            <!-- Operating Expenses -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <strong>OPERATING EXPENSES</strong>
                </div>
                <div class="col-md-4 text-end">
                    <strong>Amount (₹)</strong>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-8">
                    Operating Expenses
                </div>
                <div class="col-md-4 text-end">
                    ₹<?php echo number_format($report_data['operating_expenses'], 2); ?>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="row mb-1 pt-3 border-top">
                <div class="col-md-8">
                    <strong>NET PROFIT</strong>
                </div>
                <div class="col-md-4 text-end">
                    <strong class="<?php echo $report_data['net_profit'] >= 0 ? 'positive' : 'negative'; ?>">
                        ₹<?php echo number_format($report_data['net_profit'], 2); ?>
                    </strong>
                </div>
            </div>

            <!-- Profit Margins -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <strong>Gross Profit Margin:</strong>
                    <?php echo number_format($report_data['gross_profit_margin'], 2); ?>%
                </div>
                <div class="col-md-6">
                    <strong>Net Profit Margin:</strong>
                    <?php echo number_format($report_data['net_profit_margin'], 2); ?>%
                </div>
            </div>
        </div>

        <?php elseif ($report_type == 'balance_sheet'): ?>
        <!-- Balance Sheet Report -->
        <div class="financial-section">
            <h5 class="text-center mb-4">BALANCE SHEET</h5>

            <div class="row">
                <!-- Assets -->
                <div class="col-md-6">
                    <h6>ASSETS</h6>
                    <table class="table table-sm">
                        <?php foreach ($report_data['assets'] as $asset_name => $amount): ?>
                        <tr>
                            <td><?php echo ucfirst(str_replace('_', ' ', $asset_name)); ?></td>
                            <td class="text-end">₹<?php echo number_format($amount, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-active">
                            <td><strong>Total Assets</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format($report_data['total_assets'], 2); ?></strong>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Liabilities & Equity -->
                <div class="col-md-6">
                    <h6>LIABILITIES & EQUITY</h6>
                    <table class="table table-sm">
                        <tr>
                            <td colspan="2"><strong>Liabilities</strong></td>
                        </tr>
                        <?php foreach ($report_data['liabilities'] as $liability_name => $amount): ?>
                        <tr>
                            <td><?php echo ucfirst(str_replace('_', ' ', $liability_name)); ?></td>
                            <td class="text-end">₹<?php echo number_format($amount, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-active">
                            <td><strong>Total Liabilities</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format(array_sum($report_data['liabilities']), 2); ?></strong>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><strong>Equity</strong></td>
                        </tr>
                        <?php foreach ($report_data['equity'] as $equity_name => $amount): ?>
                        <tr>
                            <td><?php echo ucfirst(str_replace('_', ' ', $equity_name)); ?></td>
                            <td class="text-end">₹<?php echo number_format($amount, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-active">
                            <td><strong>Total Equity</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format(array_sum($report_data['equity']), 2); ?></strong>
                            </td>
                        </tr>

                        <tr class="table-primary">
                            <td><strong>Total Liabilities & Equity</strong></td>
                            <td class="text-end">
                                <strong>₹<?php echo number_format($report_data['total_liabilities_equity'], 2); ?></strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php elseif ($report_type == 'cash_flow'): ?>
        <!-- Cash Flow Report -->
        <div class="financial-section">
            <h5 class="text-center mb-4">CASH FLOW STATEMENT</h5>

            <table class="table">
                <tr>
                    <td><strong>Cash at Beginning of Period</strong></td>
                    <td class="text-end">
                        ₹<?php echo number_format($report_data['beginning_cash'], 2); ?></td>
                </tr>

                <tr class="table-success">
                    <td><strong>Cash from Operating Activities</strong></td>
                    <td class="text-end">
                        ₹<?php echo number_format($report_data['operating_activities'], 2); ?></td>
                </tr>

                <tr class="table-info">
                    <td><strong>Cash from Investing Activities</strong></td>
                    <td class="text-end">
                        ₹<?php echo number_format($report_data['investing_activities'], 2); ?></td>
                </tr>

                <tr class="table-warning">
                    <td><strong>Cash from Financing Activities</strong></td>
                    <td class="text-end">
                        ₹<?php echo number_format($report_data['financing_activities'], 2); ?></td>
                </tr>

                <tr class="table-active">
                    <td><strong>Net Cash Flow</strong></td>
                    <td class="text-end <?php echo $report_data['net_cash_flow'] >= 0 ? 'positive' : 'negative'; ?>">
                        <strong>₹<?php echo number_format($report_data['net_cash_flow'], 2); ?></strong>
                    </td>
                </tr>

                <tr class="table-primary">
                    <td><strong>Cash at End of Period</strong></td>
                    <td class="text-end">
                        <strong>₹<?php echo number_format($report_data['ending_cash'], 2); ?></strong>
                    </td>
                </tr>
            </table>
        </div>

        <?php elseif ($report_type == 'accounts_receivable'): ?>
        <!-- Accounts Receivable Report -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Invoice Date</th>
                        <th>Due Date</th>
                        <th class="text-end">Invoice Amount</th>
                        <th class="text-end">Balance Due</th>
                        <th>Status</th>
                        <th>Days Overdue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                                            $total_invoice_amount = 0;
                                            $total_balance_due = 0;
                                            ?>
                    <?php foreach ($report_data as $invoice): ?>
                    <tr>
                        <td><strong><?php echo $invoice->invoice_number; ?></strong></td>
                        <td><?php echo $invoice->customer_name; ?></td>
                        <td><?php echo date('M d, Y', strtotime($invoice->invoice_date)); ?></td>
                        <td><?php echo date('M d, Y', strtotime($invoice->due_date)); ?></td>
                        <td class="text-end">₹<?php echo number_format($invoice->total_amount, 2); ?>
                        </td>
                        <td class="text-end"><strong
                                class="text-danger">₹<?php echo number_format($invoice->balance_due, 2); ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-warning"><?php echo ucfirst($invoice->status); ?></span>
                        </td>
                        <td>
                            <?php
                                                    $due_date = new DateTime($invoice->due_date);
                                                    $today = new DateTime();
                                                    $days_overdue = $today->diff($due_date)->days;
                                                    if ($today > $due_date) {
                                                        echo '<span class="text-danger">' . $days_overdue . ' days</span>';
                                                    } else {
                                                        echo '<span class="text-success">Due in ' . $days_overdue . ' days</span>';
                                                    }
                                                    ?>
                        </td>
                    </tr>
                    <?php 
                                                $total_invoice_amount += $invoice->total_amount;
                                                $total_balance_due += $invoice->balance_due;
                                            ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4">Total</th>
                        <th class="text-end">₹<?php echo number_format($total_invoice_amount, 2); ?>
                        </th>
                        <th class="text-end"><strong
                                class="text-danger">₹<?php echo number_format($total_balance_due, 2); ?></strong>
                        </th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php elseif ($report_type == 'accounts_payable'): ?>
        <!-- Accounts Payable Report -->
        <div class="text-center py-5">
            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Accounts Payable Report</h5>
            <p class="text-muted">This report will display outstanding bills and payments due to
                suppliers.</p>
            <p class="text-muted"><small>Note: This feature requires the Purchase module to be
                    implemented.</small></p>
        </div>

        <?php endif; ?>

        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Data Available</h5>
            <p class="text-muted">No financial data found for the selected criteria.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Statistics -->
<?php if (!empty($report_data) && $report_type != 'accounts_payable'): ?>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h6 class="card-title">Report Period</h6>
                <h6 class="card-text">
                    <?php if ($report_type == 'balance_sheet'): ?>
                    As of <?php echo date('M d, Y', strtotime($end_date)); ?>
                    <?php else: ?>
                    <?php echo date('M d', strtotime($start_date)); ?> -
                    <?php echo date('M d, Y', strtotime($end_date)); ?>
                    <?php endif; ?>
                </h6>
            </div>
        </div>
    </div>

    <?php if ($report_type == 'profit_loss'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h6 class="card-title">Net Profit</h6>
                <h4 class="card-text">₹<?php echo number_format($report_data['net_profit'], 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h6 class="card-title">Profit Margin</h6>
                <h4 class="card-text">
                    <?php echo number_format($report_data['net_profit_margin'], 2); ?>%</h4>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($report_type == 'balance_sheet'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h6 class="card-title">Total Assets</h6>
                <h4 class="card-text">₹<?php echo number_format($report_data['total_assets'], 2); ?>
                </h4>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($report_type == 'cash_flow'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <h6 class="card-title">Net Cash Flow</h6>
                <h4 class="card-text">₹<?php echo number_format($report_data['net_cash_flow'], 2); ?>
                </h4>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($report_type == 'accounts_receivable'): ?>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <h6 class="card-title">Total Due</h6>
                <h4 class="card-text">₹<?php echo number_format($total_balance_due, 2); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h6 class="card-title">Total Invoices</h6>
                <h4 class="card-text"><?php echo count($report_data); ?></h4>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>